<?php

namespace App\Http\Controllers\MobileApi\Repository;

use App\Enums\OrderStatus;
use App\Http\Controllers\NotificationController;
use App\Models\MursheedUser;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class OrderRepository
{
    public static function createOrderWithDetails($order,$order_details ,$model,$country_price , $sub_total)
    {
//        return response([
//            'order' => $order,
//            'order_details' => $order_details,
//            'model' => $model,
//            'country_price' => $country_price,
//            'sub_total' => $sub_total,
//            ]);
//        return response([
//            'message' => "success",
//            'status'=>200,
//            "cost"=>$model->order()->create(array_merge($order, ['tourist_id' => auth()->user()->user->id,])) ]);

        DB::beginTransaction();
        try {
            $order = $model->order()->create(array_merge($order, ['tourist_id' => auth()->user()->user->id,'status'=> OrderStatus::notCompleted]));
            $order->orderDetails()->createMany($order_details);
            DB::commit();

            (new NotificationController())
                ->sendNotificationToMobile([
                    strval(auth()->user()->id),
                    strval(User::where('email','admin@admin.com')->first()->id),
                    strval(MursheedUser::where('user_id',$order->user_id)->where('user_type',$order->user_type)->get()->first()->id)
                ],"New order","your order has been submitted");


            return response(['message' => "success",'status'=>200, "cost"=>round($order->cost,2) , 'order_id'=>$order->id,"country_price"=>$country_price , "sub_total"=>round($sub_total )]);
        } catch (\Exception $e) {
            DB::rollback();
            return response(['message' => $e,'status'=>400], 400);
        }

    }
}
