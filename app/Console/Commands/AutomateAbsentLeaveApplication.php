<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AutomateAbsentLeaveApplication extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:absentLeaveApplication';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this command will collect all absent employees and apply for leave on their behalf';

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
        app('App\Service\ReadErsDetails')->getErsDetails();
        \Log::info('Cron - absentLeaveApplication, artisan command schedule:absentLeaveApplication ran successfully @ ' . \Carbon\Carbon::now());
    }
}
