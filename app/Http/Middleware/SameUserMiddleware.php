<?php

namespace App\Http\Middleware;

use App\Models\Shipment;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SameUserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Jika user bukan admin dan mencoba mengakses data user lain
        if (!$user->is_admin) {
            // Ambil ID user yang login
            $userId = $user->id;
            // Ambil shipment dari route parameter
            $shipment = $request->route('shipment');
            // $shipment = Shipment::where('slug', $request->route('shipment'))->first();

            // dd($shipment);

            // Cek apakah shipment ada dan apakah user bukan pemiliknya
            if ($shipment->marketing->isNot(Auth::user())) {
                return redirect(route('dashboard'))->with('error', "You are not authorized to access this shipment.");
            }
        }

        return $next($request);
    }
}