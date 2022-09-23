<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class leaveBalanceReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:leaveBalanceReport';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this command will send leave balance Report  to the head ';

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
        app('App\Http\Controllers\Crons\SendLeaveBalanceToUsers')->sendReport();
        \Log::info('Cron - leaveBalanceReport, artisan command schedule:leaveBalanceReport ran successfully @ ' . \Carbon\Carbon::now());
    }
}
