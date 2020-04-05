<?php

namespace BinaryBuilds\LaravelMailManager\Commands;

use BinaryBuilds\LaravelMailManager\Managers\MailManager;
use Illuminate\Console\Command;

/**
 * Class ResendUnSentMail
 * @package BinaryBuilds\LaravelMailManager\Commands
 */
class ResendUnSentMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail-manager:resend-unsent-mail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Resend all unsent mails';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info(MailManager::resendUnsentMails(). ' Mail(s) retried successfully.');
    }
}
