<?php

namespace BinaryBuilds\LaravelMailManager\Commands;

use BinaryBuilds\LaravelMailManager\Managers\MailManager;
use Illuminate\Console\Command;

/**
 * Class ResendMail
 * @package BinaryBuilds\LaravelMailManager\Commands
 */
class ResendMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail-manager:resend-mail {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Resend mail by id';

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
        MailManager::resendMailById( $this->argument('id') );
        $this->info('mail resent successfully.');
    }
}
