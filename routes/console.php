<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('host-payouts:process')
    ->everyMinute()
    ->withoutOverlapping()
    ->runInBackground()
    ->description('Stripe host payout splits (respects global toggle, delay hours, per-booking split)');
