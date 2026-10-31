<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response|RedirectResponse)  $next
     * @param  string|null  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        // If no roles specified, allow authenticated user
        if (empty($roles)) {
            return $next($request);
        }

        // Parse roles which can be passed as 'role:admin,guru' or 'role:admin|guru' or multiple args
        $allowedRoles = [];
        foreach ($roles as $roleArg) {
            if (is_string($roleArg)) {
                $subRoles = preg_split('/[,|]/', $roleArg);
                foreach ($subRoles as $r) {
                    $trimmed = trim($r);
                    if ($trimmed !== '') {
                        $allowedRoles[] = $trimmed;
                    }
                }
            }
        }

        if (empty($allowedRoles)) {
            return $next($request);
        }

        $userRole = (string) $user->role;

        // Admin and MPK can bypass or be included
        if (in_array($userRole, $allowedRoles, true) || $userRole === 'admin' || $userRole === 'mpk') {
            return $next($request);
        }

        return redirect()->route('admin.dashboard')
            ->with('error', 'Anda tidak memiliki akses untuk halaman ini.');
    }
}
