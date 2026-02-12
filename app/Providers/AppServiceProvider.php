<?php

namespace App\Providers;

use App\Models\CicloFormativo;
use App\Models\User;
use App\Policies\CicloFormativoPolicy;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {


        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });

         Gate::policy(CicloFormativo::class, CicloFormativoPolicy::class);
       Gate::before(function (User $user, string $ability) {
           if ($user->isAdministrador($user)) {
               return true;
           }
           return null;
       });

    }
}
