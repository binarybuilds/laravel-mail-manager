<?php

namespace BinaryBuilds\LaravelMailManager\Managers;

use BinaryBuilds\LaravelMailManager\Models\MailManagerMail;
use Illuminate\Support\Facades\Mail;

/**
 * Class MailableManager
 * @package BinaryBuilds\LaravelMailManager\Managers
 */
class MailableManager implements MailManagerInterface
{
    /**
     * @param \Illuminate\Mail\Events\MessageSending $event
     */
    public static function handleMailSendingEvent( $event )
    {
        // If recipients is empty, It means mail is being sent using notification
        // We ignore this mail because it will be handled by the notification listener
        if( isset($event->data['__mail_manager_uuid']) && count($event->data['__mail_manager_recipients'] ) > 0 ) {
            // if the __mail_manager_id is set, It means the mail
            // is a resend. So We can increment the tries on
            // the original mail without duplicating it.
            if( isset($event->data['__mail_manager_id']) ) {
                $mail = MailManagerMail::find($event->data['__mail_manager_id']);
            }

            if( isset($mail) && $mail ) {

                $mail->update([
                    'is_sent' => false,
                    'tries' => $mail->tries + 1
                ]);

            } else {

                MailManagerMail::create([
                    'uuid' => $event->data['__mail_manager_uuid'],
                    'recipients' =>  $event->data['__mail_manager_recipients'] ?? [],
                    'subject'  => $event->data['__mail_manager_subject'] ?? '',
                    'mailable_name' => $event->data['__mail_manager_mailable_name'] ?? '',
                    'mailable' => $event->data['__mail_manager_mailable'] ?? null,
                    'is_queued' => $event->data['__mail_manager_queued'] ?? false,
                    'is_sent' => false,
                    'tries' => 1
                ]);
            }
        }
    }

    /**
     * @param \Illuminate\Mail\Events\MessageSent $event
     */
    public static function handleMailSentEvent( $event )
    {
        // If recipients is empty, It means mailable is part of a notification
        // which will be handled by the notification listener
        if( isset($event->data['__mail_manager_uuid']) && count($event->data['__mail_manager_recipients'] ) > 0 ) {

            if( isset($event->data['__mail_manager_id']) ) {
                MailManagerMail::whereId($event->data['__mail_manager_id'])->update(['is_sent' => true ]);
            } else {
                MailManagerMail::whereUuid($event->data['__mail_manager_uuid'])->update(['is_sent' => true ]);
            }
        }
    }

    /**
     * @param \BinaryBuilds\LaravelMailManager\Models\MailManagerMail $mail
     */
    public static function resendMail( MailManagerMail $mail )
    {
        /**
         * @var \Illuminate\Mail\Mailable $mailable
         */
        $mailable = unserialize( $mail->mailable );

        Mail::send( $mailable->with([
            '__mail_manager_id' => $mail->id,
            '__mail_manager_uuid' => $mail->uuid
        ]));
    }
}