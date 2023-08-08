<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */

    protected $policies = [
        \App\Models\User::class => \App\Policies\UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */



    public function boot(Gate $gate)
    {
        $this->registerPolicies();

        // Define the 'upload-files' gate
        $gate->define('upload-files', function ($user) {
            // Authorization logic here
            return $user->isAdmin(); // Example: Only allow users with isAdmin() method returning true to upload files
        });
    }
}
