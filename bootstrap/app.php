<?php

use Illuminate\Foundation\Application;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\DosenWaliMiddleware;
use App\Http\Middleware\MahasiswaMiddleware;
use App\Http\Middleware\KetuaJurusanMiddleware;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Middleware\BagianKeuanganMiddleware;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\BagianPerpustakaanMiddleware;
use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\UpdateKelasCommand;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin' => AdminMiddleware::class,
            'mahasiswa' => MahasiswaMiddleware::class,
            'dosen-wali' => DosenWaliMiddleware::class,
            'ketua-jurusan' => KetuaJurusanMiddleware::class,
            'bagian-keuangan' => BagianKeuanganMiddleware::class,
            'bagian-perpustakaan' => BagianPerpustakaanMiddleware::class,
            'auth' => RedirectIfAuthenticated::class
        ]);
    })
    ->withSchedule(function (Schedule $schedule) {
        $schedule->command(UpdateKelasCommand::class)->cron('0 0 15 7 *');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
