<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\LoginHistorialEvent;
use App\Events\LogoutHistorialEvent;
use App\Events\NotificationsEvent;
use App\Events\TrazasEvent;
use App\Listeners\LoginHistorialListener;
use App\Listeners\LogoutHistorialListener;
use App\Listeners\NotificationsListener;
use App\Listeners\TrazasListener;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        LoginHistorialEvent::class => [
            LoginHistorialListener::class
        ],
        LogoutHistorialEvent::class => [
            LogoutHistorialListener::class
        ],
        TrazasEvent::class => [
            TrazasListener::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
