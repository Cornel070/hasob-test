<?php

namespace App\Http\Modules\Asset\Listeners;

use App\Http\Modules\Asset\Events\AssetCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AssetCreatedListener
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
     * @param  AssetCreated  $event
     * @return void
     */
    public function handle(AssetCreated $event)
    {
        return true;
    }
}
