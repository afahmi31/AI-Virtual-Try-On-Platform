<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Dashboard - AI Try-On Core App</title>
    <style>
        :root {
            --bg: #060b14;
            --panel: rgba(16, 25, 40, 0.92);
            --panel-border: rgba(80, 180, 255, 0.25);
            --text: #e6edf7;
            --muted: #9db0c8;
            --primary: #22d3ee;
            --success: #2dd4bf;
            --danger: #f87171;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: "Segoe UI", Arial, sans-serif;
            color: var(--text);
            background:
                radial-gradient(circle at 20% 20%, rgba(34, 211, 238, 0.2), transparent 30%),
                radial-gradient(circle at 80% 70%, rgba(59, 130, 246, 0.2), transparent 25%),
                var(--bg);
        }
        .topbar {
            height: 74px;
            padding: 0 24px;
            border-bottom: 1px solid rgba(120, 170, 255, 0.25);
            background: linear-gradient(90deg, #0b162f, #0a1b3d);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .brand { font-size: 32px; font-weight: 700; display: flex; gap: 12px; align-items: center; }
        .brand-dot { width: 36px; height: 36px; border-radius: 10px; background: rgba(34,211,238,.15); display: inline-flex; align-items: center; justify-content: center; color: var(--primary); }
        .topnav { display: flex; gap: 16px; align-items: center; }
        .store-logo-link {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-size: 16px;
            font-weight: 700;
            color: #052a31;
            background: linear-gradient(160deg, #3b82f6, #22d3ee);
            box-shadow: 0 0 16px rgba(34,211,238,.28);
        }
        .topnav button { color: var(--text); border: 1px solid transparent; padding: 10px 14px; border-radius: 10px; background: rgba(255,255,255,.04); font-size: 24px; cursor: pointer; }

        .layout { display: grid; grid-template-columns: 280px 1fr; min-height: calc(100vh - 74px); }
        .sidebar { border-right: 1px solid rgba(120,170,255,.2); background: linear-gradient(180deg, rgba(11,18,32,.9), rgba(8,14,24,.95)); padding: 18px; }
        .menu-item { display: flex; align-items: center; color: var(--muted); text-decoration: none; padding: 14px 16px; border-radius: 10px; margin-bottom: 10px; font-size: 28px; }
        .menu-item.active { color: var(--primary); background: rgba(34,211,238,.12); border: 1px solid rgba(34,211,238,.3); }

        .content { padding: 26px; }
        h1 { font-size: 44px; margin: 0 0 20px; }

        .cards { display: grid; grid-template-columns: repeat(5, minmax(0, 1fr)); gap: 16px; }
        .card {
            background: var(--panel);
            border: 1px solid var(--panel-border);
            border-radius: 14px;
            padding: 16px;
            min-height: 130px;
            box-shadow: inset 0 0 32px rgba(56, 189, 248, 0.08), 0 8px 28px rgba(0, 0, 0, 0.35);
        }
        .card-label { font-size: 22px; color: var(--muted); margin-top: 12px; }
        .card-value { margin-top: 10px; font-size: 48px; font-weight: 700; }

        .split { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-top: 18px; }
        .panel { background: var(--panel); border: 1px solid var(--panel-border); border-radius: 14px; padding: 18px; }
        .panel h2 { font-size: 34px; margin: 0 0 16px; }

        table { width: 100%; border-collapse: collapse; font-size: 22px; }
        th, td { padding: 12px 10px; border-bottom: 1px solid rgba(130, 170, 230, 0.18); text-align: left; }
        th { color: #b9c7da; font-weight: 600; font-size: 20px; background: rgba(255,255,255,0.03); }
        .badge { display: inline-block; padding: 4px 10px; border-radius: 999px; font-size: 18px; border: 1px solid rgba(45, 212, 191, .45); color: var(--success); background: rgba(45, 212, 191, .12); }
        .badge-failed { border-color: rgba(248, 113, 113, .45); color: var(--danger); background: rgba(248, 113, 113, .12); }

        .donut-wrap { display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 360px; }
        .donut {
            --used: 0;
            --remain: 0;
            width: 290px;
            height: 290px;
            border-radius: 50%;
            background:
                radial-gradient(circle at center, rgba(8, 18, 32, 1) 47%, transparent 48%),
                conic-gradient(
                    #35e5ef 0deg calc(var(--used) * 1deg),
                    #3b82f6 calc(var(--used) * 1deg) calc((var(--used) + var(--remain)) * 1deg),
                    rgba(69, 92, 128, .6) calc((var(--used) + var(--remain)) * 1deg) 360deg
                );
            border: 1px solid rgba(80, 180, 255, 0.35);
            box-shadow: inset 0 0 30px rgba(34, 211, 238, .25), 0 0 30px rgba(34, 211, 238, .18);
            position: relative;
        }
        .donut::after {
            content: '';
            position: absolute;
            inset: 34px;
            border-radius: 50%;
            border: 1px solid rgba(80, 180, 255, 0.25);
        }
        .legend { margin-top: 24px; display: flex; gap: 18px; font-size: 18px; color: #b9c7da; }
        .dot { width: 14px; height: 14px; border-radius: 4px; display: inline-block; margin-right: 8px; }

        @media (max-width: 1400px) {
            .layout { grid-template-columns: 96px 1fr; }
            .menu-item span { display: none; }
            .cards { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .split { grid-template-columns: 1fr; }
            .brand { font-size: 22px; }
        }
    </style>
</head>
<body>
<header class="topbar">
    <div class="brand"><span class="brand-dot">AI</span>AI Try-On Core App - {{ $seller->store_name }}</div>
    <nav class="topnav">
        @php
            $storeInitials = strtoupper(substr(trim($seller->store_name), 0, 2));
            $storeUrl = route('public.seller.page', ['seller_slug' => $seller->slug]);
        @endphp
        <a class="store-logo-link" href="{{ $storeUrl }}" target="_blank" rel="noopener noreferrer" title="Open Store: {{ $storeUrl }}">{{ $storeInitials }}</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </nav>
</header>

@php
    $balance = max(1, (int) $stats['token_balance']);
    $usedRatio = min(1, $stats['token_used'] / $balance);
    $remainRatio = min(1, $stats['token_available'] / $balance);
    $usedDeg = (int) round($usedRatio * 360);
    $remainDeg = (int) round($remainRatio * 360);
@endphp

<div class="layout">
    <aside class="sidebar">
        <a class="menu-item active" href="{{ route('seller.dashboard') }}"><span>Dashboard</span></a>
        <a class="menu-item" href="{{ route('seller.products.index') }}"><span>Products</span></a>
        <a class="menu-item" href="{{ route('seller.dashboard') }}"><span>Store Settings</span></a>
        <a class="menu-item" href="{{ route('seller.dashboard') }}"><span>Analytics</span></a>
    </aside>

    <main class="content">
        <h1>Seller Dashboard</h1>

        <section class="cards">
            <article class="card">
                <div class="card-label">Total Products</div>
                <div class="card-value">{{ number_format($stats['total_products']) }}</div>
            </article>
            <article class="card">
                <div class="card-label">Token Available</div>
                <div class="card-value">{{ number_format($stats['token_available']) }}</div>
            </article>
            <article class="card" style="border-color: rgba(53,229,239,.65); box-shadow: inset 0 0 30px rgba(34,211,238,.25), 0 0 34px rgba(34,211,238,.25);">
                <div class="card-label">Success Generate</div>
                <div class="card-value">{{ number_format($stats['success_count']) }}</div>
            </article>
            <article class="card">
                <div class="card-label">Failed Generate</div>
                <div class="card-value">{{ number_format($stats['failed_count']) }}</div>
            </article>
            <article class="card">
                <div class="card-label">Token Used</div>
                <div class="card-value">{{ number_format($stats['token_used']) }}</div>
            </article>
        </section>

        <section class="split">
            <div class="panel" style="border-color: rgba(53,229,239,.6); box-shadow: inset 0 0 26px rgba(34,211,238,.2), 0 0 28px rgba(34,211,238,.16);">
                <h2>Recent Try-On Sessions</h2>
                <table>
                    <thead>
                        <tr><th>ID</th><th>Status</th><th>Quality</th><th>Created</th></tr>
                    </thead>
                    <tbody>
                        @forelse($stats['recent_tryon'] as $session)
                            <tr>
                                <td>{{ $session->id }}</td>
                                <td>
                                    <span class="badge {{ $session->status === 'failed' ? 'badge-failed' : '' }}">
                                        {{ ucfirst($session->status) }}
                                    </span>
                                </td>
                                <td>{{ strtoupper($session->quality_mode) }}</td>
                                <td>{{ $session->created_at->format('Y-m-d H:i:s') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4">Belum ada session try-on.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="panel">
                <h2>Token Usage Trends</h2>
                <div class="donut-wrap">
                    <div class="donut" style="--used: {{ $usedDeg }}; --remain: {{ $remainDeg }};"></div>
                    <div class="legend">
                        <span><i class="dot" style="background:#35e5ef"></i>Used</span>
                        <span><i class="dot" style="background:#3b82f6"></i>Remaining</span>
                        <span><i class="dot" style="background:rgba(69, 92, 128, .6)"></i>Bonus</span>
                    </div>
                </div>
            </div>
        </section>
    </main>
</div>
</body>
</html>
