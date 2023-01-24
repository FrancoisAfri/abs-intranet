<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class EmployeeBirthday extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:employeeBirthday';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will command will send employees happy birthday emails';

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
