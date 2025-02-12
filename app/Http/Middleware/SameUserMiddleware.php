<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SameUserMiddleware {
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response {
        $user = Auth::user();

        // Jika user bukan admin dan mencoba mengakses data user lain
        if (!$user->is_admin) {
            // Ambil ID user yang login
            $userId = $user->slug;

            // Cek apakah request mengakses data yang bukan miliknya
            if ($request->route('user') && $request->route('user') != $userId) {
                return redirect(route('dashboard'))->with('error', 'You are not authorized to access the data.');
            }
        }

        return $next($request);
    }
}