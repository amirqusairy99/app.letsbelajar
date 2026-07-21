<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserActive
{
    /**
     * Routes a disabled user is still allowed to reach.
     *
     * @var array<int, string>
     */
    protected array $except = [
        'account.suspended',
        'account.suspended.receipt',
        'logout',
        'verification.*',
        'password.*',
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->isDisabled() && ! $this->shouldPassThrough($request)) {
            return redirect()->route('account.suspended');
        }

        return $next($request);
    }

    protected function shouldPassThrough(Request $request): bool
    {
        foreach ($this->except as $pattern) {
            if ($pattern === '*') {
                return true;
            }

            if (str_contains($pattern, '*')) {
                $regex = '#^' . str_replace('\*', '.*', preg_quote($pattern, '#')) . '$#';
                if (preg_match($regex, $request->route()?->getName() ?? '')) {
                    return true;
                }
                continue;
            }

            if ($request->route()?->getName() === $pattern) {
                return true;
            }
        }

        return false;
    }
}
