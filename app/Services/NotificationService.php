<?php

namespace App\Services;
use App\Models\MursheedUser;

class NotificationService
{
   public function notification($request){
    $beamsClient = new \Pusher\PushNotifications\PushNotifications(array(
        "instanceId" => "140343aa-f173-4a2d-940a-7724c7c12be1",
        "secretKey" => "7D70A732FDB61A5566B7DAD488F4FAFAD39120B6B172A8A91A9A605F4B3653D5",
    ));

    switch($request->type) {
        case "Driver": $type = "App\Models\Driver" ; 
            break;
        case "Guides": $type = "App\Models\Guides";
            break;
        case "Tourist": $type = "App\Models\Tourist";
        break;

        default:
            $type = 'Something went wrong.';
    }


    $user= MursheedUser::where('user_id',$request->id)->where('user_type',$type)->first();

    switch($request->status) {
        case 1: $title = "pending" ;
                $body = "pending"; 
            break;
        case 2: $title = "approved";
                $body = "approved";
            break;
        case 3: $title = "approved";
                $body = "approved";
        break;
        case 4: $title = "approved";
                $body = "approved";
        break;
        case 5: $title = "approved";
                $body = "approved";
        break;
        case 6: $title = "approved";
                $body = "approved";
        break;
        default:
            $title = 'Something went wrong.';
            $body = 'Something went wrong.';
    }

    $publishResponse = $beamsClient->publishToUsers(
        array("$user->id"),
        array(
            "fcm" => array(
                "notification" => array(
                    "title" => $title,
                    "body" => $body
                )
            ),
            "apns" => array("aps" => array(
                "alert" => array(
                    "title" => $title,
                    "body" => $body
                )
            )),
            "web" => array(
                "notification" => array(
                    "title" => $title,
                    "body" => $body
                )
            )
        )
    );
    return response(['k' => $publishResponse]);
   }
}