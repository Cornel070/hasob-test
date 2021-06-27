<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        \App\Http\Modules\User\Events\UserCreated::class => [
            \App\Http\Modules\User\Listeners\UserCreatedListener::class,
        ],
        \App\Http\Modules\Asset\Events\AssetCreated::class => [
            \App\Http\Modules\Asset\Listeners\AssetCreatedListener::class,
        ],
        \App\Http\Modules\Vendor\Events\VendorCreated::class => [
            \App\Http\Modules\Vendor\Listeners\VendorCreatedListener::class,
        ],
        \App\Http\Modules\AssetAssignment\Events\AssetAssignmentCreated::class => [
            \App\Http\Modules\AssetAssignment\Listeners\AssetAssignmentCreatedListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
