<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class EmployeeTasksOverdue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emptask:overdue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will command will check for tasks that are overdue and send an email notification to the relevant person';

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
        app('App\Http\Controllers\InductionCronController')->execute();
        \Log::info('Cron - EmployeeTasksOverdue, artisan command emptask:overdue ran successfully @ ' . \Carbon\Carbon::now());
    }
}
