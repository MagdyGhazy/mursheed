<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    protected $beamsClient;

    public function __construct()
    {
        $this->beamsClient = new \Pusher\PushNotifications\PushNotifications(array(
            "instanceId" => "140343aa-f173-4a2d-940a-7724c7c12be1",
            "secretKey" => "7D70A732FDB61A5566B7DAD488F4FAFAD39120B6B172A8A91A9A605F4B3653D5",
        ));
    }

//    public function registerUsersToPusher(Request $request)
//    {
//        $userID = $request->user()->id; // If you use a different auth system, do your checks here
//
//        $beamsToken = $this->beamsClient->generateToken(strval($userID));
//        return response()->json($beamsToken);
//    }


    public function sendNotification(Request $request)
    {
        $publishResponse = $this->beamsClient->publishToUsers(
            array("$request->id"),
            array(
                "fcm" => array(
                    "notification" => array(
                        "title" => $request->title,
                        "body" => $request->body
                    )
                ),
                "apns" => array("aps" => array(
                    "alert" => array(
                        "title" => $request->title,
                        "body" => $request->body
                    )
                )),
                "web" => array(
                    "notification" => array(
                        "title" => $request->title,
                        "body" => $request->body
                    )
                )
            )
        );
        $notification= new Notification;
        $notification->from =Auth::user()->id;
        $id=$notification->from;
        $notification->notification = $request->title;
        $notification->save();
        return response()->json($publishResponse);
    }

    /**
     * @param $ids @array of strings
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function sendNotificationToMobile($ids , $title , $body)
    {
        $publishResponse = $this->beamsClient->publishToUsers(
            $ids,
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
        $notification= new Notification;
        $notification->from =Auth::user()->id;
        $id=$notification->from;
        $notification->notification=$title;
        $notification->save();
        return response()->json($publishResponse);
    }


    public function GetUnreadNotifications()
    {
        $unreadNotifications = Notification::whereNull('read_at')->get();

        return response([
            "data" => $unreadNotifications,
            "message" => "Unread Notifications Fetched Successfully",
            "status" => true,
        ], 200);
    }
    public function GetAllNotifications()
    {
        $unreadNotifications = Notification::all();

        return response([
            "data" => $unreadNotifications,
            "message" => " Notifications Fetched Successfully",
            "status" => true,
        ], 200);
    }

    public function MarkAllNotifications()
    {
        $notifications = Notification::where('read_at', null)->get();

        foreach ($notifications as $notification) {
            $notification->update(['read_at' => now()]);
        }
        return response([
            "message" => " Mark As Read Successfully",
            "status" => true,
        ], 200);
    }



}