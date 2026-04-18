<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (! Auth::check()) {
            $loginRoute = 'login';

            if (! $request->isMethod('get')) {
                $request->session()->put('url.intended', url()->previous());
            }

            return redirect()
                ->guest(route($loginRoute))
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        /** @var User $user */
        $user = $request->user();

        if ($user->role !== $role) {
            abort(403, 'Akses ditolak.');
        }

        return $next($request);
    }
}
