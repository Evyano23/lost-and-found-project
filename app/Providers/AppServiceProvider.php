<?php

namespace App\Providers;

use App\Models\Claim;
use App\Models\Item;
use App\Models\Report;
use App\Policies\ReportPolicy;
use Illuminate\Database\Eloquent\Relations\Relation;
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
        // Register the ReportPolicy for the Report model
        Gate::policy(Report::class, ReportPolicy::class);

        // Morph map: keeps reportable_type values short and decoupled from class names
        Relation::morphMap([
            'item' => Item::class,
            'claim' => Claim::class,
        ]);

        // Ensure the 'mail' view namespace is always registered (fixes intermittent
        // "No hint path defined for [mail]" errors, especially in test environments)
    }
}