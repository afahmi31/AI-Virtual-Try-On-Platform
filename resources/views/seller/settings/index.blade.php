<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Try-On Commerce Studio</title>
    <style>
        :root { --bg:#060b14; --panel:rgba(16,25,40,.92); --panel-border:rgba(80,180,255,.25); --text:#e6edf7; --muted:#9db0c8; --primary:#22d3ee; --fs-caption:12px; --fs-label:13px; --fs-control:14px; --fs-body:15px; --fs-nav:16px; --fs-section-title:30px; --fs-page-title:40px; }
        * { box-sizing: border-box; }
        body { margin:0; font-family:"Segoe UI",Arial,sans-serif; color:var(--text); background:radial-gradient(circle at 20% 20%, rgba(34,211,238,.2), transparent 30%), radial-gradient(circle at 80% 70%, rgba(59,130,246,.2), transparent 25%), var(--bg); }
        .topbar { height:72px; padding:0 22px; border-bottom:1px solid rgba(115,170,240,.22); background:linear-gradient(90deg,#0b1630 0%,#091c3f 100%); display:flex; align-items:center; justify-content:space-between; }
        .brand { font-size:18px; font-weight:700; letter-spacing:.2px; display:flex; gap:10px; align-items:center; color:#deebff; }
        .brand-dot { width:30px; height:30px; border-radius:9px; background:rgba(49,217,241,.14); display:inline-flex; align-items:center; justify-content:center; color:var(--primary); font-weight:800; font-size:13px; border:1px solid rgba(49,217,241,.28); }
        .topnav { display:flex; gap:12px; align-items:center; }
        .store-logo-link { width:34px; height:34px; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; text-decoration:none; font-size:12px; font-weight:700; color:#032a33; background:linear-gradient(160deg,#3b82f6,#32ddf2); box-shadow:0 0 16px rgba(50,221,242,.35); }
        .store-icon-svg { width:18px; height:18px; stroke:#032a33; stroke-width:1.9; fill:none; }
        .layout { display:grid; grid-template-columns:220px minmax(0,1fr); min-height:calc(100vh - 72px); }
        .sidebar { border-right:1px solid rgba(115,170,240,.18); background:linear-gradient(180deg, rgba(8,16,30,.95), rgba(6,13,24,.96)); padding:18px 14px; }
        .menu-item { display:flex; align-items:center; color:var(--muted); text-decoration:none; padding:11px 14px; border-radius:10px; margin-bottom:8px; font-size:var(--fs-nav); border:1px solid transparent; transition:color .2s ease, border-color .2s ease, background .2s ease; }
        .menu-item:hover { color:#d8e6fb; border-color:rgba(120,160,220,.2); }
        .menu-item.active { color:#3be0f5; background:rgba(52,219,242,.12); border-color:rgba(52,219,242,.3); }
        .content { padding:26px; }
        h1 { font-size:var(--fs-page-title); margin:0 0 20px; line-height:1.08; }
        .panel h2 { margin:0; font-size:var(--fs-section-title); line-height:1.1; }
        .cards-wrap { max-width: 980px; display: grid; gap: 14px; }
        .panel { background:var(--panel); border:1px solid var(--panel-border); border-radius:14px; padding:18px; }
        .panel-credentials { position:relative; overflow:hidden; }
        .panel-head { display:flex; justify-content:space-between; align-items:center; gap:12px; margin-bottom:4px; }
        .panel-head h2 { margin:0; }
        .status-badge { font-size:var(--fs-label); font-weight:700; padding:6px 10px; border-radius:999px; border:1px solid transparent; }
        .status-ok { color:#78f6dc; background:rgba(45,212,191,.12); border-color:rgba(45,212,191,.45); }
        .status-empty { color:#fecaca; background:rgba(248,113,113,.13); border-color:rgba(248,113,113,.45); }
        label { display:block; margin:12px 0 6px; color:#cad7ea; font-size:var(--fs-control); }
        input[type="text"], input[type="url"] { width:100%; height:44px; border-radius:10px; border:1px solid rgba(54,198,230,.45); background:rgba(6,14,26,.65); color:var(--text); padding:0 12px; font-size:var(--fs-control); }
        .row { margin-bottom:6px; }
        .key-row { display:flex; align-items:flex-end; gap:10px; }
        .key-row .key-input-wrap { flex:1; min-width:0; }
        .key-row .btn-secondary { margin-top:0; margin-left:0; height:44px; white-space:nowrap; }
        select { width:100%; height:44px; border-radius:10px; border:1px solid rgba(54,198,230,.45); background:rgba(6,14,26,.65); color:var(--text); padding:0 12px; font-size:var(--fs-control); }
        .btn { border:none; border-radius:12px; padding:12px 20px; font-size:var(--fs-control); cursor:pointer; margin-top:14px; background:linear-gradient(180deg, #35e5ef, #1ac6d7); color:#052a31; font-weight:700; }
        .btn-secondary { border:1px solid rgba(54,198,230,.45); border-radius:12px; padding:11px 18px; font-size:var(--fs-control); cursor:pointer; margin-top:14px; margin-left:10px; background:rgba(6,14,26,.65); color:var(--text); }
        .hint { color:var(--muted); font-size:var(--fs-label); margin-top:6px; }
        .checks-wrap { display:flex; gap:16px; flex-wrap:wrap; margin-top:10px; }
        .toggle-wrap { display:inline-flex; align-items:center; gap:8px; font-size:var(--fs-label); color:#cad7ea; }
        .toggle-wrap input { display:none; }
        .toggle-switch {
            position: relative;
            width: 46px;
            height: 26px;
            border-radius: 999px;
            background: rgba(157,176,200,.28);
            border: 1px solid rgba(157,176,200,.35);
            cursor: pointer;
            transition: all .2s ease;
        }
        .toggle-switch::after {
            content: '';
            position: absolute;
            top: 2px;
            left: 2px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #fff;
            transition: transform .2s ease;
        }
        .toggle-wrap input:checked + .toggle-switch {
            background: rgba(49,217,241,.42);
            border-color: rgba(49,217,241,.55);
        }
        .toggle-wrap input:checked + .toggle-switch::after {
            transform: translateX(20px);
            background: #ddfbff;
        }
        .flash { margin-bottom:14px; padding:12px 14px; border-radius:10px; font-size:var(--fs-control); border:1px solid rgba(45,212,191,.45); color:#78f6dc; background:rgba(45,212,191,.12); }
        .flash-error { margin-bottom:14px; padding:12px 14px; border-radius:10px; font-size:var(--fs-control); border:1px solid rgba(248,113,113,.45); color:#fecaca; background:rgba(248,113,113,.13); }
        .topnav button { color:var(--text); border:1px solid rgba(130,170,225,.24); padding:8px 14px; border-radius:10px; background:rgba(255,255,255,.04); font-size:var(--fs-control); cursor:pointer; transition:border-color .2s ease, background .2s ease; }
        .topnav button:hover { border-color:rgba(49,217,241,.42); background:rgba(49,217,241,.08); }
        .api-test-overlay { position:absolute; inset:0; display:none; align-items:center; justify-content:center; background:rgba(4,10,20,.42); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); z-index:10; }
        .api-test-overlay.visible { display:flex; }
        .api-test-modal { width:min(520px, calc(100% - 24px)); border:1px solid rgba(54,198,230,.35); border-radius:12px; background:rgba(10,18,34,.72); padding:16px; }
        .api-test-title { font-size:var(--fs-body); font-weight:700; margin-bottom:8px; }
        .api-test-message { color:#cfdced; font-size:var(--fs-control); line-height:1.4; }
        .dots { display:flex; gap:6px; margin:10px 0 2px; }
        .dot { width:8px; height:8px; border-radius:999px; background:#35e5ef; animation: upDown 0.8s ease-in-out infinite; }
        .dot:nth-child(2) { animation-delay: 0.08s; }
        .dot:nth-child(3) { animation-delay: 0.16s; }
        .dot:nth-child(4) { animation-delay: 0.24s; }
        .dot:nth-child(5) { animation-delay: 0.32s; }
        .api-test-close { margin-top:12px; border:1px solid rgba(54,198,230,.45); border-radius:10px; padding:8px 14px; font-size:var(--fs-control); cursor:pointer; background:rgba(6,14,26,.65); color:var(--text); display:none; }
        .api-test-close.visible { display:inline-block; }
        .api-test-modal.success { border-color: rgba(45,212,191,.45); }
        .api-test-modal.failed { border-color: rgba(248,113,113,.45); }
        @keyframes upDown {
            0%, 100% { transform: translateY(0); opacity: .55; }
            50% { transform: translateY(-7px); opacity: 1; }
        }
        @media (max-width:1100px) {
            .layout { grid-template-columns:84px minmax(0,1fr); }
            .menu-item span { display:none; }
            .brand { font-size:15px; }
            .content { padding:18px; }
        }
        @media (max-width:820px) {
            .layout { grid-template-columns:1fr; }
            .sidebar { display:flex; gap:8px; border-right:none; border-bottom:1px solid rgba(115,170,240,.18); }
            .menu-item { margin-bottom:0; }
            .topbar { flex-wrap:wrap; height:auto; padding:12px; gap:10px; }
            h1 { font-size:32px; }
            .panel h2 { font-size:25px; }
        }
    </style>
</head>
<body>
<header class="topbar">
    <div class="brand">Try-On Commerce Studio</div>
    <nav class="topnav">
        @php
            $storeUrl = route('public.seller.page', ['seller_slug' => $seller->slug]);
        @endphp
        <a class="store-logo-link" href="{{ $storeUrl }}" target="_blank" rel="noopener noreferrer" title="Open Store: {{ $storeUrl }}">
            <svg viewBox="0 0 24 24" aria-hidden="true" class="store-icon-svg">
                <path d="M3 9l2-4h14l2 4"></path>
                <path d="M4 9h16v10a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V9z"></path>
                <path d="M9 20v-5h6v5"></path>
            </svg>
        </a>
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
        @if($errors->any())
            <div class="flash-error">{{ $errors->first() }}</div>
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
                        <select name="fashn_model" id="fashnModelSelect" required>
                            <option value="tryon-v1.6" {{ $selectedModel === 'tryon-v1.6' ? 'selected' : '' }}>FASHN Virtual Try-On v1.6</option>
                            <option value="tryon-max" {{ $selectedModel === 'tryon-max' ? 'selected' : '' }}>Try-On Max</option>
                        </select>
                        <div class="hint">Try-On Max direkomendasikan sebagai default model untuk hasil yang lebih stabil.</div>
                    </div>
                </section>

                <section class="panel" id="panelTryonMaxConfig">
                    <h2>Try-On Max Config</h2>
                    @php
                        $selectedGenerationMode = old('fashn_tryon_max_generation_mode', $setting?->fashn_tryon_max_generation_mode ?: 'balanced');
                        $selectedResolution = old('fashn_tryon_max_resolution', $setting?->fashn_tryon_max_resolution ?: '1k');
                        $selectedOutputFormat = old('fashn_tryon_max_output_format', $setting?->fashn_tryon_max_output_format ?: 'png');
                    @endphp
                    <div class="row">
                        <label>Generation Mode</label>
                        <select name="fashn_tryon_max_generation_mode" id="fashnGenerationModeSelect">
                            <option value="balanced" {{ $selectedGenerationMode === 'balanced' ? 'selected' : '' }}>Balanced</option>
                            <option value="quality" {{ $selectedGenerationMode === 'quality' ? 'selected' : '' }}>Quality</option>
                        </select>
                    </div>
                    <div class="row">
                        <label>Resolution</label>
                        <select name="fashn_tryon_max_resolution" id="fashnResolutionSelect">
                            <option value="1k" {{ $selectedResolution === '1k' ? 'selected' : '' }}>1K</option>
                            <option value="2k" {{ $selectedResolution === '2k' ? 'selected' : '' }}>2K</option>
                            <option value="4k" {{ $selectedResolution === '4k' ? 'selected' : '' }}>4K</option>
                        </select>
                        <div class="hint" id="fashnCreditUseHint"></div>
                    </div>
                    <div class="row">
                        <label>Output Format</label>
                        <select name="fashn_tryon_max_output_format">
                            <option value="png" {{ $selectedOutputFormat === 'png' ? 'selected' : '' }}>PNG</option>
                            <option value="jpeg" {{ $selectedOutputFormat === 'jpeg' ? 'selected' : '' }}>JPEG</option>
                        </select>
                    </div>
                </section>

                <section class="panel" id="panelTryonV16Config">
                    <h2>Try-On v1.6 Config</h2>
                    @php
                        $selectedV16Mode = old('fashn_tryon_v16_mode', $setting?->fashn_tryon_v16_mode ?: 'balanced');
                        $selectedV16NumSamples = (int) old('fashn_tryon_v16_num_samples', (int) ($setting?->fashn_tryon_v16_num_samples ?: 1));
                        $selectedV16OutputFormat = old('fashn_tryon_v16_output_format', $setting?->fashn_tryon_v16_output_format ?: 'png');
                    @endphp
                    <div class="row">
                        <label>Mode</label>
                        <select name="fashn_tryon_v16_mode">
                            <option value="performance" {{ $selectedV16Mode === 'performance' ? 'selected' : '' }}>Performance</option>
                            <option value="balanced" {{ $selectedV16Mode === 'balanced' ? 'selected' : '' }}>Balanced</option>
                            <option value="quality" {{ $selectedV16Mode === 'quality' ? 'selected' : '' }}>Quality</option>
                        </select>
                    </div>
                    <div class="row">
                        <label>Number of Samples</label>
                        <select name="fashn_tryon_v16_num_samples" id="fashnV16NumSamplesSelect">
                            <option value="1" {{ $selectedV16NumSamples === 1 ? 'selected' : '' }}>1</option>
                            <option value="2" {{ $selectedV16NumSamples === 2 ? 'selected' : '' }}>2</option>
                            <option value="3" {{ $selectedV16NumSamples === 3 ? 'selected' : '' }}>3</option>
                            <option value="4" {{ $selectedV16NumSamples === 4 ? 'selected' : '' }}>4</option>
                        </select>
                        <div class="hint" id="fashnV16CreditUseHint"></div>
                    </div>
                    <div class="row">
                        <label>Output Format</label>
                        <select name="fashn_tryon_v16_output_format">
                            <option value="png" {{ $selectedV16OutputFormat === 'png' ? 'selected' : '' }}>PNG</option>
                            <option value="jpeg" {{ $selectedV16OutputFormat === 'jpeg' ? 'selected' : '' }}>JPEG</option>
                        </select>
                    </div>
                </section>

                <section class="panel">
                    <h2>Public Generate Limit</h2>
                    @php
                        $publicGeneratePerDay = (int) old('public_generate_per_day', $setting?->public_generate_per_day ?? config('tryon.public_limits.generate_per_day', 3));
                        $publicLimitPerIpEnabled = (int) old('public_limit_per_ip_enabled', isset($setting) && $setting !== null ? (int) ($setting->public_limit_per_ip_enabled ?? 1) : 1);
                        $publicLimitPerDeviceEnabled = (int) old('public_limit_per_device_enabled', isset($setting) && $setting !== null ? (int) ($setting->public_limit_per_device_enabled ?? 1) : 1);
                    @endphp
                    <div class="row">
                        <label>Generate Per Day</label>
                        <input type="text" name="public_generate_per_day" value="{{ $publicGeneratePerDay }}" inputmode="numeric" pattern="[0-9]*">
                    </div>
                    <div class="checks-wrap">
                        <label class="toggle-wrap">
                            <input type="checkbox" name="public_limit_per_ip_enabled" value="1" {{ $publicLimitPerIpEnabled ? 'checked' : '' }}>
                            <span class="toggle-switch"></span>
                            <span>Per IP</span>
                        </label>
                        <label class="toggle-wrap">
                            <input type="checkbox" name="public_limit_per_device_enabled" value="1" {{ $publicLimitPerDeviceEnabled ? 'checked' : '' }}>
                            <span class="toggle-switch"></span>
                            <span>Per Device</span>
                        </label>
                    </div>
                </section>

                <section class="panel">
                    <div class="panel-head">
                        <h2>Dummy</h2>
                        <label class="toggle-wrap">
                            <input type="checkbox" name="fashn_dummy_enabled" value="1" {{ old('fashn_dummy_enabled', (int)($setting?->fashn_dummy_enabled ?? 0)) ? 'checked' : '' }}>
                            <span class="toggle-switch"></span>
                            <span>Enabled</span>
                        </label>
                    </div>
                    <div class="row">
                        <label>Dummy Result URL</label>
                        <input type="url" name="fashn_dummy_result_url" value="{{ old('fashn_dummy_result_url', $setting?->fashn_dummy_result_url ?: '') }}">
                    </div>
                    <div class="row">
                        <label>Dummy Model Image URL</label>
                        <input type="url" name="fashn_dummy_model_image_url" value="{{ old('fashn_dummy_model_image_url', $setting?->fashn_dummy_model_image_url ?: '') }}">
                        <div class="hint">Saat Dummy Mode aktif, URL ini dipakai sebagai model image input dummy.</div>
                    </div>
                </section>
            </div>
            <button type="submit" class="btn">Save Settings</button>
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
    const generationModeSelect = document.getElementById('fashnGenerationModeSelect');
    const resolutionSelect = document.getElementById('fashnResolutionSelect');
    const creditUseHint = document.getElementById('fashnCreditUseHint');
    const modelSelect = document.getElementById('fashnModelSelect');
    const panelTryonMaxConfig = document.getElementById('panelTryonMaxConfig');
    const panelTryonV16Config = document.getElementById('panelTryonV16Config');
    const v16NumSamplesSelect = document.getElementById('fashnV16NumSamplesSelect');
    const v16CreditUseHint = document.getElementById('fashnV16CreditUseHint');
    const csrfToken = document.querySelector('input[name=\"_token\"]')?.value || '';

    function updateCreditUseHint() {
        if (!generationModeSelect || !resolutionSelect || !creditUseHint) {
            return;
        }

        const generationMode = generationModeSelect.value === 'quality' ? 'quality' : 'balanced';
        const resolution = resolutionSelect.value;
        const creditTable = {
            balanced: { '1k': 2, '2k': 3, '4k': 4 },
            quality: { '1k': 3, '2k': 4, '4k': 5 },
        };
        const creditUse = creditTable[generationMode]?.[resolution] ?? 2;
        creditUseHint.textContent = `Credit Use: ${creditUse} credits / image`;
    }

    function updateV16CreditUseHint() {
        if (!v16NumSamplesSelect || !v16CreditUseHint) {
            return;
        }

        const samples = parseInt(v16NumSamplesSelect.value || '1', 10);
        const safeSamples = Number.isNaN(samples) ? 1 : Math.max(1, Math.min(4, samples));
        v16CreditUseHint.textContent = `Credit Use: ${safeSamples} credits / request (1 credit / image)`;
    }

    function syncModelConfigPanels() {
        if (!modelSelect || !panelTryonMaxConfig || !panelTryonV16Config) {
            return;
        }

        const model = modelSelect.value;
        const isV16 = model === 'tryon-v1.6';
        panelTryonMaxConfig.style.display = isV16 ? 'none' : '';
        panelTryonV16Config.style.display = isV16 ? '' : 'none';
    }

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

    if (generationModeSelect) {
        generationModeSelect.addEventListener('change', updateCreditUseHint);
    }

    if (resolutionSelect) {
        resolutionSelect.addEventListener('change', updateCreditUseHint);
    }

    if (v16NumSamplesSelect) {
        v16NumSamplesSelect.addEventListener('change', updateV16CreditUseHint);
    }

    if (modelSelect) {
        modelSelect.addEventListener('change', syncModelConfigPanels);
    }

    updateCreditUseHint();
    updateV16CreditUseHint();
    syncModelConfigPanels();
</script>
</body>
</html>
