<?php

namespace App\Http\Controllers\Api;

use App\Events\SendMessage;
use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Event;

use Illuminate\Support\Facades\Auth;

use Symfony\Component\HttpFoundation\Response as Response;

class MessageController extends Controller
{
    public function listMessages(User $user)
    {
        $userFrom = Auth::user()->id;
        $userTo = $user->id;

        $messages = Message::where(
            function($query) use ($userFrom, $userTo){
                $query->where([
                    'from' => $userFrom,
                    'to' => $userTo
                ]);
            }
        )->orWhere(
            function($query) use ($userFrom, $userTo){
                $query->where([
                    'from' => $userTo,
                    'to' => $userFrom
                ]);
            }
        )->orderBy('created_at', 'ASC')->get();
        return response()->json([
            'messages' => $messages
        ], Response::HTTP_OK);
    }
    public function store(Request $request)
    {
        $message = new Message();

        $message->to      = $request->to;
        $message->from    = Auth::user()->id;
        $message->content = $request->content;
        
        $message->save();

        event:dispatch(new SendMessage($message, $request->to,));
    }
}
