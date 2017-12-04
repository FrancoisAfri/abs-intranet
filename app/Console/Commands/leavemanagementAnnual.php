<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class leavemanagementAnnual extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:leaveAllocationAnnual';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will allocate Annual leave days to employees at the end of the month';

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
        app('App\Http\Controllers\AllocateLeavedaysAnnualCronController')->execute();
        \Log::info('Cron - leavemanagementAnnual, artisan command schedule:leaveAllocationAnnual ran successfully @ ' . \Carbon\Carbon::now());
    }
}
