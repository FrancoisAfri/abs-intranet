<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RemindManager extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:remindManager';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this command will remind the manager about un approved leave applications';

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
        app('App\Http\Controllers\Crons\SendLeaveBalanceToUsers')->managerReminder();
        \Log::info('Cron - RemindManager, artisan command schedule:remindManager ran successfully @ ' . \Carbon\Carbon::now());
    }
}
