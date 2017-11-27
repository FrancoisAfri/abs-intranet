<?php

namespace App\Console;

use App\Console\Commands;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //\App\Console\Commands\Inspire::class,
        \App\Console\Commands\EmployeeTasksOverdue::class,
         \App\Console\Commands\leavemanagementAnnual::class,
		  \App\Console\Commands\leavemanagementFamily::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //$filePath = '/storage/logs/tasks.log';
        //$schedule->command('send:statement')->everyMinute()->appendOutputTo($filePath);
        //$schedule->command('emptask:overdue')->daily();
        $schedule->command('emptask:overdue')->everyMinute();
        $schedule->command('schedule:leaveAllocationAnnual')->monthlyOn(1, '01:00');
		$schedule->command('schedule:leaveAllocationFamily')->dailyAt('01:00');
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
