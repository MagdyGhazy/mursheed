<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\State;
use App\Models\Driver;
use App\Models\Guides;
use App\Models\Favourite;
use App\Models\MursheedUser;
use Illuminate\Http\Request;
use App\Models\Languagesable;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\GuideRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\DriverRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Pipeline;
use App\Notifications\SendEmailForApprove;
use App\Http\Controllers\ControllerHandler;
use App\Http\Controllers\Filter\SearchByName;
use App\Http\Controllers\Filter\SearchByPrice;
use App\Http\Controllers\Filter\SearchByState;
use App\Http\Controllers\Filter\SearchByCountry;
use App\Http\Controllers\Filter\SearchByCarModel;
use App\Http\Controllers\Filter\SearchByLanguage;
use App\Http\Requests\Driver\UpdateProfileRequest;
use App\Http\Controllers\Filter\SearchByCarCategory;
use App\Models\Language;

class Drivercontroller extends Controller
{
    private $ControllerHandler;

    public function __construct()
    {
        $this->ControllerHandler = new ControllerHandler(new Driver());
    }

    public function index()
    {

        $model = Driver::with(['languagesable', 'country', 'state', 'media'])->orderBy('total_rating', 'DESC')->get();
        $k = array_search('media', ['languagesable', 'country', 'state', 'media'], true);

        if ($k !== false) {
            $model = Driver::with(['languagesable', 'country', 'state', 'media', 'priceServices' => function ($query) {
                $query->first();
            }])
                ->addSelect([
                    'email_verified' => MursheedUser::select('email_verified_at')->whereColumn('mursheed_users.email', 'drivers.email')
                ])
                ->get()
                ->map(function ($data) {
                    $collect = collect(collect($data)['media'])->groupBy('collection_name')->toArray();

                    $data['pictures'] = count($collect) ? $collect : null;
                    $data['email_verified'] = $data['email_verified'] ? Carbon::parse($data['email_verified'])->diffForHumans() : "Not Yet";
                    return $data;
                });
        }

        return response([
            "drivers" => $model,
            "message" => "success",
            "status" => true
        ], 200);
    }
    public function index_mobile(Request $request)
    {
        //        return $request->carBrandName;

        $drivers = Driver::query()
            ->when($request->has('price'), fn ($query) => $query->whereHas('priceServices', fn ($query) => $query->where('price', '<=', $request->price)))
            ->when($request->has('language_id'), fn ($query) => $query->whereHas('languagesable', fn ($query) => $query->whereIn('language_id', $request->language_id)))
            ->when($request->has('carBrandName'), fn ($query) => $query->where('car_brand_name', "$request->carBrandName"))
            ->when($request->has('name'), fn ($query) => $query->where('name', 'LIKE', "%{$request->name}%"))
            ->when($request->has('rating'), fn ($query) => $query->where('total_rating', (float)$request->rating))
            ->when($request->has('country_id'), fn ($query) => $query->where('country_id', $request->country_id))
            ->when($request->has('state_id'), fn ($query) => $query->where('state_id', $request->state_id))
            ->when($request->has('car_type'), fn ($query) => $query->where('car_type', $request->car_type))
            ->where('status', 1)
            ->select('id', 'name', 'state_id', 'country_id', 'total_rating', 'ratings_count')
            ->withCount(['favourites' => function ($q) {
                $q->where('tourist_id', '=', auth()->user()->user_id);
            }])
            ->addSelect(['state_name' => State::select('state')->whereColumn('states.id', 'drivers.state_id')])
            ->with(['priceServices' => function ($query) {
                $query->limit(1)->latest();
            }])
            ->orderBy('total_rating', 'DESC');

        $perPage = $request->input('per_page', 10); // Default to 10 items per page
        $page = $request->input('page', 1);
        $paginatedDrivers = $drivers->paginate($perPage, ['*'], 'page', $page);

        $paginatedDrivers->getCollection()->each(function ($driver) {
//            $driver->personal_photo = count($driver->getMedia('personal_photo')) == 0 ? url("default_user.jpg") : $driver->getMedia('personal_photo')->first()->getUrl();

            $driver->personal_photo = empty($driver->getFirstMediaUrl('personal_pictures')) ? url("default_user.jpg") : $driver->getFirstMediaUrl('personal_pictures');


            $car_photos = [] ;
            foreach ($driver->getMedia('car_photos') as $media) {
                $car_photos[] = $media->getUrl();
            }


            $driver->image_background = count($driver->getMedia('car_photo')) == 0 ? url("car_photo_default.jpg") : $driver->getMedia('car_photo')->first()->getUrl();
//

            $driver->car_photos = empty($driver->getMedia('car_photos')) ? url("default_user.jpg") : $car_photos;
            unset($driver->media);

            $driver->is_favourite = $driver->favourites()->where('tourist_id',auth()->user()->user_id)->count() > 0 ;
        });

        //        $drivers = Pipeline::send($drivers)
        //            ->through([
        ////                SearchByName::class,
        ////                SearchByCountry::class,
        ////                SearchByState::class,
        ////                SearchByCarCategory::class,
        ////                SearchByCarModel::class,
        //            ])
        //            ->then(fn($user) => $user
        ////                ->select('id', 'name', 'state_id', 'country_id', 'total_rating', 'ratings_count')
        ////                ->withCount(['favourites' => function ($q) {
        ////                    $q->where('tourist_id', '=', auth()->user()->user_id);
        ////                }])
        ////                ->addSelect(['state_name' => State::select('state')->whereColumn('states.id', 'drivers.state_id')])
        ////                ->with(['priceServices'=>function($query){
        ////                    $query->limit(1)->latest();
        ////                }])
        ////                ->orderBy('total_rating', 'DESC')
        ////                ->get()
        ////                ->each(function ($driver) {
//                            $driver->personal_photo =
//                                count($driver->getMedia('personal_photo')) == 0
//                                    ? url("default_user.jpg") : $driver->getMedia('personal_photo')->first()->getUrl();
//
//                            $driver->image_background =
//                                count($driver->getMedia('car_photo')) == 0
//                                    ? url("car_photo_default.jpg") : $driver->getMedia('car_photo')->first()->getUrl();
//                            unset($driver->media);
//
//                            $driver->is_favourite = $driver->favourites()->where('tourist_id',auth()->user()->user_id)->count() > 0 ;
        ////
        ////                })
        //            );
        $data = json_decode(json_encode($paginatedDrivers), true);

        return response()->json([
            "success" => true,
            "message" => "latest drivers in state",
            "current_page" => $data['current_page'],
            "drivers" => $data['data'],
            "first_page_url" => $data['first_page_url'],
            "from" => $data['from'],
            "last_page" => $data['last_page'],
            "last_page_url" => $data['last_page_url'],
            "links" => $data['links'],
            "next_page_url" => $data['next_page_url'],
            "path" => $data['path'],
            "prev_page_url" => $data['prev_page_url'],
            "to" => $data['to'],
            "total" => $data['total'],

        ], 200);
    }

    public function show(driver $driver)
    {
        $car_photos = [];
        $document = [];
        $driver->load(['country', 'state'])->with('priceServices');

        if (count($driver->getMedia('car_photo')) >= 0) {
            foreach ($driver->getMedia('car_photo') as $media) {
                $car_photos[] = $media->getUrl();
            }
        }

        if (count($driver->getMedia('document')) >= 0) {
            foreach ($driver->getMedia('document') as $media) {
                $document[] = $media->getUrl();
            }
        }


//        $driver = Driver::where('email', $request->email)->first();
//                    if (count($driver->getMedia('car_photos')) >= 0) {
        $car_photos = [] ;
        foreach ($driver->getMedia('car_photos') as $media) {
            $car_photos[] = $media->getUrl();
        }
//                    }



        $collect = collect(collect($driver)['media'])->groupBy('collection_name')->toArray();
        $driver['pictures'] = count($collect) ? $collect : null;

        $driver["personal_photo"] = empty($driver->getFirstMediaUrl('personal_pictures')) ? url("car_photo_default.jpg") : $driver->getFirstMediaUrl('personal_pictures');
        $driver["car_photo"] = count($car_photos) == 0 ? [url("car_photo_default.jpg")] : $car_photos;
        $driver["document"] = count($document) == 0 ? [url("car_photo_default.jpg")] : $document;
        return response()->json([
            "success" => true,
            "message" => "driver details",
            "user" => [
                "id" => $driver->id,
                "name" => $driver->name,
                "country" => $driver->country->country,
                "state" => $driver->state->state,
                "lang" => [],
                "bio" => $driver->bio,
                "car_type" => $driver->car_type,
                "car_model" => $driver->car_brand_name,
                "car_date" => $driver->car_manufacturing_date,
//                "personal_photo" => empty($driver->getFirstMediaUrl('personal_pictures')) ? url("car_photo_default.jpg") : $driver->getFirstMediaUrl('personal_pictures'),
                "personal_photo" => empty($driver->getFirstMediaUrl('personal_pictures')) ? url("default_user.jpg") : $driver->getFirstMediaUrl('personal_pictures'),

//                "car_photo" => count($car_photos) == 0 ? [url("car_photo_default.jpg")] : $car_photos,
                "car_photo" => empty($driver->getMedia('car_photos')) ? url("default_user.jpg") : $car_photos,

                "total_rate" => $driver->total_rating,
                "count_rate" => $driver->ratings_count,
                'pictures' => $driver->pictures,
                'document' => $driver->document,
                'priceServices' => $driver->priceServices,
            ],
        ], 200);
    }

    public function show_web(driver $driver)
    {
        $driver = Driver::where('id', $driver->id)->with(['media', 'country', 'state', 'languagesable', 'priceServices'])->first();

        $car_photos = [];
        $document = [];
        if (count($driver->getMedia('car_photo')) >= 0) {
            foreach ($driver->getMedia('car_photo') as $media) {
                $car_photos[] = $media->getUrl();
            }
        }
        if (count($driver->getMedia('document')) >= 0) {
            foreach ($driver->getMedia('document') as $media) {
                $document[] = $media->getUrl();
            }
        }

        $collect = collect(collect($driver)['media'])->groupBy('collection_name')->toArray();
        $driver['pictures'] = count($collect) ? $collect : null;

        $driver["personal_photo"] = empty($driver->getFirstMediaUrl('personal_pictures')) ? url("car_photo_default.jpg") : $driver->getFirstMediaUrl('personal_pictures');
        $driver["car_photo"] = count($car_photos) == 0 ? [url("car_photo_default.jpg")] : $car_photos;
        $driver["document"] = count($document) == 0 ? [url("car_photo_default.jpg")] : $document;

        return response([
            "data" => $driver,
            "message" => "success",
            "status" => true
        ], 200);
    }

    public function store(DriverRequest $request)
    {

        $data = $request->validated();
        $data['password'] = Hash::make($request->validated('password'));
        $data['status'] = -1;

        $driver = Driver::create($data);
        $global_user = MursheedUser::where("email", $driver->email)->first();
        return response()->json([
            'status' => true,
            'message' => 'driver successfully created',
            'token' => $global_user->createToken("API TOKEN")->plainTextToken,
            "user" => [
                "id" => $driver->id,
                "name" => $driver->name,
                "notification_id" => $driver->mursheed_user->id,
                "phone" => $driver->phone,
                "email" => $driver->email,
                "is_verified" => $driver->email_verified_at ? true : false,
                "type" => "Driver",
                "nationality" => $driver->nationality,
                "country_id" => (int)$driver->country_id,
                "state_id" => (int)$driver->state_id,
                "gender" => $driver->gender ? ($driver->gender == 1 ? "male" : "female") : null,
                "personal_photo" => empty($driver->getFirstMediaUrl('personal_pictures')) ? null : $driver->getFirstMediaUrl('personal_pictures'),
            ],
        ], 201);
    }

    public function update_mobile(UpdateProfileRequest $request)
    {
        $user = Auth::user();

        $car_photos = [];
        $driver = Driver::where('email', $request->email)->first();

        if ($driver == null) {
            return response()->json(["message" => "unauthenticated"], 401);
        }

        $global_user = $request->user();

        $global_user->update([
            'email' => $request->email ? $request->email : $global_user->email,
            'password' => $request->password ? Hash::make($request->password) : $global_user->password
        ]);
        if ($request->has('languages')) {
            $languagesable = Languagesable::where('languagesable_id', $user->user_id)->delete();
        }

        foreach ($request->languages as $value) {
            Languagesable::create(
                [
                    'languagesable_type' => "App\Models\Driver",
                    'languagesable_id' => $user->user_id,
                    'language_id' => $value
                ]
            );
        }
        $languages = Languagesable::where('languagesable_id', $user->id)->with([
            'language' => function ($query) {
            $query->select('id','lang')
            ;}
        ])->get();


        if ($request->document) {
            $driver->clearMediaCollection('document');
            $driver->addMultipleMediaFromRequest(['document'])->each(function ($fileAdder) {
                $fileAdder->toMediaCollection('document');
            });
        }





        if ($request->hasFile('car_photos')) {
            $driver->clearMediaCollection('car_photos');
            foreach ($request->file('car_photos') as $image) {
                $driver->addMedia($image)->toMediaCollection('car_photos');
            }
        }

        if (count($driver->getMedia('car_photos')) >= 0) {
            foreach ($driver->getMedia('car_photos') as $media) {
                $car_photos[] = $media->getUrl();
            }
        }

        if ($request->personal_pictures) {
            $driver->clearMediaCollection('personal_pictures');
            $driver->addMultipleMediaFromRequest(['personal_pictures'])->each(function ($fileAdder) {
                $fileAdder->toMediaCollection('personal_pictures');
            });
        }


        $data = $request->except('personal_pictures', 'languages', 'car_photos', 'document');

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $driver->update($data);
        return response()->json([
            'status' => true,
            'message' => 'Driver Update Is Successfully',
            'token' => $global_user->createToken("API TOKEN")->plainTextToken,
            "user" => [
                "id" => $driver->id,
                "name" => $driver->name,
                "notification_id" => $driver->mursheed_user->id,
                "phone" => $driver->phone,
                "email" => $driver->email,
                "is_verified" => $driver->email_verified_at ? true : false,
                "type" => "Driver",
                "nationality" => $driver->nationality,
                "country_id" => (int)$driver->country_id,
                "state_id" => (int)$driver->state_id,
                "gender" => $driver->gender ? ($driver->gender == 1 ? "male" : "female") : null,
                "bio" => $driver->bio,
                "car_number" => $driver->car_number,
                "driver_licence_number" => $driver->driver_licence_number,
                "gov_id" => $driver->gov_id,
                "status" => $driver->status,
                "car_type" => $driver->car_type,
                "car_brand_name" => $driver->car_brand_name,
                "car_manufacturing_date" => $driver->car_manufacturing_date,
                "admin_rating" => $driver->admin_rating,
                "ratings_count" => $driver->ratings_count,
                "ratings_sum" => $driver->ratings_sum,
                "total_rating" => $driver->total_rating,
                "languages" => $languages,
                "car_photo" => count($car_photos) == 0 ? [url("car_photo_default.jpg")] : $car_photos,
                "personal_photo" => empty($driver->getFirstMediaUrl('personal_pictures')) ? null : $driver->getFirstMediaUrl('personal_pictures'),
                "document" => empty($driver->getFirstMediaUrl('document')) ? null : $driver->getFirstMediaUrl('document'),


            ],
        ], 201);
    }

    public function update(GuideRequest $request, driver $driver)
    {

        $global_user = MursheedUser::where('email', $driver->email)->first();
        $global_user->update([
            'email' => $request->email ? $request->email : $global_user->email,
            'password' => $request->password ? Hash::make($request->password) : $global_user->password
        ]);

        if ($request->personal_pictures) {
            $driver->clearMediaCollection('personal_pictures');
            $driver->addMultipleMediaFromRequest(['personal_pictures'])->each(function ($fileAdder) {
                $fileAdder->toMediaCollection('personal_pictures');
            });
        }
        if ($request->car_photos) {
            $driver->clearMediaCollection('car_photos');
            $driver->addMultipleMediaFromRequest(['car_photos'])->each(function ($fileAdder) {
                $fileAdder->toMediaCollection('car_photos');
            });
        }


        return $this->ControllerHandler->update(
            "driver",
            $driver,
            array_merge(
                $request->except('personal_pictures', 'languages', 'car_photos'),
                $request->password ? ['password' => Hash::make($request->password), 'status' => -1] : ['status' => -1]
            )
        );
    }

    public function destroy(driver $driver)
    {
        return $this->ControllerHandler->destory("driver", $driver);
    }

    public function active(driver $driver)
    {

        $this->ControllerHandler->update("driver", $driver, ['status' => 1]);
    }

    public function inActive(driver $driver)
    {
        $this->ControllerHandler->update("driver", $driver, ['status' => 0]);
        return $driver->notify(new SendEmailForApprove());
    }

    public function change(driver $driver)
    {
        $this->ControllerHandler->update("driver", $driver, ['status' => $driver->status ^ 1]);
        return $driver->notify(new SendEmailForApprove());
    }

    public function getLatestWithCity(Request $request)
    {
        // return $this->ControllerHandler
        //     ->getWhere("drivers", 'state_id', $state_id, 4);

        $drivers = Driver::query()
            ->select('id', 'name', 'state_id', 'total_rating')->with(['priceServices' => function ($query) {
                $query->first();
            }])
            ->addSelect(['state_name' => State::select('state')->whereColumn('states.id', 'drivers.state_id')])
            ->when($request->state_id, function ($query) use ($request) {
                $query->where('state_id', $request->state_id);
            })
            ->orderBy('ratings_sum', 'DESC')
            ->with(['priceServices' => function ($query) {
                $query->limit(1)->latest();
            }])
            ->where('status', 1)

            ->limit(4)
            ->get()
            ->each(function ($driver) {
//                $driver->personal_photo = count($driver->getMedia('personal_photo')) == 0 ? url("default_user.jpg") : $driver->getMedia('personal_photo')->first()->getUrl();
                $driver->personal_photo = empty($driver->getFirstMediaUrl('personal_pictures')) ? url("default_user.jpg") : $driver->getFirstMediaUrl('personal_pictures');

                $car_photos = [] ;
                foreach ($driver->getMedia('car_photos') as $media) {
                    $car_photos[] = $media->getUrl();
                }
                $driver->image_background = count($driver->getMedia('car_photo')) == 0 ? url("car_photo_default.jpg") : $driver->getMedia('car_photo')->first()->getUrl();
                
                $driver->car_photos = empty($driver->getMedia('car_photos')) ? url("default_user.jpg") : $car_photos;

                $driver->is_favourite = $driver->favourites()->where('tourist_id', auth()->user()->user_id)->count() > 0;


                unset($driver->media);
            });

        return response()->json([
            "success" => true,
            "message" => "latest drivers in state",
            "drivers" => $drivers,
        ], 200);
    }

    public function getDriverByCityWithPriceList(Request $request)
    {
        return response([
            "drivers" => Driver::whereHas('priceServices', function ($query) use ($request) {
                $query->where('city_id', $request->city_id);
            })->with('priceServices')->get()->append('state_name')->each(function ($driver) {
                $driver->personal_photo =
                    count($driver->getMedia('personal_photo')) == 0
                    ? url("default_user.jpg") : $driver->getMedia('personal_photo')->first()->getUrl();

                $driver->image_background =
                    count($driver->getMedia('car_photo')) == 0
                    ? url("car_photo_default.jpg") : $driver->getMedia('car_photo')->first()->getUrl();

                unset($driver->media);
            }),
            "status" => true

        ]);
    }
}
