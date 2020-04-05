<?php

return [

    /**
     * Table name to use for storing mailable's.
     * If the defined table name collides with
     * your existing table name's, You can
     * change this value to specify the
     * table name to use. Make sure to
     * run migrations again if you
     *  change this table name.
     */
    'table_name' => 'mail_manager_mails',

    /**
     * List of mailable's and notifications to ignore
     * from being tracked by the mail manager
     */
    'ignore' => [
        // ForgotPasswordMailable::class
    ],
];