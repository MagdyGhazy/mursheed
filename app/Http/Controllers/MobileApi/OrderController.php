<?php

namespace App\Http\Controllers\MobileApi;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MobileApi\Repository\OrderRepository;
use App\Http\Controllers\MobileApi\Services\OrderServices;
use App\Http\Controllers\NotificationController;
use App\Models\CountryPrice;
use App\Models\Driver;
use App\Models\Guides;
use App\Models\MursheedUser;
use App\Models\Order;
use App\Models\Tourist;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Geocoder\Geocoder;
use \KMLaravel\GeographicalCalculator\Facade\GeoFacade;
use function Laravel\Prompts\select;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public $geocoder;

    public function __construct()
    {
//
//        $client = new \GuzzleHttp\Client();
//        $this->geocoder = new Geocoder($client);
//
//        $this->geocoder->setApiKey(config('geocoder.key'));


    }

    public function store(Request $request)
    {
        if (!CountryPrice::where('country_id', $request->order['country_id'])->first())
            return response(['message' => "country_id should enter by admin", 'status' => false], 404);

        $model = Driver::find($request->user_id);
        if ($request->user_type == 2) {
            $model = Guides::find($request->user_id);
        }

        if ($model) {
            return (new OrderServices($request->order['country_id'], $model))
                ->calculateRoutingForCities($request->order_details)
                ->storeOrderWithDetailsForUser($request->order, $model);

        }
        return response(['message' => "model not found", 'status' => false], 404);

    }

    public function getPrice(Request $request)
    {
        if (!CountryPrice::where('country_id', $request->order['country_id'])->first())
            return response(['message' => "country_id should enter by admin", 'status' => false], 404);

        $model = Driver::find($request->user_id);
        if ($request->user_type == 2) {
            $model = Guides::find($request->user_id);
        }

        if ($model) {
            return (new OrderServices($request->order['country_id'], $model))
                ->calculateRoutingForCities($request->order_details)
                ->returnResponseForCities();
        }
        return response(['message' => "model not found", 'status' => false], 404);

    }


    public function index()
    {
        $orders = Order::with('orderDetails')->get();
        $orders = collect($orders->toArray())->map(function ($order) {
            $order['rating'] = intval($order['rating']);
            return $order;
        });

        return response(['orders' => $orders]);
    }

    public function show(Order $order,$id)
    {
//        $order_info = $order->where('id',$id)->with('orderDetails')->get();
//        $order_info = collect($order_info->toArray())->map(function ($order) {
//            $order['rating'] = intval($order['rating']);
//            return $order;
//        });
        $order_info = $order->find($id);

        return response([
            'status' =>  'success',
            'vendor' =>  $order_info->vendor[0],
            'userType' =>  $order_info,
            'order' =>  $order_info->orderDetails,
            'country_price' => CountryPrice::where('country_id',$order_info['country_id'])->first(),
            'total_cost' => $order_info['cost'],
            'start_date' => $order_info['start_date'],
            'end_date' => $order_info['end_date']
        ]);
    }

    public function submitOrder(Order $order)
    {
        $order->update(['status' => OrderStatus::pending]);
        return response(['message' => "order created successfully", "status" => true]);
    }

    public function statusOrder(Request $request, Order $order)
    {
//        return strval(MursheedUser::where('user_id',$order->tourist_id)->where('user_type','App\Models\Tourist')->get()->first()->id);
        $order->update(['status' => $request->status]);
        $adminId=strval(User::where('email','admin@admin.com')->first()->id);
//        MursheedUser::where('user_id',$order->user_id)->where('user_type','App\Models\\'. $order->user_type)->get()->first()->id)
        if ($request->status == 1)
            (new NotificationController())->sendNotificationToMobile([strval(MursheedUser::where('user_id',$order->user_id)->where('user_type','App\Models\\'. $order->user_type)->get()->first()->id),$adminId], "New Order", "your order has been " . OrderStatus::fromName($request->status));

        elseif ($request->status == 2)
        {
            (new NotificationController())->sendNotificationToMobile([strval(MursheedUser::where('user_id',$order->tourist_id)->where('user_type','App\Models\Tourist')->get()->first()->id),$adminId], "New Order", "your order has been " . OrderStatus::fromName($request->status));
        }

        elseif($request->status ==5)
            (new NotificationController())->sendNotificationToMobile([strval(MursheedUser::where('user_id',$order->tourist_id)->where('user_type','App\Models\Tourist')->get()->first()->id),$adminId], "New Order", "your order has been " . OrderStatus::fromName($request->status));
        elseif ($request->status == 6)
            (new NotificationController())->sendNotificationToMobile([strval(MursheedUser::where('user_id',$order->user_id)->where('user_type','App\Models\\'. $order->user_type)->get()->first()->id),$adminId], "New Order", "your order has been " . OrderStatus::fromName($request->status));



        return response(['message' => "order status updated ", "status" => true]);
    }

    public function getMyOrders()
    {
        if (class_basename(auth()->user()->user_type) != "Tourist"){
            $orders = Order::with('orderDetails')
                ->when(request('status') == 'open', function ($query) {
                    return $query->where('status', '!=', OrderStatus::canceled)->where('status', '!=', OrderStatus::paid)->where('status', '!=', OrderStatus::expired)->where('status', '!=', OrderStatus::approved);
                })
                ->when(request('status') == 'close', function ($query) {
                    return $query->where('status', OrderStatus::canceled)->where('status', OrderStatus::paid)->where('status', OrderStatus::expired)->where('status', OrderStatus::approved);
                })
                ->where('user_id', auth()->user()->user->id)->where('user_type', auth()->user()->user_type)->orderBy('created_at', 'DESC')->get();
            $orders = collect($orders->toArray())->map(function ($order) {
                $order['rating'] = intval($order['rating']);
                return $order;
            });

        return response(['myOrders' => $orders, "status" => true]);
        } else {
            $orders = Order::with('orderDetails')
                ->when(request('status') == 'open', function ($query) {
                    return $query->where('status', '!=', OrderStatus::canceled)->where('status', '!=', OrderStatus::paid)->where('status', '!=', OrderStatus::expired)->where('status', '!=', OrderStatus::approved);
                })
                ->when(request('status') == 'close', function ($query) {
                    return $query->where('status', OrderStatus::canceled)->where('status', OrderStatus::paid)->where('status', OrderStatus::expired)->where('status', OrderStatus::approved);
                })
                ->where('tourist_id', auth()->user()->user->id)->orderBy('created_at', 'DESC')->get();

            $orders = collect($orders->toArray())->map(function ($order) {
                $order['rating'] = intval($order['rating']);
                return $order;
            });
            return response(['myOrders' => $orders, "status" => true]);
        }
    }
      public function profiteCost()
    {
        $user = Auth::user()->user_id;
        $newdata = [];
        $orderData = Order::where('user_id', $user)
            ->where('status', "1")->get();
        foreach ($orderData as $order) {
            $newdata[] = $order->sub_total;
        }
        return response()->json([
            'sumOrder' => round(array_sum($newdata),2)
        ]);
    }
}
