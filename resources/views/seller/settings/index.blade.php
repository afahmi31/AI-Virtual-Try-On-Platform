<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Try-On Commerce Studio</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;500;600;700&family=Inter:wght@500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/seller-theme.css') }}">
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
                    <h2>Store URL & SEO</h2>
                    @php
                        $sellerSlug = old('seller_slug', $seller->slug);
                        $sellerSeoTitle = old('seo_title', $seller->seo_title ?? '');
                        $sellerSeoDescription = old('seo_description', $seller->seo_description ?? '');
                        $sellerSeoLogoUrl = old('seo_logo_url', $seller->seo_logo_url ?? '');
                        $storeUrlPreview = url('/' . $sellerSlug);
                    @endphp
                    <div class="row">
                        <label>Seller URL (Slug)</label>
                        <input type="text" name="seller_slug" value="{{ $sellerSlug }}" placeholder="contoh: ceriakid" required>
                        <div class="hint">Hanya huruf kecil, angka, dan tanda minus. URL store: {{ $storeUrlPreview }}</div>
                    </div>
                    <div class="row">
                        <label>SEO Title</label>
                        <input type="text" name="seo_title" value="{{ $sellerSeoTitle }}" placeholder="Judul SEO halaman seller">
                    </div>
                    <div class="row">
                        <label>SEO Description</label>
                        <input type="text" name="seo_description" value="{{ $sellerSeoDescription }}" placeholder="Deskripsi SEO halaman seller">
                    </div>
                    <div class="row">
                        <label>SEO Logo URL (OG Image)</label>
                        <input type="url" name="seo_logo_url" value="{{ $sellerSeoLogoUrl }}" placeholder="https://...">
                        <div class="hint">Dipakai untuk meta image (Open Graph/Twitter) di halaman store publik.</div>
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


