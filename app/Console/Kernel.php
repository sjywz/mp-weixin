<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $lastweek = strtotime('-7 day');
            DB::table('platform_event')->where('create_time','<',$lastweek)->delete();
        })->dailyAt('3:00');
        $schedule->call(function () {
            $lastMonth = strtotime('-30 day');
            DB::table('mp_message')->where('create_time','<',$lastMonth)->delete();
        })->dailyAt('3:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
