<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class LeaveManagementSickLeave extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:leaveAllocationSick';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This Command will allocate sick leave to employees. 1 day every 26 days the the remaning 24 days, this is valide for 3 years';

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
        app('App\Http\Controllers\AllocateLeavedaysFamilyCronController')->sickDays();
        \Log::info('Cron - LeaveManagementSickLeave, artisan command schedule:leaveAllocationSick ran successfully @ ' . \Carbon\Carbon::now());
    }
}
