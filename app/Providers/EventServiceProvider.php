<?php

namespace App\Providers;

use App\Events\ManifestDeleted;
use App\Events\ManifestDispatched;
use App\Events\ManifestAcknowledged;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use App\Listeners\ManifestDeletedListener;
use App\Events\ManifestPartiallyAcknowledged;
use App\Listeners\ManifestAcknowledgedListener;
use App\Listeners\SendManifestDispatchedNotification;
use App\Listeners\ManifestPartiallyAcknowledgedListener;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

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

        ManifestDispatched::class => [
            SendManifestDispatchedNotification::class,
            // ManifestAcknowledgedListener::class,
        ],

        ManifestAcknowledged::class => [
            ManifestAcknowledgedListener::class,
        ],

        ManifestCancelled::class => [
            ManifestCancelledListener::class
        ],

        ManifestPartiallyAcknowledged::class => [
            ManifestPartiallyAcknowledgedListener::class
        ],

        ManifestDeleted::class => [
            ManifestDeletedListener::class
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

        //
    }
}
