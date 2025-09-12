<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use Override;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    #[Override]
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(Request $request): void
    {
        // Vite::prefetch(concurrency: 3);

        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        Vite::prefetch(concurrency: 3);
        View::composer('*', function ($view): void {

            $request = App::get(Request::class);

            $user = $request->user();
            if ($user && method_exists($user, 'roles')) {
                $permissions = $user->roles()->with('permissions')->get()->pluck('permissions.*')->flatten()->unique()->pluck('name')->toArray();
            } else {
                $permissions = [];  // No roles for customers
            }

            $view->with('auth', [
                'user' => $user,
                'permissions' => $permissions,
            ]);
        });

        $path = $request->path();

        $firstSegmentRaw = explode('/', $path)[0] ?? '';

        $firstSegment = (new Collection(explode('-', $firstSegmentRaw)))
            ->map(fn ($word): string => ucfirst($word))
            ->implode(' ');

        Inertia::share([
            'routeName' => fn () => $firstSegment,
        ]);
    }
}
