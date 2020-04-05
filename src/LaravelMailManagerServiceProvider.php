<?php

namespace BinaryBuilds\LaravelMailManager;

use BinaryBuilds\LaravelMailManager\Commands\PruneOldMailables;
use BinaryBuilds\LaravelMailManager\Commands\ResendMail;
use BinaryBuilds\LaravelMailManager\Commands\ResendUnSentMail;
use BinaryBuilds\LaravelMailManager\Listeners\MailSendingListener;
use BinaryBuilds\LaravelMailManager\Listeners\MailSentListener;
use BinaryBuilds\LaravelMailManager\Listeners\NotificationSendingListener;
use BinaryBuilds\LaravelMailManager\Listeners\NotificationSentListener;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Events\MessageSending;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Events\NotificationSending;
use Illuminate\Notifications\Events\NotificationSent;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

/**
 * Class LaravelMailManagerServiceProvider
 * @package BinaryBuilds\LaravelMailManager
 */
class LaravelMailManagerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app['events']->listen(MessageSending::class, MailSendingListener::class);
        $this->app['events']->listen(MessageSent::class, MailSentListener::class);

        $this->app['events']->listen(NotificationSending::class, NotificationSendingListener::class);
        $this->app['events']->listen(NotificationSent::class, NotificationSentListener::class);

        $this->registerMailableData();

        $this->publishes([
            __DIR__ .'/Config/mail_manager.php' => config_path('mail_manager.php')
        ], 'laravel-mail-manager-config');

        $this->loadMigrationsFrom(__DIR__.'/Migrations' );

        $this->commands([
            PruneOldMailables::class,
            ResendUnSentMail::class,
            ResendMail::class
        ]);
    }

    private function registerMailableData()
    {
        $existing_callback = Mailable::$viewDataCallback;

        Mailable::buildViewDataUsing(function ($mailable) use ( $existing_callback ) {

            $data = [];

            if( $existing_callback ) {
                $data = call_user_func( $existing_callback, $mailable );

                if( ! is_array($data) ) $data = [];
            }

            return array_merge($data, [
                '__mail_manager_uuid' => Str::uuid(),
                '__mail_manager_mailable_name' => get_class($mailable),
                '__mail_manager_subject' => $mailable->subject,
                '__mail_manager_recipients' => collect($mailable->to)->pluck('address')->toArray(),
                '__mail_manager_mailable' => serialize(clone $mailable),
                '__mail_manager_queued' => in_array(ShouldQueue::class, class_implements($mailable)),
            ]);
        });
    }
}
