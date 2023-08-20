<?php
namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\Response;

class Authenticate
{
    public function handle($request, Closure $next, ...$guards)
    {
        if ($this->authenticate($request, $guards)) {
            return $next($request);
        }

        return response()->json(['error' => 'Không được phép'], Response::HTTP_FORBIDDEN);
    }

    protected function authenticate($request, array $guards)
    {
        if (empty($guards)) {
            $guards[] = null;
        }

        foreach ($guards as $guard) {
            if ($this->isGuardAuthenticated($request, $guard)) {
                return true;
            }
        }

        return false;
    }

    protected function isGuardAuthenticated($request, $guard)
    {
        return auth()->guard($guard)->check();
    }
}
