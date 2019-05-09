<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class LeaveManagementResetPaternityLeave extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:leaveResetPartenity';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command will reset paternity every year';

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
        app('App\Http\Controllers\AllocateLeavedaysFamilyCronController')->resetPaternityLeaves();
        \Log::info('Cron - LeaveManagementResetPaternityLeave, artisan command schedule:leaveResetPartenity ran successfully @ ' . \Carbon\Carbon::now());
    }
}
