<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Tenant;
use Spatie\Multitenancy\Multitenancy;

class IdentifyTenant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get subdomain part (e.g., foo from foo.app.test)
        $host = $request->getHost(); // foo.app.test
        $subdomain = explode('.', $host)[0]; // foo

        // Avoid resolving on root domain or invalid subdomains
        if ($subdomain === 'app' || empty($subdomain)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid tenant subdomain.'
            ], 403);
        }

        // Resolve tenant by slug (stored in DB)
        $tenant = Tenant::where('slug', $subdomain)->first();

        if (! $tenant) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant not found.'
            ], 404);
        }

        // Set current tenant in the container (via Spatie)
        Multitenancy::setTenant($tenant);

        return $next($request);
    }
}
