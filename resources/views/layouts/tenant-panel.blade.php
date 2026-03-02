<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name', 'Forgebase'))</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 text-slate-900">
    <main class="mx-auto max-w-4xl px-6 py-10">
        <header class="mb-6 flex flex-col gap-3 rounded-lg border border-slate-200 bg-white px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-xl font-semibold tracking-tight">@yield('heading')</h1>
            @hasSection('subheading')
                    <p class="mt-1 text-sm text-slate-600">@yield('subheading')</p>
            @endif
            </div>
            @php
                $tenantId = app(\App\Support\Tenancy\TenantContext::class)->id();
            @endphp
            @if ($tenantId && auth()->check())
                <div class="flex items-center gap-2 text-sm">
                    <a href="/projects" class="rounded border border-slate-200 px-3 py-1.5 text-slate-700 hover:text-slate-900">Projects</a>
                    <a href="{{ route('workspaces.team', $tenantId) }}" class="rounded border border-slate-200 px-3 py-1.5 text-slate-700 hover:text-slate-900">Team</a>
                </div>
            @endif
        </header>

        @yield('content')
    </main>
</body>
</html>
