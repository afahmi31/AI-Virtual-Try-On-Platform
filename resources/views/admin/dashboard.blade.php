<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - AI Try-On Core App</title>
    <style>
        :root {
            --bg: #060b14;
            --bg-soft: #0b1220;
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
        .brand { font-size: 32px; font-weight: 700; letter-spacing: .3px; display: flex; gap: 12px; align-items: center; }
        .brand-dot {
            width: 36px; height: 36px; border-radius: 10px;
            background: rgba(34, 211, 238, .15);
            display: inline-flex; align-items: center; justify-content: center;
            color: var(--primary); font-weight: 700;
        }
        .topnav { display: flex; align-items: center; gap: 16px; }
        .topnav a, .topnav button {
            color: var(--text);
            text-decoration: none;
            border: 1px solid transparent;
            padding: 10px 14px;
            border-radius: 10px;
            background: rgba(255,255,255,.04);
            font-size: 24px;
        }
        .topnav button { cursor: pointer; }
        .layout { display: grid; grid-template-columns: 280px 1fr; min-height: calc(100vh - 74px); }
        .sidebar {
            border-right: 1px solid rgba(120, 170, 255, 0.2);
            background: linear-gradient(180deg, rgba(11, 18, 32, .9), rgba(8, 14, 24, .95));
            padding: 18px;
        }
        .menu-item {
            display: flex; align-items: center; gap: 12px;
            color: var(--muted);
            text-decoration: none;
            padding: 14px 16px;
            border-radius: 10px;
            margin-bottom: 10px;
            font-size: 28px;
        }
        .menu-item.active {
            color: var(--primary);
            background: rgba(34, 211, 238, 0.12);
            border: 1px solid rgba(34, 211, 238, 0.3);
        }
        .content { padding: 26px; }
        h1 { font-size: 44px; margin: 0 0 20px; }
        .cards {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 16px;
        }
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
        .split {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
            margin-top: 18px;
        }
        .panel {
            background: var(--panel);
            border: 1px solid var(--panel-border);
            border-radius: 14px;
            padding: 18px;
        }
        .panel h2 { font-size: 34px; margin: 0 0 16px; }
        table { width: 100%; border-collapse: collapse; font-size: 22px; }
        th, td { padding: 12px 10px; border-bottom: 1px solid rgba(130, 170, 230, 0.18); text-align: left; }
        th { color: #b9c7da; font-weight: 600; font-size: 20px; background: rgba(255,255,255,0.03); }
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 18px;
            border: 1px solid rgba(45, 212, 191, .45);
            color: var(--success);
            background: rgba(45, 212, 191, .12);
        }
        .chart {
            height: 360px;
            position: relative;
            display: flex;
            align-items: end;
            justify-content: space-between;
            gap: 12px;
            padding: 20px 8px 10px;
            border-left: 1px solid rgba(130, 170, 230, .2);
            border-bottom: 1px solid rgba(130, 170, 230, .2);
        }
        .bar-wrap { flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: end; gap: 10px; }
        .bar {
            width: 58px;
            background: linear-gradient(180deg, rgba(34, 211, 238, .9), rgba(34, 211, 238, .35));
            border: 1px solid rgba(34, 211, 238, 0.5);
            border-radius: 10px 10px 4px 4px;
            box-shadow: 0 0 20px rgba(34, 211, 238, 0.3);
        }
        .bar-label { color: #b8c7dc; font-size: 18px; }
        .bar-value { color: var(--primary); font-size: 18px; }
        @media (max-width: 1400px) {
            .cards { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .split { grid-template-columns: 1fr; }
            .layout { grid-template-columns: 96px 1fr; }
            .menu-item span { display: none; }
            .brand { font-size: 22px; }
        }
    </style>
</head>
<body>
    <header class="topbar">
        <div class="brand"><span class="brand-dot">AI</span>AI Try-On Core App</div>
        <nav class="topnav">
            <a href="{{ route('admin.dashboard') }}">Admin Dashboard</a>
            <a href="{{ route('admin.sellers.index') }}">Sellers</a>
            <form method="POST" action="{{ route('logout') }}" style="display:inline">
                @csrf
                <button type="submit">Logout</button>
            </form>
        </nav>
    </header>

    <div class="layout">
        <aside class="sidebar">
            <a class="menu-item active" href="{{ route('admin.dashboard') }}"><span>Dashboard</span></a>
            <a class="menu-item" href="{{ route('admin.sellers.index') }}"><span>Sellers</span></a>
            <a class="menu-item" href="{{ route('admin.dashboard') }}"><span>Analytics</span></a>
            <a class="menu-item" href="{{ route('admin.dashboard') }}"><span>Settings</span></a>
        </aside>

        <main class="content">
            <h1>Dashboard</h1>

            <section class="cards">
                <article class="card">
                    <div class="card-label">Total Sellers</div>
                    <div class="card-value">{{ number_format($stats['total_sellers']) }}</div>
                </article>
                <article class="card">
                    <div class="card-label">Active Sellers</div>
                    <div class="card-value">{{ number_format($stats['active_sellers']) }}</div>
                </article>
                <article class="card">
                    <div class="card-label">Token Available</div>
                    <div class="card-value">{{ number_format($stats['total_tokens_available']) }}</div>
                </article>
                <article class="card">
                    <div class="card-label">Total Generate</div>
                    <div class="card-value">{{ number_format($stats['total_generate']) }}</div>
                </article>
                <article class="card">
                    <div class="card-label">Failed Generate</div>
                    <div class="card-value" style="color: var(--danger)">{{ number_format($stats['failed_generate']) }}</div>
                </article>
            </section>

            <section class="split">
                <div class="panel">
                    <h2>Top Sellers</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Seller Name</th>
                                <th>Active Since</th>
                                <th>Tokens Used</th>
                                <th>Total Generations</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topSellers as $seller)
                                <tr>
                                    <td>{{ $seller->store_name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($seller->created_at)->format('Y-m-d') }}</td>
                                    <td>{{ number_format($seller->token_used) }}</td>
                                    <td>{{ number_format($seller->success_count) }}</td>
                                    <td><span class="badge">{{ ucfirst($seller->status) }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="5">No data.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="panel">
                    <h2>Generation Trends (Last 30 Days)</h2>
                    @php
                        $maxTrend = max($trendValues ?: [1]);
                    @endphp
                    <div class="chart">
                        @foreach($trendValues as $i => $value)
                            @php
                                $height = $maxTrend > 0 ? max(24, (int) round(($value / $maxTrend) * 280)) : 24;
                            @endphp
                            <div class="bar-wrap">
                                <div class="bar-value">{{ number_format($value) }}</div>
                                <div class="bar" style="height: {{ $height }}px;"></div>
                                <div class="bar-label">{{ $trendLabels[$i] }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        </main>
    </div>
</body>
</html>