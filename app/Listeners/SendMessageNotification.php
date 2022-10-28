<?php

namespace App\Listeners;

use App\Events\SendMessage;
use App\Http\Controllers\Api\Event\EventController;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Route;

class SendMessageNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\SendMessage  $event
     * @return void
     */
    public function handle(SendMessage $event)
    {
        Route::get('event', [EventController::class, 'sendNotification'], ['event'=>$event]);
    }
}
