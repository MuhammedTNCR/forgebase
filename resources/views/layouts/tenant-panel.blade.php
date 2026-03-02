<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name', 'Forgebase'))</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700%7Cplex-mono:400,500" rel="stylesheet" />

    <style>
        :root {
            --bg: #0b1413;
            --bg-soft: #101b19;
            --surface: #12201d;
            --ink: #f0f4f2;
            --ink-muted: #b7c7c1;
            --accent: #f6a723;
            --accent-strong: #ffb949;
            --outline: rgba(240, 244, 242, 0.16);
            --danger: #ff7d7d;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: "Space Grotesk", system-ui, sans-serif;
            background: radial-gradient(1200px 600px at 10% 10%, rgba(246, 167, 35, 0.18), transparent 55%),
                radial-gradient(1000px 500px at 90% 10%, rgba(106, 198, 168, 0.18), transparent 60%),
                var(--bg);
            color: var(--ink);
            min-height: 100vh;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .container {
            width: min(1000px, 92vw);
            margin: 0 auto;
            padding: 36px 0 64px;
        }

        .header {
            display: flex;
            flex-direction: column;
            gap: 12px;
            padding: 20px 24px;
            background: var(--surface);
            border: 1px solid var(--outline);
            border-radius: 18px;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.35);
            margin-bottom: 24px;
        }

        .header-row {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .header-title {
            font-size: 1.6rem;
            font-weight: 600;
        }

        .header-subtitle {
            color: var(--ink-muted);
            font-size: 0.95rem;
        }

        .nav {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .panel {
            background: var(--surface);
            border: 1px solid var(--outline);
            border-radius: 18px;
            padding: 20px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.25);
        }

        .section-title {
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--ink-muted);
            margin-bottom: 12px;
        }

        .list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 14px 16px;
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.06);
        }

        .row-meta {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .muted {
            color: var(--ink-muted);
            font-size: 0.92rem;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            padding: 4px 10px;
            font-size: 0.72rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            background: rgba(246, 167, 35, 0.16);
            color: var(--accent-strong);
        }

        .btn {
            border-radius: 999px;
            padding: 10px 16px;
            border: 1px solid transparent;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-primary {
            background: var(--accent);
            color: #1a1306;
            box-shadow: 0 10px 30px rgba(246, 167, 35, 0.25);
        }

        .btn-outline {
            border-color: var(--outline);
            color: var(--ink);
            background: transparent;
        }

        .btn-danger {
            border-color: rgba(255, 125, 125, 0.4);
            color: var(--danger);
            background: rgba(255, 125, 125, 0.12);
        }

        .form {
            display: grid;
            gap: 16px;
        }

        label {
            display: block;
            font-size: 0.92rem;
            color: var(--ink-muted);
            margin-bottom: 6px;
        }

        input,
        select {
            width: 100%;
            padding: 12px 14px;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.14);
            background: rgba(255, 255, 255, 0.04);
            color: var(--ink);
            font-size: 0.98rem;
        }

        input:focus,
        select:focus {
            outline: 2px solid rgba(246, 167, 35, 0.4);
            border-color: var(--accent);
        }

        .error {
            color: var(--danger);
            font-size: 0.85rem;
            margin-top: 6px;
        }

        .status {
            margin-bottom: 16px;
            padding: 12px 14px;
            border-radius: 12px;
            border: 1px solid rgba(106, 198, 168, 0.4);
            background: rgba(106, 198, 168, 0.12);
            color: #a9f0d6;
            font-size: 0.9rem;
        }

        .grid {
            display: grid;
            gap: 18px;
        }

        @media (min-width: 768px) {
            .header {
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
            }

            .header-row {
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <main class="container">
        <header class="header">
            <div class="header-row">
                <div>
                    <h1 class="header-title">@yield('heading')</h1>
                    @hasSection('subheading')
                        <p class="header-subtitle">@yield('subheading')</p>
                    @endif
                </div>
                @php
                    $tenantId = app(\App\Support\Tenancy\TenantContext::class)->id();
                @endphp
                @php
                    $tenantSlug = request()->route('tenant');
                @endphp
                @if ($tenantId && $tenantSlug && auth()->check())
                    <div class="nav">
                        <a class="btn btn-outline" href="{{ route('projects.index', ['tenant' => $tenantSlug]) }}">Projects</a>
                        @if ($tenantId)
                            <a class="btn btn-outline" href="{{ route('workspaces.team', $tenantId) }}">Team</a>
                        @endif
                    </div>
                @endif
            </div>
        </header>

        @yield('content')
    </main>
</body>
</html>
