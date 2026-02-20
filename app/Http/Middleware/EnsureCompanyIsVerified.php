<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCompanyIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || !in_array($user->role, [\App\Models\User::ROLE_COMPANY, \App\Models\User::ROLE_ADMIN])) {
            return redirect('/')->with('error', 'Unauthorized access.');
        }

        // Admins can bypass verification
        if ($user->role === \App\Models\User::ROLE_ADMIN) {
            return $next($request);
        }

        // Only verified companies can proceed
        if ($user->company && $user->company->is_verified) {
            return $next($request);
        }

        return redirect()->route('company.profile.edit')->with('error', 'Your company profile needs to be verified before you can access this section.');
    }
}
