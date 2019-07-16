<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class PromotionsPackagesEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'promemails:sent';
    //protected $signature = 'emptask:overdue';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will send emails to clents if they is a running promotion';

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
        //
         app('App\Http\Controllers\promotionpackageCronController')->execute();
        // \Log::info('Cron - EmployeeTasksOverdue, artisan command emptask:overdue ran successfully @ ' . \Carbon\Carbon::now());
    }
}
