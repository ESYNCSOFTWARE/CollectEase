<?php

declare(strict_types=1);

namespace App\Domains\User\Middleware;

use Closure;
use Illuminate\Http\Request;

class AccessControl
{
    public function handle(Request $request, Closure $next, ...$permissions)
    {
        $user = $request->user();

        if (! $user || ! $user->hasAnyPermission($permissions)) {
            return to_route('users.unauthorize.index')->with('error', 'Unauthorized access');
        }

        return $next($request);
    }
}
