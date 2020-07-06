<?php

namespace App\Providers;

use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Modules\User\Events\LoginEvent' => [
            'App\Modules\User\Listeners\LoginListener'
        ],
        'App\Modules\Admin\Events\LoginEvent' => [
            'App\Modules\Admin\Listeners\LoginListener'
        ],
    ];
}
