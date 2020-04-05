<?php

namespace BinaryBuilds\LaravelMailManager\Managers;

use BinaryBuilds\LaravelMailManager\Models\MailManagerMail;

/**
 * Interface MailManagerInterface
 * @package BinaryBuilds\LaravelMailManager\Managers
 */
interface MailManagerInterface
{
    /**
     * @param $event
     * @return mixed
     */
    public static function handleMailSendingEvent( $event );

    /**
     * @param $event
     * @return mixed
     */
    public static function handleMailSentEvent( $event );

    /**
     * @param \BinaryBuilds\LaravelMailManager\Models\MailManagerMail $mail
     */
    public static function resendMail( MailManagerMail $mail );
}