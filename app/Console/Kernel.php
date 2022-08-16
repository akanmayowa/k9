<?php

namespace App\Console;

use Illuminate\Support\Facades\DB;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\SiteSynchronization;

class Kernel extends ConsoleKernel
{

    protected $commands = [
        SiteSynchronization::class,
    ];


    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        // $schedule->call(function () {

        //     $sms_s = new \App\Services\SmsServices();
        //     $escalator = new \App\Services\Escalator($sms_s);
        //     $escalator->Run();
        //     DB::table('test')->insert([
        //         'name' => 1
        //     ]);
        //     // DB::table('test')->delete();
        // })->everyTwoMinutes();

            $schedule->command('cronjob:sites')->everyMinute();
    }


    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}