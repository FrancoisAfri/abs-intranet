<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class LeaveManagementResetSickLeave extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:leaveResetSick';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command reset sick leave';

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
        app('App\Http\Controllers\AllocateLeavedaysFamilyCronController')->resetSickLeaves();
        \Log::info('Cron - LeaveManagementResetSickLeave, artisan command schedule:leaveResetSick ran successfully @ ' . \Carbon\Carbon::now());
    }
}
