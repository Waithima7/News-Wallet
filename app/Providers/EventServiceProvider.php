<?php

namespace App\Providers;

use App\Events\InitiateB2b;
use App\Events\UpdateAccount;
use App\Listeners\InitiateB2bListener;
use App\Listeners\ProcessSmsListener;
use App\Listeners\UpdateAccountListener;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        \App\Events\ProcessSms::class => [
            ProcessSmsListener::class,
        ],
        UpdateAccount::class=>[
            UpdateAccountListener::class
        ],
        InitiateB2b::class=>[
            InitiateB2bListener::class
        ]
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
