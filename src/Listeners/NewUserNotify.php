<?php

namespace Seongbae\Canvas\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;
use Seongbae\Canvas\Notifications\UserCreatedNotification;

class NewUserNotify
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
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $user = $event->getUser();
        $args = $event->getArgs();

        $user->notify(new UserCreatedNotification($user, $args));
    }
}
