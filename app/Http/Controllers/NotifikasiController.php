<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Laravel\Firebase\Facades\FirebaseMessaging;
use Kreait\Firebase\Messaging\MulticastMessage;

class NotifikasiController extends Controller
{
    /**
     * Mengirim notifikasi ke sebagian token FCM
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $tokens = $request->input('token');
        $title = $request->input('title');
        $body = $request->input('body');

        $notification = Notification::create($title, $body);

        $message = CloudMessage::new()
            ->withNotification($notification)
            ->withChangedTarget('token', $tokens);

        FirebaseMessaging::send($message);

        return response()->json(['message' => 'Notifikasi berhasil dikirim.']);
    }


    /** 
     * Mengirim notifikasi ke semua perangkat
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeall(Request $request)
    {
        $title = $request->input('title');
        $body = $request->input('body');

        $message = CloudMessage::new()
        ->withNotification(Notification::create($title, $body))
        ->withChangedTarget('topic', 'all');

        FirebaseMessaging::send($message);

        return response()->json(['message' => 'Notifikasi berhasil dikirim.']);
    }
}
