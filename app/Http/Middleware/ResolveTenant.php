<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResolveTenant
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tenantId = $request->header('X-Tenant-ID');

        if (empty($tenantId)) {
            return response()->json([
                'message' => 'Missing X-Tenant-ID header',
            ], 400);
        }

        $tenant = Tenant::query()
            ->where('id', $tenantId)
            ->orWhere('slug', $tenantId)
            ->first();

        if (! $tenant) {
            return response()->json([
                'message' => 'Tenant not found',
            ], 404);
        }

        $request->attributes->set('tenant', $tenant);
        app()->instance(Tenant::class, $tenant);

        return $next($request);
    }
}
