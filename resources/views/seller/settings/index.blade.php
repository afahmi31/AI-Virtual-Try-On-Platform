<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - AI Try-On Core App</title>
    <style>
        :root { --bg:#060b14; --panel:rgba(16,25,40,.92); --panel-border:rgba(80,180,255,.25); --text:#e6edf7; --muted:#9db0c8; --primary:#22d3ee; }
        * { box-sizing: border-box; }
        body { margin:0; font-family:"Segoe UI",Arial,sans-serif; color:var(--text); background:radial-gradient(circle at 20% 20%, rgba(34,211,238,.2), transparent 30%), radial-gradient(circle at 80% 70%, rgba(59,130,246,.2), transparent 25%), var(--bg); }
        .topbar { height:74px; padding:0 24px; border-bottom:1px solid rgba(120,170,255,.25); background:linear-gradient(90deg,#0b162f,#0a1b3d); display:flex; align-items:center; justify-content:space-between; }
        .brand { font-size:32px; font-weight:700; display:flex; gap:12px; align-items:center; }
        .brand-dot { width:36px; height:36px; border-radius:10px; background:rgba(34,211,238,.15); display:inline-flex; align-items:center; justify-content:center; color:var(--primary); }
        .layout { display:grid; grid-template-columns:280px 1fr; min-height:calc(100vh - 74px); }
        .sidebar { border-right:1px solid rgba(120,170,255,.2); background:linear-gradient(180deg, rgba(11,18,32,.9), rgba(8,14,24,.95)); padding:18px; }
        .menu-item { display:flex; align-items:center; color:var(--muted); text-decoration:none; padding:14px 16px; border-radius:10px; margin-bottom:10px; font-size:28px; }
        .menu-item.active { color:var(--primary); background:rgba(34,211,238,.12); border:1px solid rgba(34,211,238,.3); }
        .content { padding:26px; }
        h1 { font-size:44px; margin:0 0 20px; }
        .cards-wrap { max-width: 980px; display: grid; gap: 14px; }
        .panel { background:var(--panel); border:1px solid var(--panel-border); border-radius:14px; padding:18px; }
        .panel-credentials { position:relative; overflow:hidden; }
        .panel-head { display:flex; justify-content:space-between; align-items:center; gap:12px; margin-bottom:4px; }
        .panel-head h2 { margin:0; }
        .status-badge { font-size:13px; font-weight:700; padding:6px 10px; border-radius:999px; border:1px solid transparent; }
        .status-ok { color:#78f6dc; background:rgba(45,212,191,.12); border-color:rgba(45,212,191,.45); }
        .status-empty { color:#fecaca; background:rgba(248,113,113,.13); border-color:rgba(248,113,113,.45); }
        label { display:block; margin:12px 0 6px; color:#cad7ea; font-size:18px; }
        input[type="text"], input[type="url"] { width:100%; height:44px; border-radius:10px; border:1px solid rgba(54,198,230,.45); background:rgba(6,14,26,.65); color:var(--text); padding:0 12px; font-size:16px; }
        .row { margin-bottom:6px; }
        .key-row { display:flex; align-items:flex-end; gap:10px; }
        .key-row .key-input-wrap { flex:1; min-width:0; }
        .key-row .btn-secondary { margin-top:0; margin-left:0; height:44px; white-space:nowrap; }
        select { width:100%; height:44px; border-radius:10px; border:1px solid rgba(54,198,230,.45); background:rgba(6,14,26,.65); color:var(--text); padding:0 12px; font-size:16px; }
        .btn { border:none; border-radius:12px; padding:12px 20px; font-size:18px; cursor:pointer; margin-top:14px; background:linear-gradient(180deg, #35e5ef, #1ac6d7); color:#052a31; font-weight:700; }
        .btn-secondary { border:1px solid rgba(54,198,230,.45); border-radius:12px; padding:11px 18px; font-size:16px; cursor:pointer; margin-top:14px; margin-left:10px; background:rgba(6,14,26,.65); color:var(--text); }
        .hint { color:var(--muted); font-size:14px; margin-top:6px; }
        .flash { margin-bottom:14px; padding:12px 14px; border-radius:10px; font-size:16px; border:1px solid rgba(45,212,191,.45); color:#78f6dc; background:rgba(45,212,191,.12); }
        .topnav button { color:var(--text); border:1px solid transparent; padding:10px 14px; border-radius:10px; background:rgba(255,255,255,.04); font-size:18px; cursor:pointer; }
        .api-test-overlay { position:absolute; inset:0; display:none; align-items:center; justify-content:center; background:rgba(4,10,20,.42); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); z-index:10; }
        .api-test-overlay.visible { display:flex; }
        .api-test-modal { width:min(520px, calc(100% - 24px)); border:1px solid rgba(54,198,230,.35); border-radius:12px; background:rgba(10,18,34,.72); padding:16px; }
        .api-test-title { font-size:16px; font-weight:700; margin-bottom:8px; }
        .api-test-message { color:#cfdced; font-size:14px; line-height:1.4; }
        .dots { display:flex; gap:6px; margin:10px 0 2px; }
        .dot { width:8px; height:8px; border-radius:999px; background:#35e5ef; animation: upDown 0.8s ease-in-out infinite; }
        .dot:nth-child(2) { animation-delay: 0.08s; }
        .dot:nth-child(3) { animation-delay: 0.16s; }
        .dot:nth-child(4) { animation-delay: 0.24s; }
        .dot:nth-child(5) { animation-delay: 0.32s; }
        .api-test-close { margin-top:12px; border:1px solid rgba(54,198,230,.45); border-radius:10px; padding:8px 14px; font-size:14px; cursor:pointer; background:rgba(6,14,26,.65); color:var(--text); display:none; }
        .api-test-close.visible { display:inline-block; }
        .api-test-modal.success { border-color: rgba(45,212,191,.45); }
        .api-test-modal.failed { border-color: rgba(248,113,113,.45); }
        @keyframes upDown {
            0%, 100% { transform: translateY(0); opacity: .55; }
            50% { transform: translateY(-7px); opacity: 1; }
        }
    </style>
</head>
<body>
<header class="topbar">
    <div class="brand"><span class="brand-dot">AI</span>AI Try-On Core App - {{ $seller->store_name }}</div>
    <nav class="topnav">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </nav>
</header>
<div class="layout">
    <aside class="sidebar">
        <a class="menu-item" href="{{ route('seller.dashboard') }}"><span>Dashboard</span></a>
        <a class="menu-item" href="{{ route('seller.products.index') }}"><span>Products</span></a>
        <a class="menu-item active" href="{{ route('seller.settings.index') }}"><span>Settings</span></a>
    </aside>
    <main class="content">
        <h1>Settings</h1>
        @if(session('success'))
            <div class="flash">{{ session('success') }}</div>
        @endif
        <form method="POST" action="{{ route('seller.settings.update') }}">
            @csrf
            <div class="cards-wrap">
                <section class="panel panel-credentials">
                    <div class="panel-head">
                        <h2>FASHN AI Credentials</h2>
                        <span id="fashnStatusBadge" class="status-badge {{ $setting?->fashn_api_key ? 'status-ok' : 'status-empty' }}">
                            {{ $setting?->fashn_api_key ? 'Configured' : 'Not Configured' }}
                        </span>
                    </div>
                    <div class="row key-row">
                        <div class="key-input-wrap">
                            <label>FASHN API Key</label>
                            <input type="text" id="fashnApiKeyInput" name="fashn_api_key" placeholder="fa-xxxxxx">
                        </div>
                        <button type="button" id="testApiKeyBtn" class="btn-secondary">Test API Key</button>
                    </div>
                    <div id="apiTestOverlay" class="api-test-overlay" aria-live="polite">
                        <div id="apiTestModal" class="api-test-modal">
                            <div id="apiTestTitle" class="api-test-title">Testing API Key</div>
                            <div id="apiTestDots" class="dots">
                                <span class="dot"></span>
                                <span class="dot"></span>
                                <span class="dot"></span>
                                <span class="dot"></span>
                                <span class="dot"></span>
                            </div>
                            <div id="apiTestMessage" class="api-test-message">Memvalidasi API key ke FASHN endpoint credits...</div>
                            <button type="button" id="apiTestCloseBtn" class="api-test-close">Close</button>
                        </div>
                    </div>
                </section>

                <section class="panel">
                    <h2>FASHN Model</h2>
                    <div class="row">
                        <label>Select Model</label>
                        @php
                            $selectedModel = old('fashn_model', $setting?->fashn_model ?: config('ai.providers.fashn.model', 'tryon-max'));
                        @endphp
                        <select name="fashn_model" required>
                            <option value="tryon-v1.6" {{ $selectedModel === 'tryon-v1.6' ? 'selected' : '' }}>FASHN Virtual Try-On v1.6</option>
                            <option value="tryon-max" {{ $selectedModel === 'tryon-max' ? 'selected' : '' }}>Try-On Max</option>
                        </select>
                    </div>
                </section>

                <section class="panel">
                    <h2>Dummy Result URL (optional)</h2>
                    <div class="row">
                        <label>Dummy Result URL</label>
                        <input type="url" name="fashn_dummy_result_url" value="{{ old('fashn_dummy_result_url', $setting?->fashn_dummy_result_url ?: '') }}">
                    </div>
                    <div class="row">
                        <label><input type="checkbox" name="fashn_dummy_enabled" value="1" {{ old('fashn_dummy_enabled', (int)($setting?->fashn_dummy_enabled ?? 0)) ? 'checked' : '' }}> Enable Dummy Mode</label>
                    </div>
                    <button type="submit" class="btn">Save Settings</button>
                </section>
            </div>
        </form>
    </main>
</div>
<script>
    const testApiKeyBtn = document.getElementById('testApiKeyBtn');
    const apiKeyInput = document.getElementById('fashnApiKeyInput');
    const statusBadge = document.getElementById('fashnStatusBadge');
    const overlay = document.getElementById('apiTestOverlay');
    const overlayModal = document.getElementById('apiTestModal');
    const overlayTitle = document.getElementById('apiTestTitle');
    const overlayDots = document.getElementById('apiTestDots');
    const overlayMessage = document.getElementById('apiTestMessage');
    const overlayCloseBtn = document.getElementById('apiTestCloseBtn');
    const csrfToken = document.querySelector('input[name=\"_token\"]')?.value || '';

    function showOverlayLoading() {
        overlay.classList.add('visible');
        overlayModal.classList.remove('success', 'failed');
        overlayTitle.textContent = 'Testing API Key';
        overlayMessage.textContent = 'Memvalidasi API key ke FASHN endpoint credits...';
        overlayDots.style.display = 'flex';
        overlayCloseBtn.classList.remove('visible');
    }

    function showOverlayResult(ok, message) {
        overlayModal.classList.remove('success', 'failed');
        overlayModal.classList.add(ok ? 'success' : 'failed');
        overlayTitle.textContent = ok ? 'API Key Valid' : 'API Key Tidak Valid';
        overlayMessage.textContent = message || 'Tidak ada response message.';
        overlayDots.style.display = 'none';
        overlayCloseBtn.classList.add('visible');
    }

    function setConfiguredBadge(configured) {
        if (!statusBadge) {
            return;
        }
        statusBadge.classList.remove('status-ok', 'status-empty');
        statusBadge.classList.add(configured ? 'status-ok' : 'status-empty');
        statusBadge.textContent = configured ? 'Configured' : 'Not Configured';
    }

    if (overlayCloseBtn) {
        overlayCloseBtn.addEventListener('click', () => {
            overlay.classList.remove('visible');
        });
    }

    if (testApiKeyBtn) {
        testApiKeyBtn.addEventListener('click', async () => {
            showOverlayLoading();
            testApiKeyBtn.disabled = true;

            try {
                const response = await fetch(@json(route('seller.settings.test-api-key')), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({
                        fashn_api_key: apiKeyInput ? apiKeyInput.value : '',
                    }),
                });

                const payload = await response.json();
                showOverlayResult(!!payload.ok, payload.message || 'Tidak ada response message.');
                if (payload.ok) {
                    setConfiguredBadge(true);
                }
            } catch (error) {
                showOverlayResult(false, 'Gagal test API key: ' + (error.message || 'unknown error'));
            } finally {
                testApiKeyBtn.disabled = false;
            }
        });
    }
</script>
</body>
</html>
