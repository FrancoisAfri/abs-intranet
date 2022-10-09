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
		  \App\Console\Commands\LeaveManagementSickLeave::class,
		  \App\Console\Commands\LeaveManagementPartenityLeave::class,
		  \App\Console\Commands\LeaveManagementResetFamilyLeave::class,
		  \App\Console\Commands\LeaveManagementResetPaternityLeave::class,
		  \App\Console\Commands\LeaveManagementResetSickLeave::class,
		  \App\Console\Commands\FleetManagementDocsUpload::class,
		  \App\Console\Commands\VehicleDocsUpload::class,
		  \App\Console\Commands\SendLeaveBalanceToUsers::class,
		  \App\Console\Commands\RemindManager::class,
		  \App\Console\Commands\leaveEscalation::class,
		  \App\Console\Commands\leaveBalanceReport::class,
		  \App\Console\Commands\AutomateAbsentLeaveApplication::class,
		  \App\Console\Commands\SendListOfAbesntUsersToManager::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
		
        $filePath = '/storage/logs/tasks.log';
        //$schedule->command('send:statement')->everyMinute()->appendOutputTo($filePath);
        $schedule->command('emptask:overdue')->daily();
        $schedule->command('schedule:leaveAllocationAnnual')->monthlyOn(1, '01:00');
		$schedule->command('schedule:leaveAllocationFamily')->dailyAt('02:00');
		$schedule->command('schedule:leaveAllocationSick')->dailyAt('03:00');
		$schedule->command('schedule:leaveAllocationPartenity')->dailyAt('04:00');
		$schedule->command('schedule:leaveResetSick')->dailyAt('06:00');
		$schedule->command('schedule:leaveResetPartenity')->cron('0 0 1 1 * *');
		$schedule->command('schedule:leaveResetFamily')->cron('0 1 1 1 * *');
		//$schedule->command('fleet:documentsUpload')->everyTenMinutes();
		//$schedule->command('vehicle:variouDocumentsUpload')->everyThirtyMinutes();
		$schedule->command('schedule:SendLeaveBalanceToUsers')->monthlyOn(1, '22:00');
		$schedule->command('schedule:RemindManager')->weekdays()->dailyAt("22:00");
		$schedule->command('schedule:leaveEscalation')->weekdays()->dailyAt("21:00"));
        $schedule->command('schedule:LeaveBalanceReport')->monthlyOn(1, '01:00');
        $schedule->command('schedule:absentLeaveApplication')->weekdays()->dailyAt("23:00");
        $schedule->command('schedule:SendListOfAbesntUsersToManager')->weekdays()->dailyAt("10:00");

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