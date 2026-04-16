<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Login;
use App\Models\AuditLog;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void { }

    public function boot(): void
    {
        // NEW: Automatically log every successful login
        Event::listen(Login::class, function (Login $event) {
            AuditLog::create([
                'user_id' => $event->user->id,
                'action_description' => 'Logged into the system.'
            ]);
        });
    }
}
