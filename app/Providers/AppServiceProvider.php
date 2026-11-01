<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Date;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Pakai tampilan pagination kustom (resources/views/vendor/pagination/default.blade.php),
        // bukan tampilan Tailwind bawaan Laravel yang memunculkan ikon panah raksasa.
        Paginator::defaultView('pagination::default');
        Paginator::defaultSimpleView('pagination::default');

        // Set timezone untuk Carbon
        Carbon::setLocale('id');
        config(['app.locale' => 'id']);
        
        // Force timezone
        date_default_timezone_set(config('app.timezone', 'Asia/Jakarta'));
    }

    public function register()
    {
        //
    }
}