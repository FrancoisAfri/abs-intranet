<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class leavemanagementFamily extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:leaveAllocationFamily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will allocate Family leave days to employees at the end of the month';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        app('App\Http\Controllers\AllocateLeavedaysFamilyCronController')->execute();
        app('App\Http\Controllers\AllocateLeavedaysFamilyCronController')->sickDays();
        //app('App\Http\Controllers\AllocateLeavedaysFamilyCronController')->maternity();
        //app('App\Http\Controllers\AllocateLeavedaysFamilyCronController')->sickDays();
         
        \Log::info('Cron - leavemanagementFamily, artisan command schedule:leaveAllocationFamily ran successfully @ ' . \Carbon\Carbon::now());
    }

}
