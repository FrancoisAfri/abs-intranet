<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendListOfAbesntUsersToManager extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:sendAbsentUsersToManager';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this command will send excel doc to managers';

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
        app('App\Service\ReadErsDetails')->sendAbsentUsersToManagers();
        \Log::info('Cron - sendAbsentUsersToManager, artisan command schedule:sendAbsentUsersToManager ran successfully @ ' . \Carbon\Carbon::now());
    }
}
