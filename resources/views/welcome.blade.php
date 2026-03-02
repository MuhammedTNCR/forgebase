<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Forgebase') }}</title>

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
            width: min(1100px, 92vw);
            margin: 0 auto;
            padding: 36px 0 64px;
        }

        .nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 700;
            letter-spacing: 0.02em;
        }

        .brand-mark {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--accent), #ffde9c);
            display: grid;
            place-items: center;
            color: #1b1300;
            font-weight: 700;
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn {
            border-radius: 999px;
            padding: 10px 18px;
            border: 1px solid transparent;
            font-weight: 600;
            font-size: 0.95rem;
        }

        .btn-outline {
            border-color: var(--outline);
            color: var(--ink);
            background: transparent;
        }

        .btn-primary {
            background: var(--accent);
            color: #1a1306;
            box-shadow: 0 10px 30px rgba(246, 167, 35, 0.25);
        }

        .hero {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 36px;
            align-items: center;
            margin-top: 56px;
        }

        .hero h1 {
            font-size: clamp(2.4rem, 4vw, 3.6rem);
            line-height: 1.05;
            letter-spacing: -0.02em;
        }

        .hero p {
            color: var(--ink-muted);
            font-size: 1.05rem;
            margin: 20px 0 28px;
            max-width: 520px;
        }

        .hero-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .panel {
            background: var(--surface);
            border: 1px solid var(--outline);
            border-radius: 18px;
            padding: 22px;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.35);
        }

        .metrics {
            display: grid;
            gap: 16px;
        }

        .metric {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 16px;
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.03);
            font-size: 0.95rem;
        }

        .mono {
            font-family: "Plex Mono", ui-monospace, monospace;
            color: var(--accent-strong);
            font-weight: 600;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 18px;
            margin-top: 48px;
        }

        .card {
            background: var(--bg-soft);
            border-radius: 16px;
            border: 1px solid var(--outline);
            padding: 18px;
        }

        .card h3 {
            font-size: 1.05rem;
            margin-bottom: 8px;
        }

        .card p {
            color: var(--ink-muted);
            font-size: 0.95rem;
        }

        .cta {
            margin-top: 52px;
            padding: 26px;
            background: linear-gradient(120deg, rgba(246, 167, 35, 0.25), rgba(16, 27, 25, 0.6));
            border-radius: 20px;
            border: 1px solid rgba(246, 167, 35, 0.35);
            display: flex;
            flex-direction: column;
            gap: 16px;
            align-items: flex-start;
        }

        .cta h2 {
            font-size: clamp(1.6rem, 3vw, 2.2rem);
        }

        .fade-up {
            animation: fadeUp 0.9s ease both;
        }

        .float {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-8px);
            }
        }

        @media (max-width: 720px) {
            .hero {
                margin-top: 36px;
            }

            .nav {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <nav class="nav fade-up">
            <div class="brand">
                <div class="brand-mark">FB</div>
                <div>{{ config('app.name', 'Forgebase') }}</div>
            </div>
            <div class="nav-actions">
                <a class="btn btn-outline" href="{{ route('login') }}">Log in</a>
                <a class="btn btn-primary" href="{{ route('register') }}">Get started</a>
            </div>
        </nav>

        <section class="hero">
            <div class="fade-up">
                <p class="mono">Multi-tenant SaaS starter</p>
                <h1>Launch a secure, tenant-aware SaaS in days, not weeks.</h1>
                <p>
                    Forgebase ships a production-ready Laravel 12 foundation with subdomain tenancy,
                    workspace switching, audit trails, and team invitations baked in.
                </p>
                <div class="hero-actions">
                    <a class="btn btn-primary" href="{{ route('login') }}">Open your workspace</a>
                    <a class="btn btn-outline" href="{{ route('workspaces.index') }}">View workspaces</a>
                </div>
            </div>
            <div class="panel fade-up float" style="animation-delay: 0.1s;">
                <div class="metrics">
                    <div class="metric">
                        <span>Tenant isolation</span>
                        <span class="mono">Scoped + cached</span>
                    </div>
                    <div class="metric">
                        <span>Audit trail</span>
                        <span class="mono">Project CRUD</span>
                    </div>
                    <div class="metric">
                        <span>Team invites</span>
                        <span class="mono">Signed links</span>
                    </div>
                </div>
            </div>
        </section>

        <section class="grid fade-up" style="animation-delay: 0.2s;">
            <div class="card">
                <h3>Tenant-first architecture</h3>
                <p>Single database, shared tables, strict scoping, and fail-fast guards.</p>
            </div>
            <div class="card">
                <h3>Workspace UX</h3>
                <p>Central workspace selection with clear roles and context-aware navigation.</p>
            </div>
            <div class="card">
                <h3>Operational visibility</h3>
                <p>Activity logging with diffs, actor tracking, and IP metadata.</p>
            </div>
            <div class="card">
                <h3>Invite flow built-in</h3>
                <p>Email invitations with signed accept links and role assignment.</p>
            </div>
        </section>

        <section class="cta fade-up" style="animation-delay: 0.3s;">
            <h2>Ready to build on Forgebase?</h2>
            <p class="text-muted">Spin up your first tenant and start shipping features.</p>
            <div class="hero-actions">
                <a class="btn btn-primary" href="{{ route('register') }}">Create an account</a>
                <a class="btn btn-outline" href="{{ route('login') }}">Sign in</a>
            </div>
        </section>
    </div>
</body>
</html>
