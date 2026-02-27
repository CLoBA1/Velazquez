<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Eliminar Historial de Auditoría de más de 6 meses todos los días a las 3:00 AM
Schedule::command('activitylog:clean')->dailyAt('03:00');
