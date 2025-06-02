<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAdminPlan
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Check if the user is an admin and if their plan is inactive
        if ($user->user_type == 'admin' && $user->is_subscribe == 0) {
            return redirect()->route('pricing'); // Redirect to website home
        }
        return $next($request);
    }
}
