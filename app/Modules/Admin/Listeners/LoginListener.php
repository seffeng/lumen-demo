<?php

namespace App\Modules\Admin\Listeners;

use App\Modules\Admin\Events\LoginEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

class LoginListener implements ShouldQueue
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
     * @param  LoginEvent  $event
     * @return void
     */
    public function handle(LoginEvent $event)
    {
        $user = $event->getUser();
        $user->updateLoginValues();
        $user->save();
    }
}
