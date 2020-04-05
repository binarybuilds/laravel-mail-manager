<?php

namespace BinaryBuilds\LaravelMailManager\Listeners;

use BinaryBuilds\LaravelMailManager\Managers\MailManager;
use BinaryBuilds\LaravelMailManager\Managers\NotificationManager;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class NotificationSentListener
 * @package BinaryBuilds\LaravelMailManager\Listeners
 */
class NotificationSentListener
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
            MailManager::handleMailSentEvent(new NotificationManager, $event );
        }
    }
}
