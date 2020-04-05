<?php

namespace BinaryBuilds\LaravelMailManager\Managers;

use BinaryBuilds\LaravelMailManager\Models\MailManagerMail;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class NotificationManager
 * @package BinaryBuilds\LaravelMailManager\Managers
 */
class NotificationManager implements MailManagerInterface
{
    /**
     * @param \Illuminate\Notifications\Events\NotificationSending $event
     * @return mixed
     */
    public static function handleMailSendingEvent( $event )
    {
        $mail = MailManagerMail::whereUuid($event->notification->id)->first();

        if ($mail) {
            $mail->update([
                'is_sent' => false,
                'tries' => $mail->tries + 1
            ]);
        }
        else {
            MailManagerMail::create([
                'uuid' => $event->notification->id,
                'recipients' =>  array_values($event->notifiable->routes),
                'subject'  =>$event->notification->toMail($event->notifiable)->subject,
                'mailable_name' => get_class($event->notification),
                'mailable' => serialize(clone $event->notification),
                'is_queued' => in_array(ShouldQueue::class, class_implements($event->notification)),
                'is_notification' => true,
                'notifiable' => serialize(clone $event->notifiable),
                'is_sent' => false,
                'tries' => 1
            ]);
        }
    }

    /**
     * @param \Illuminate\Notifications\Events\NotificationSent $event
     * @return mixed
     */
    public static function handleMailSentEvent( $event )
    {
        MailManagerMail::whereUuid($event->notification->id)->update(['is_sent' => true ]);
    }

    /**
     * @param \BinaryBuilds\LaravelMailManager\Models\MailManagerMail $mail
     */
    public static function resendMail( MailManagerMail $mail )
    {
        (unserialize($mail->notifiable))->notify( unserialize($mail->notification) );
    }
}