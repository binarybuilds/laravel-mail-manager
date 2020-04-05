<?php

namespace BinaryBuilds\LaravelMailManager\Listeners;

use BinaryBuilds\LaravelMailManager\Managers\MailManager;
use BinaryBuilds\LaravelMailManager\Managers\NotificationManager;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class NotificationSendingListener
 * @package BinaryBuilds\LaravelMailManager\Listeners
 */
class NotificationSendingListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        if( $event->channel === 'mail' ) {
            MailManager::handleMailSendingEvent(new NotificationManager, $event );
        }
    }
}
