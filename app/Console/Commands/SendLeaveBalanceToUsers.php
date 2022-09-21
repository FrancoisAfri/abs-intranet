<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendLeaveBalanceToUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:sendLeaveBalances';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this command will send leave balances to all users';

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
        app('App\Http\Controllers\Crons\SendLeaveBalanceToUsers')->execute();
        \Log::info('Cron - SendLeaveBalanceToUsers, artisan command schedule:leaveSendBalance ran successfully @ ' . \Carbon\Carbon::now());
    }
}
