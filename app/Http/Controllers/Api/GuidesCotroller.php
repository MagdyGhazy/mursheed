<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ControllerHandler;
use App\Http\Controllers\Filter\SearchByLanguage;
use App\Http\Controllers\Filter\SearchByPrice;
use App\Http\Requests\Guide\GuideRequest;
use App\Http\Requests\Guide\UpdateProfileRequest;
use App\Models\Guides;
use App\Models\Languagesable;
use App\Models\MursheedUser;
use App\Models\State;
use App\Notifications\SendEmailForApprove;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserUpdateRequest;
use Illuminate\Support\Facades\Pipeline;
use App\Http\Controllers\Filter\SearchByName;
use App\Http\Controllers\Filter\SearchByState;
use App\Http\Controllers\Filter\SearchByCountry;
use App\Models\Tourist;

class GuidesCotroller extends Controller
{
    private $ControllerHandler;

    public function __construct()
    {
        $this->ControllerHandler = new ControllerHandler(new Guides());
    }

    public function index()
    {
        $model = Guides::with(['languagesable', 'country', 'state', 'media'])->orderBy('total_rating', 'DESC')->get();
        $k = array_search('media', ['languagesable', 'country', 'state', 'media'], true);

        if ($k !== false) {
            $model = Guides::with(['languagesable', 'country', 'state', 'media', 'priceServices' => function ($query) {
                $query->first();
            }])
                ->addSelect([
                    'email_verified' => MursheedUser::select('email_verified_at')->whereColumn('mursheed_users.email', 'guides.email')
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
            "guides" => $model,
            "message" => "success",
            "status" => true
        ], 200);
    }

    public function index_mobile(Request $request)
    {
        $guides = Guides::query()

            ->when($request->has('price'), fn ($query) => $query->whereHas('priceServices', fn ($query) => $query->where('price', '<=', $request->price)))
            ->when($request->has('language_id'), fn ($query) => $query->whereHas('languagesable', fn ($query) => $query->whereIn('language_id', $request->language_id)))
            ->when($request->has('name'), fn ($query) => $query->where('name', 'LIKE', "%{$request->name}%"))
            ->when($request->has('rating'), fn ($query) => $query->where('total_rating', (float)$request->rating))

            ->when($request->has('country_id'), fn ($query) => $query->where('country_id', $request->country_id))
            ->when($request->has('state_id'), fn ($query) => $query->where('state_id', $request->state_id))->where('status', 1)

            ->select('id', 'name', 'state_id', 'total_rating')
            ->with(['priceServices' => function ($query) {
                $query->limit(1)->latest();
            }])
            ->withCount(['favourites' => function ($q) {
                $q->where('tourist_id', '=', auth()->user()->user_id);
            }])
            ->addSelect(['state_name' => State::select('state')->whereColumn('states.id', 'guides.state_id')])
            ->orderBy('ratings_sum', 'DESC');
        //            ->limit(4)->get()

        $perPage = $request->input('per_page', 10); // Default to 10 items per page
        $page = $request->input('page', 1);

        $paginatedGuides = $guides->paginate($perPage, ['*'], 'page', $page);

        $paginatedGuides->getCollection()->each(function ($guide) {
//            $guide->personal_photo = count($guide->getMedia('personal_photo')) == 0 ? url("default_user.jpg") : $guide->getMedia('personal_photo')->first()->getUrl();
            $guide->personal_photo = empty($guide->getFirstMediaUrl('personal_pictures')) ? url("default_user.jpg") : $guide->getFirstMediaUrl('personal_pictures');


            $guide->image_background = $guide->getFirstMediaUrl('personal_pictures');
            unset($guide->media);

            $guide->is_favourite = $guide->favourites()->where('tourist_id', auth()->user()->user_id)->count() > 0;
            unset($guide->favourites_count);
        });


        //        $guides = Pipeline::send(Guides::query())
        //
        //            ->through([
        //
        //                SearchByName::class,
        //                SearchByCountry::class,
        //                SearchByState::class,
        //            ])
        //            ->then(fn ($user) => $user
        //                ->select('id', 'name', 'state_id','total_rating')
        //                ->with(['priceServices'=>function($query){
        //                    $query->limit(1)->latest();
        //                }])
        //
        //                ->withCount(['favourites' =>function($q){
        //                    $q->where('tourist_id','=',auth()->user()->user_id);
        //                 }])
        //                ->addSelect(['state_name' => State::select('state')->whereColumn('states.id', 'guides.state_id')])
        //                ->orderBy('ratings_sum','DESC')
        //                ->limit(4)->get()
        //                ->each(function ($guide) {
        //                    $guide->personal_photo =
        //                        count($guide->getMedia('personal_photo')) == 0
        //                            ? url("default_user.jpg") : $guide->getMedia('personal_photo')->first()->getUrl();
        //
        //                    $guide->image_background = url("guide_default.jpg");
        //                    unset($guide->media);
        //
        //                    $guide->is_favourite = $guide->favourites()->where('tourist_id',auth()->user()->user_id)->count() > 0 ;
        //                    unset($guide->favourites_count);
        //                })
        //);


        $data = json_decode(json_encode($paginatedGuides), true);

        return response()->json([
            "success" => true,
            "message" => "latest guides in state",
            "current_page" => $data['current_page'],
            "guides" => $data['data'],
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

    public function show(Guides $guide)
    {
        // return $this->ControllerHandler->showWith("guide", $guide, ['media']);

        $car_photos = [];
        $guide->load(['country', 'state', 'priceServices'])->with(['priceServices']);

        if (count($guide->getMedia('car_photo')) >= 0) {
            foreach ($guide->getMedia('car_photo') as $media) {
                $car_photos[] = $media->getUrl();
            }
        }

        return response()->json([
            "success" => true,
            "message" => "guide details",
            "user" => [
                "id" => $guide->id,
                "name" => $guide->name,
                "country" => $guide->country->country,
                "state" => $guide->state->state,
                "lang" => [],
                "bio" => $guide->bio,
                "personal_photo" => empty($guide->getFirstMediaUrl('personal_pictures')) ? url("default_user.jpg") : $guide->getFirstMediaUrl('personal_pictures'),
                "total_rate" => $guide->total_rating,
                "count_rate" => $guide->ratings_count,
                "priceServices" => $guide->priceServices
            ],
        ], 200);
    }

    public function store(GuideRequest $request)
    {

        // return $this->ControllerHandler->storeWithMediaAndLanguages(
        //     "guide",
        //     array_merge(
        //         $request->except('personal_pictures', 'languages'),
        //         $request->password ? ['password' => Hash::make($request->password), 'status' => -1] : ['status' => -1]
        //     ),
        //     ['personal_pictures'],
        //     $request->languages
        // );

        $data = $request->validated();
        $data['password'] = Hash::make($request->validated('password'));
        $data['status'] = -1;

        $guide = Guides::create($data);
        $global_user = MursheedUser::where("email", $guide->email)->first();

        return response()->json([
            'status' => true,
            'message' => 'tourist successfully created',
            'token' => $global_user->createToken("API TOKEN")->plainTextToken,
            "user" => [
                "id" => $guide->id,
                "notification_id" => $guide->mursheed_user->id,
                "name" => $guide->name,
                "phone" => $guide->phone,
                "email" => $guide->email,
                "is_verified" => $guide->email_verified_at ? true : false,
                "type" =>  "Guides",
                "nationality" => $guide->nationality,
                "country_id" => (int)$guide->country_id,
                "state_id" => (int)$guide->state_id,
                "gender" => $guide->gender ? ($guide->gender == 1 ? "male" : "female") : null,
                "personal_photo" => empty($guide->getFirstMediaUrl('personal_pictures')) ? null : $guide->getFirstMediaUrl('personal_pictures'),
            ],
        ], 201);
    }

    public function update_mobile(UpdateProfileRequest $request)
    {
        $user = Auth::user();


        $guide = Guides::where('email', $request->email)->first();
        if ($request->has('languages')) {
            $languagesable = Languagesable::where('languagesable_id', $user->user_id)->delete();
        }
        if ($request->has('languages')) {
            foreach ($request->languages as $value) {
                $data = Languagesable::create(
                    [
                        'languagesable_type' => "App\Models\Guides",
                        'languagesable_id' => $user->user_id,
                        'language_id' => $value
                    ]
                );
            }
        }
        $languages = Languagesable::where('languagesable_id', $user->user_id)->with([
            'language' => function ($query) {
                $query->select('id','lang')
                ;}
        ])->get();

        if ($guide == null) {
            return response()->json(["message" => "unauthenticated"], 401);
        }

        $global_user = $request->user();
        $global_user->update([
            'email' => $request->email ? $request->email : $global_user->email,
            'password' => $request->password ? Hash::make($request->password) : $global_user->password
        ]);

        if ($request->personal_pictures) {
            $guide->clearMediaCollection('personal_pictures');
            $guide->addMultipleMediaFromRequest(['personal_pictures'])->each(function ($fileAdder) {
                $fileAdder->toMediaCollection('personal_pictures');
            });
        }

        $data = $request->except('personal_pictures', 'languages', 'car_photos');

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $guide->update($data);
        return response()->json([
            'status' => true,
            'message' => 'guides successfully created',
            'token' => $global_user->createToken("API TOKEN")->plainTextToken,
            "user" => [
                "id" => $guide->id,
                "notification_id" => $guide->mursheed_user->id,
                "name" => $guide->name,
                "phone" => $guide->phone,
                "email" => $guide->email,
                "is_verified" => $guide->email_verified_at ? true : false,
                "type" =>  "Guides",
                "nationality" => $guide->nationality,
                "country_id" => (int)$guide->country_id,
                "state_id" => (int)$guide->state_id,
                "gender" => $guide->gender ? ($guide->gender == 1 ? "male" : "female") : null,
                "personal_photo" => empty($guide->getFirstMediaUrl('personal_pictures')) ? null : $guide->getFirstMediaUrl('personal_pictures'),
                "bio"=>$guide->bio,
                "status"=> $guide->status,
                "admin_rating"=> $guide->admin_rating,
                "ratings_count"=> $guide->ratings_count,
                "ratings_sum"=> $guide->name,
                "languages"=>$languages,
                "total_rating"=> $guide->name,

            ],
        ], 201);
    }

    public function update(GuideRequest $request, Guides $guide)
    {

        $global_user = MursheedUser::where('email', $guide->email)->first();
        $global_user->update([
            'email' => $request->email ? $request->email : $global_user->email,
            'password' => $request->password ? Hash::make($request->password) : $global_user->password
        ]);

        if ($request->personal_pictures) {
            $guide->clearMediaCollection('personal_pictures');
            $guide->addMultipleMediaFromRequest(['personal_pictures'])->each(function ($fileAdder) {
                $fileAdder->toMediaCollection('personal_pictures');
            });
        }

        return $this->ControllerHandler->update(
            "guide",
            $guide,
            array_merge(
                $request->except('personal_pictures', 'languages'),
                $request->password ? ['password' => Hash::make($request->password)] : []
            )
        );
    }

    public function destroy(Guides $guide)
    {
        return $this->ControllerHandler->destory("guide", $guide);
    }

    public function active(Guides $guide)
    {
        $guide->notify(new SendEmailForApprove());
        return $this->ControllerHandler->update("guide", $guide, ['status' => 1]);
    }

    public function inActive(Guides $guide)
    {

        $this->ControllerHandler->update("guide", $guide, ['status' => 0]);
        return  $guide->notify(new SendEmailForApprove());
    }

    public function change(Guides $guide)
    {


        $this->ControllerHandler->update("guide", $guide, ['status' => $guide->status ^ 1]);
        return $guide->notify(new SendEmailForApprove());
    }

    public function getLatestWithCity(Request $request)
    {
        // return $this->ControllerHandler->getWhere("guides", 'state_id', $state_id, 4);

        $guides = Guides::query()
            ->select('id', 'name', 'state_id', 'total_rating')
            ->addSelect(['state_name' => State::select('state')->whereColumn('states.id', 'guides.state_id')])
            ->when($request->state_id, function ($query) use ($request) {
                $query->where('state_id', $request->state_id);
            })->where('status', 1)
            ->with(['priceServices' => function ($query) {
                $query->limit(1)->latest();
            }])
            ->orderBy('ratings_sum', 'DESC')
            ->limit(4)
            ->get()
            ->each(function ($guide) {
//                $guide->personal_photo = count($guide->getMedia('personal_photo')) == 0 ? url("default_user.jpg") : $guide->getMedia('personal_photo')->first()->getUrl();
                $guide->personal_photo = empty($guide->getFirstMediaUrl('personal_pictures')) ? url("default_user.jpg") : $guide->getFirstMediaUrl('personal_pictures');

                $guide->is_favourite = $guide->favourites()->where('tourist_id', auth()->user()->user_id)->count() > 0;

                $guide->image_background =empty($guide->getFirstMediaUrl('personal_pictures')) ? url("default_user.jpg") : $guide->getFirstMediaUrl('personal_pictures');
                unset($guide->media);
            });

        return response()->json([
            "success" => true,
            "message" => "latest guides in state",
            "guides" => $guides,
        ], 200);
    }

     public function getGuideByCityWithPriceList(Request $request)
     {
         return response([
             "guides" => Guides::with(['priceServices'])->where("state_id", $request->city_id)->get()->append('state_name')->each(function ($driver) {
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


    public function getGuideByCountryWithPriceList()
    {
        $user = Auth::user();
        $tourist = Tourist::where('id', $user->user_id)->first();
        if ($user->user_type == 'App\\Models\\Tourist' && $tourist->dest_country_id != null ) {
//            $guides = Guides::where('country_id', $tourist->dest_country_id)->get();
            $guides = Guides::query()
                ->select('id', 'name', 'state_id', 'total_rating')
                ->addSelect(['state_name' => State::select('state')->whereColumn('states.id', 'guides.state_id')])
               ->where('status', 1)
                ->where('country_id', $tourist->dest_country_id)
                ->with(['priceServices' => function ($query) {
                    $query->limit(1)->latest();
                }])
                ->orderBy('ratings_sum', 'DESC')
                ->get()
                ->each(function ($guide) {
//                $guide->personal_photo = count($guide->getMedia('personal_photo')) == 0 ? url("default_user.jpg") : $guide->getMedia('personal_photo')->first()->getUrl();
                    $guide->personal_photo = empty($guide->getFirstMediaUrl('personal_pictures')) ? url("default_user.jpg") : $guide->getFirstMediaUrl('personal_pictures');

                    $guide->is_favourite = $guide->favourites()->where('tourist_id', auth()->user()->user_id)->count() > 0;

                    $guide->image_background =empty($guide->getFirstMediaUrl('personal_pictures')) ? url("default_user.jpg") : $guide->getFirstMediaUrl('personal_pictures');
                    unset($guide->media);
                });
            return response()->json([
                "success" => true,
                "message" => "latest guides From Country",
                "guides" => $guides,
            ], 200);
        } elseif ($user->user_type == 'App\\Models\\Tourist' && $tourist->dest_country_id == null) {
//            $guides = Guides::where('country_id', $tourist->dest_country_id)->orderBy('total_rating', 'desc')->limit(4)->get();
            $guides = Guides::query()
                ->select('id', 'name', 'state_id', 'total_rating')
                ->addSelect(['state_name' => State::select('state')->whereColumn('states.id', 'guides.state_id')])
                ->where('status', 1)
                ->with(['priceServices' => function ($query) {
                    $query->limit(1)->latest();
                }])
                ->orderBy('ratings_sum', 'DESC')
                ->limit(4)
                ->get()
                ->each(function ($guide) {
//                $guide->personal_photo = count($guide->getMedia('personal_photo')) == 0 ? url("default_user.jpg") : $guide->getMedia('personal_photo')->first()->getUrl();
                    $guide->personal_photo = empty($guide->getFirstMediaUrl('personal_pictures')) ? url("default_user.jpg") : $guide->getFirstMediaUrl('personal_pictures');

                    $guide->is_favourite = $guide->favourites()->where('tourist_id', auth()->user()->user_id)->count() > 0;

                    $guide->image_background =empty($guide->getFirstMediaUrl('personal_pictures')) ? url("default_user.jpg") : $guide->getFirstMediaUrl('personal_pictures');
                    unset($guide->media);
                });
            return response()->json([
                "success" => false,
                "message" => "No valid tourist or destination country provided",
                "guides" => $guides,
            ], 400);
        }
    }

}
