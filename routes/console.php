<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::command('airquality:fetch')->everyThirtyMinutes()->withoutOverlapping();
Schedule::command('education:download')->cron('0 0 1 */6 *');
