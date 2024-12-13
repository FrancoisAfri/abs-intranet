<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TimeAndAttendanceCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:gettimeAttendance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
       app('App\Service\ReadErsDetails')->getTimeAndAttendance();
        \Log::info('Cron - gettimeAttendance, artisan command schedule:gettimeAttendance ran successfully @ ' . \Carbon\Carbon::now());
   
    }
}
