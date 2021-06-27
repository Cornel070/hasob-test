<?php

namespace App\Http\Modules\User\Listeners;

use App\Http\Modules\User\Notifications\UserCreatedNotification;
use App\Http\Modules\User\Events\UserCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UserCreatedListener
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
     * @param  UserCreated  $event
     * @return void
     */
    public function handle(UserCreated $event)
    {
        $event->user->notify(new UserCreatedNotification($event->user));
    }
}