<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class LeaveManagementResetFamilyLeave extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:leaveResetFamily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command will reset family leave every year';

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
        app('App\Http\Controllers\AllocateLeavedaysFamilyCronController')->execute();
        \Log::info('Cron - LeaveManagementResetFamilyLeave, artisan command schedule:leaveResetFamily ran successfully @ ' . \Carbon\Carbon::now());
    }
}
