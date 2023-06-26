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
            //清理7天前的平台事件推送
            $lastweek = strtotime('-7 day');
            DB::table('platform_event')
                ->where('create_time','<',$lastweek)
                ->delete();
        })->dailyAt('4:00');

        $schedule->call(function () {
            //清理30天前的微信消息
            $lastMonth = strtotime('-30 day');
            DB::table('mp_message')
                ->where('create_time','<',$lastMonth)
                ->delete();
        })->dailyAt('3:00');

        $schedule->call(function () {
            //每5分钟清理一次过期的临时素材
            $expDate = date('Y-m-d H:i:s', strtotime('-3 day') - 600);
            DB::table('material')
                ->where('is_temp',1)
                ->whereDate('created_at','<',$expDate)
                ->delete();
        })->everyFiveMinutes();
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
