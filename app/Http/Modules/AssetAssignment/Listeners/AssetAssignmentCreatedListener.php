<?php

namespace App\Http\Modules\AssetAssignment\Listeners;

use App\Http\Modules\AssetAssignment\Events\AssetAssignmentCreated;
use App\Http\Modules\AssetAssignment\Notifications\AssignmentCreatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AssetAssignmentCreatedListener
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
     * @param  AssetAssignmentCreated  $event
     * @return void
     */
    public function handle(AssetAssignmentCreated $event)
    {
        $event->assignment->notify(new AssignmentCreatedNotification($event->assignment));
    }
}
