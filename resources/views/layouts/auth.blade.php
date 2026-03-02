<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
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
            width: min(960px, 92vw);
            margin: 0 auto;
            padding: 36px 0 64px;
            min-height: 100vh;
            display: grid;
            align-items: center;
        }

        .shell {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 32px;
            align-items: stretch;
        }

        .panel {
            background: var(--surface);
            border: 1px solid var(--outline);
            border-radius: 18px;
            padding: 28px;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.35);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 700;
            letter-spacing: 0.02em;
            margin-bottom: 18px;
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

        .title {
            font-size: clamp(1.8rem, 3vw, 2.6rem);
            letter-spacing: -0.02em;
        }

        .subtitle {
            color: var(--ink-muted);
            margin-top: 8px;
            line-height: 1.5;
        }

        .input-group {
            margin-top: 18px;
        }

        label {
            display: block;
            font-size: 0.95rem;
            margin-bottom: 8px;
            color: var(--ink-muted);
        }

        input {
            width: 100%;
            padding: 12px 14px;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.14);
            background: rgba(255, 255, 255, 0.04);
            color: var(--ink);
            font-size: 1rem;
        }

        input:focus {
            outline: 2px solid rgba(246, 167, 35, 0.4);
            border-color: var(--accent);
        }

        .meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 16px;
            font-size: 0.9rem;
            color: var(--ink-muted);
            flex-wrap: wrap;
            gap: 10px;
        }

        .btn {
            border-radius: 999px;
            padding: 12px 18px;
            border: 1px solid transparent;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
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

        .helper {
            margin-top: 18px;
            color: var(--ink-muted);
            font-size: 0.95rem;
        }

        .errors {
            margin-top: 12px;
            padding: 12px 14px;
            border-radius: 12px;
            border: 1px solid rgba(255, 125, 125, 0.4);
            background: rgba(255, 125, 125, 0.12);
            color: var(--danger);
            font-size: 0.9rem;
        }

        .status {
            margin-top: 12px;
            padding: 12px 14px;
            border-radius: 12px;
            border: 1px solid rgba(106, 198, 168, 0.4);
            background: rgba(106, 198, 168, 0.12);
            color: #a9f0d6;
            font-size: 0.9rem;
        }

        .aside {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 18px;
        }

        .aside-card {
            padding: 18px;
            border-radius: 16px;
            border: 1px solid var(--outline);
            background: rgba(255, 255, 255, 0.04);
        }

        .mono {
            font-family: "Plex Mono", ui-monospace, monospace;
            color: var(--accent-strong);
            font-weight: 600;
        }

        .fade-up {
            animation: fadeUp 0.9s ease both;
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
    </style>
</head>
<body>
    <div class="container">
        @yield('content')
    </div>
</body>
</html>
