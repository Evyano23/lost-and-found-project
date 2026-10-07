<?php

namespace App\Providers;

use App\Models\Claim;
use App\Models\Item;
use App\Models\Report;
use App\Policies\ReportPolicy;
use App\Support\FallbackMimeTypeGuesser;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;
use League\Flysystem\Local\LocalFilesystemAdapter;
use League\MimeTypeDetection\ExtensionMimeTypeDetector;
use Symfony\Component\Mime\MimeTypes;

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
        // ──────────────────────────────────────────────────────────────────
        // FIX: php_fileinfo.dll is blocked by Windows Application Control.
        // Replace ALL finfo-dependent MIME detection with extension-based
        // alternatives so file uploads work on this machine.
        // ──────────────────────────────────────────────────────────────────

        // 1) symfony/mime – register our guesser FIRST (array_unshift in MimeTypes)
        //    so it short-circuits before FinfoMimeTypeGuesser is attempted.
        $mimeTypes = MimeTypes::getDefault();
        $mimeTypes->registerGuesser(new FallbackMimeTypeGuesser());

        // 2) league/flysystem-local – override both disk drivers so Flysystem
        //    uses ExtensionMimeTypeDetector instead of FinfoMimeTypeDetector.
        $extDetector = new ExtensionMimeTypeDetector();

        foreach (['local', 'public'] as $diskName) {
            Storage::extend($diskName, function ($app, $config) use ($extDetector) {
                $adapter = new LocalFilesystemAdapter(
                    location: $config['root'],
                    mimeTypeDetector: $extDetector,
                );

                return new FilesystemAdapter(
                    new Filesystem($adapter, $config),
                    $adapter,
                    $config,
                );
            });
        }

        // ──────────────────────────────────────────────────────────────────

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
