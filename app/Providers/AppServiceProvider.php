<?php

namespace App\Providers;

use App\Models\Project;
use App\Observers\ProjectObserver;
use App\Policies\ProjectPolicy;
use App\Support\Activity\ActivityLogger;
use App\Support\Tenancy\TenantContext;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->scoped(TenantContext::class, fn () => new TenantContext());
        $this->app->singleton(ActivityLogger::class, fn () => new ActivityLogger());
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Project::class, ProjectPolicy::class);
        Project::observe(ProjectObserver::class);
    }
}
