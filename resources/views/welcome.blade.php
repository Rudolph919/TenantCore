<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TenantCore – API-First Multi-Tenant Backend</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <style>
        body { font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif; }
    </style>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-50">
    <div class="mx-auto max-w-4xl px-6 py-16 sm:py-24">
        <header class="flex items-center justify-between">
            <a href="/" class="flex items-center gap-2 text-xl font-semibold tracking-tight text-slate-800 hover:text-slate-600">
                <img src="{{ asset('favicon.svg') }}" alt="" class="h-8 w-8" />
                TenantCore
            </a>
            <nav class="flex items-center gap-4">
                <a href="/api/v1" class="rounded-md bg-violet-600 px-4 py-2 text-sm font-medium text-white hover:bg-violet-500">
                    API v1
                </a>
                <a href="/up" class="text-slate-600 hover:text-slate-900 text-sm">
                    Health
                </a>
            </nav>
        </header>

        <main class="mt-24 text-center">
            <h1 class="text-4xl font-bold tracking-tight text-slate-900 sm:text-5xl">
                API-First Multi-Tenant Backend
            </h1>
            <p class="mx-auto mt-6 max-w-2xl text-lg text-slate-600">
                A clean, maintainable Laravel API demonstrating tenant isolation, stable contracts,
                and SaaS architecture fundamentals. Strict data isolation per tenant with token-based auth.
            </p>

            <div class="mt-10 flex flex-col items-center justify-center gap-4 sm:flex-row">
                <a href="/api/v1" class="rounded-md bg-violet-600 px-6 py-3 text-base font-medium text-white hover:bg-violet-500">
                    View API
                </a>
            </div>

            <div class="mt-20 grid gap-8 sm:grid-cols-3">
                <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm text-left">
                    <div class="text-sm font-medium uppercase tracking-wider text-violet-600">
                        Tenant Isolation
                    </div>
                    <p class="mt-2 text-slate-600">
                        Data and auth scoped per tenant via <code class="rounded bg-slate-100 px-1.5 py-0.5 text-sm">X-Tenant-ID</code> header.
                    </p>
                </div>
                <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm text-left">
                    <div class="text-sm font-medium uppercase tracking-wider text-violet-600">
                        REST API
                    </div>
                    <p class="mt-2 text-slate-600">
                        Versioned routes at <code class="rounded bg-slate-100 px-1.5 py-0.5 text-sm">/api/v1</code>. Login, register, CRUD.
                    </p>
                </div>
                <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm text-left">
                    <div class="text-sm font-medium uppercase tracking-wider text-violet-600">
                        Laravel Sanctum
                    </div>
                    <p class="mt-2 text-slate-600">
                        Bearer token auth. Tenant-scoped users. See README for curl examples.
                    </p>
                </div>
            </div>

            <div class="mt-16 rounded-lg border border-slate-200 bg-slate-900 p-6 text-left">
                <div class="text-sm font-medium text-violet-400 mb-2">Quick start</div>
                <pre class="text-sm text-slate-300 overflow-x-auto"><code># Register (requires X-Tenant-ID: acme)
curl -X POST {{ url('/api/v1/register') }} \
  -H "X-Tenant-ID: acme" \
  -H "Content-Type: application/json" \
  -d '{"name":"Jane","email":"jane@acme.com","password":"password","password_confirmation":"password"}'</code></pre>
            </div>
        </main>

        <footer class="mt-24 border-t border-slate-200 pt-8 text-center text-sm text-slate-500">
            TenantCore – Laravel 12, Sanctum, MySQL
        </footer>
    </div>
</body>
</html>
