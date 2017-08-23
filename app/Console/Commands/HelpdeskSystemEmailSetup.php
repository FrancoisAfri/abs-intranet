<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class HelpdeskSystemEmailSetup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    //protected $signature = 'command:name';
    // protected $signature = 'helpDeskemails:settings';
     protected $signature = 'HelpdesksystemSetup:emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will command will check for emails set up';
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
         app('App\Http\Controllers\HelpdeskSystemEmailSetupCronController')->execute();
        //\Log::info('Cron - EmployeeTasksOverdue, artisan command emptask:overdue ran successfully @ ' . \Carbon\Carbon::now());
    }
}
