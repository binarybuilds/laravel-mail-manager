<?php

namespace BinaryBuilds\LaravelMailManager\Commands;

use BinaryBuilds\LaravelMailManager\Managers\MailManager;
use Illuminate\Console\Command;

/**
 * Class PruneOldMailables
 * @package BinaryBuilds\LaravelMailManager\Commands
 */
class PruneOldMailables extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail-manager:prune {--hours=72 : The number of hours to retain mails}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete\'s old mailables';

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
        $this->info(MailManager::pruneMails( now()->subHours($this->option('hours')) ).' mails pruned.');
    }
}
