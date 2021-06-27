<?php

namespace App\Http\Modules\Vendor\Listeners;

use App\Http\Modules\Vendor\Events\VendorCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class VendorCreatedListener
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
     * @param  VendorCreated  $event
     * @return void
     */
    public function handle(VendorCreated $event)
    {
        return true;
    }
}
