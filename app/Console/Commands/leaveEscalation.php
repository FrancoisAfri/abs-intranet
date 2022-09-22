<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class leaveEscalation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:leaveEscalation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this command will escalate all un resolved leave applications';

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
        app('App\Http\Controllers\Crons\SendLeaveBalanceToUsers')->leaveEscallation();
        \Log::info('Cron - leaveEscalation, artisan command schedule:leaveEscalation ran successfully @ ' . \Carbon\Carbon::now());
    }
}
