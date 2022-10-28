<?php

namespace App\Http\Controllers\Api\Event;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function sendNotification(Request $request){
        $message = new Message;
        $messageSend = $message->find($request->id);
        return $messageSend->content;
    }
}
