<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - AI Try-On Core App</title>
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
            --fs-caption: 12px;
            --fs-label: 13px;
            --fs-control: 14px;
            --fs-body: 15px;
            --fs-body-strong: 16px;
            --fs-nav: 16px;
            --fs-section-title: 32px;
            --fs-page-title: 40px;
            --fs-metric: 42px;
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
            height: 72px;
            padding: 0 22px;
            border-bottom: 1px solid rgba(115, 170, 240, 0.22);
            background: linear-gradient(90deg, #0b1630 0%, #091c3f 100%);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .brand {
            font-size: 18px;
            font-weight: 700;
            letter-spacing: 0.2px;
            display: flex;
            gap: 10px;
            align-items: center;
            color: #deebff;
        }
        .brand-dot {
            width: 30px;
            height: 30px;
            border-radius: 9px;
            background: rgba(49, 217, 241, 0.14);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-weight: 800;
            font-size: 13px;
            border: 1px solid rgba(49, 217, 241, 0.28);
        }
        .topnav { display: flex; gap: 12px; align-items: center; }
        .store-logo-link {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-size: 12px;
            font-weight: 700;
            color: #032a33;
            background: linear-gradient(160deg, #3b82f6, #32ddf2);
            box-shadow: 0 0 16px rgba(50, 221, 242, 0.35);
        }
        .topnav button {
            color: var(--text);
            border: 1px solid rgba(130, 170, 225, 0.24);
            padding: 8px 14px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.04);
            font-size: 14px;
            cursor: pointer;
            transition: border-color 0.2s ease, background 0.2s ease;
        }
        .topnav button:hover {
            border-color: rgba(49, 217, 241, 0.42);
            background: rgba(49, 217, 241, 0.08);
        }

        .layout { display: grid; grid-template-columns: 220px minmax(0, 1fr); min-height: calc(100vh - 72px); }
        .sidebar { border-right: 1px solid rgba(115, 170, 240, 0.18); background: linear-gradient(180deg, rgba(8, 16, 30, 0.95), rgba(6, 13, 24, 0.96)); padding: 18px 14px; }
        .menu-item {
            display: flex;
            align-items: center;
            color: var(--muted);
            text-decoration: none;
            padding: 11px 14px;
            border-radius: 10px;
            margin-bottom: 8px;
            font-size: var(--fs-nav);
            border: 1px solid transparent;
            transition: color 0.2s ease, border-color 0.2s ease, background 0.2s ease;
        }
        .menu-item:hover { color: #d8e6fb; border-color: rgba(120, 160, 220, 0.2); }
        .menu-item.active { color: #3be0f5; background: rgba(52, 219, 242, 0.12); border-color: rgba(52, 219, 242, 0.3); }

        .content { padding: 26px; }
        h1 { font-size: var(--fs-page-title); margin: 0 0 20px; line-height: 1.08; }

        .cards { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 16px; }
        .card {
            background: var(--panel);
            border: 1px solid var(--panel-border);
            border-radius: 14px;
            padding: 16px;
            min-height: 130px;
            box-shadow: inset 0 0 32px rgba(56, 189, 248, 0.08), 0 8px 28px rgba(0, 0, 0, 0.35);
        }
        .card-label { font-size: 18px; color: var(--muted); margin-top: 12px; }
        .card-value { margin-top: 10px; font-size: var(--fs-metric); font-weight: 700; line-height: 1.05; }
        .credit-breakdown { margin-top: 10px; border-top: 1px solid rgba(130, 170, 230, 0.2); padding-top: 10px; }
        .credit-item { display: flex; justify-content: space-between; align-items: center; font-size: var(--fs-body); color: #c6d3e6; margin: 4px 0; }
        .credit-item strong { color: #e6edf7; font-size: var(--fs-body-strong); }

        .split { display: grid; grid-template-columns: 1fr; gap: 18px; margin-top: 18px; }
        .panel { background: var(--panel); border: 1px solid var(--panel-border); border-radius: 14px; padding: 18px; }
        .panel h2 { font-size: var(--fs-section-title); margin: 0 0 16px; line-height: 1.1; }

        table { width: 100%; border-collapse: collapse; font-size: var(--fs-body); }
        th, td { padding: 12px 10px; border-bottom: 1px solid rgba(130, 170, 230, 0.18); text-align: left; }
        th { color: #b9c7da; font-weight: 600; font-size: var(--fs-label); background: rgba(255,255,255,0.03); }
        .badge { display: inline-block; padding: 4px 10px; border-radius: 999px; font-size: var(--fs-caption); border: 1px solid rgba(45, 212, 191, .45); color: var(--success); background: rgba(45, 212, 191, .12); }
        .badge-failed { border-color: rgba(248, 113, 113, .45); color: var(--danger); background: rgba(248, 113, 113, .12); }
        .badge-processing { border-color: rgba(56, 189, 248, .45); color: #7dd3fc; background: rgba(56, 189, 248, .12); }
        .preview-thumb { width: 42px; height: 42px; border-radius: 8px; object-fit: cover; border: 1px solid rgba(130, 170, 230, 0.35); }
        .details-btn { border:1px solid rgba(80,180,255,.45); background:rgba(6,14,26,.65); color:#dbeafe; border-radius:10px; padding:6px 10px; font-size:var(--fs-control); cursor:pointer; }
        .req-id { font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace; font-size: var(--fs-control); color:#dbeafe; }
        .req-model { font-size: var(--fs-label); color:#d4e4f7; background: rgba(255,255,255,.05); border:1px solid rgba(130,170,230,.25); border-radius:8px; padding: 4px 8px; display:inline-block; }

        .modal-backdrop { position: fixed; inset: 0; display: none; align-items: center; justify-content: center; background: rgba(4,10,20,.62); backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px); z-index: 50; padding: 20px; }
        .modal-backdrop.open { display: flex; }
        .modal-card { width: min(980px, 100%); max-height: 90vh; overflow: auto; background: #0e1728; border: 1px solid rgba(130,170,230,.35); border-radius: 14px; padding: 18px; box-shadow: 0 18px 60px rgba(0,0,0,.45); }
        .modal-header { display:flex; justify-content: space-between; align-items: center; gap: 12px; margin-bottom: 12px; }
        .modal-title { font-size: 28px; margin: 0; }
        .modal-close { border:1px solid rgba(130,170,230,.35); background:rgba(6,14,26,.65); color:#e6edf7; border-radius:10px; width:38px; height:38px; font-size:16px; cursor:pointer; }
        .modal-grid { display:grid; grid-template-columns: repeat(4, minmax(0,1fr)); gap: 12px; margin-bottom: 12px; }
        .modal-kv { background: rgba(255,255,255,.03); border:1px solid rgba(130,170,230,.2); border-radius:10px; padding:10px; }
        .modal-kv .k { font-size: var(--fs-caption); color:#9db0c8; text-transform: uppercase; letter-spacing: .03em; margin-bottom: 5px; }
        .modal-kv .v { font-size: var(--fs-control); color:#e6edf7; word-break: break-all; }
        .modal-tabs { display:flex; gap: 8px; margin: 6px 0 12px; }
        .tab-btn { border:1px solid rgba(130,170,230,.35); background:rgba(6,14,26,.65); color:#c8d7eb; border-radius:10px; padding:8px 12px; cursor:pointer; font-size:var(--fs-label); }
        .tab-btn.active { border-color: rgba(34,211,238,.6); color: #22d3ee; background: rgba(34,211,238,.12); }
        .tab-content { display:none; }
        .tab-content.active { display:block; }
        .image-grid { display:grid; grid-template-columns: repeat(3, minmax(0,1fr)); gap: 12px; margin-bottom: 12px; }
        .image-block { background: rgba(255,255,255,.03); border:1px solid rgba(130,170,230,.2); border-radius:10px; padding: 8px; }
        .image-block h4 { margin: 0 0 8px; font-size: var(--fs-label); color:#b9c7da; }
        .image-block img { width:100%; height:210px; object-fit: cover; border-radius: 8px; border: 1px solid rgba(130,170,230,.3); }
        .image-empty { height:210px; border-radius: 8px; border: 1px dashed rgba(130,170,230,.35); display:flex; align-items:center; justify-content:center; color:#8fa4bf; font-size:var(--fs-label); }
        .json-box { background:#0a0f1a; border:1px solid rgba(130,170,230,.28); border-radius:10px; padding:12px; overflow:auto; max-height: 280px; }
        .json-box pre { margin:0; color:#d7e7fb; font-size: var(--fs-caption); line-height: 1.5; font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace; white-space: pre-wrap; word-break: break-word; }


        @media (max-width: 1100px) {
            .layout { grid-template-columns: 84px minmax(0, 1fr); }
            .menu-item span { display: none; }
            .cards { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .brand { font-size: 15px; }
            .modal-grid { grid-template-columns: repeat(2, minmax(0,1fr)); }
            .image-grid { grid-template-columns: 1fr; }
            .content { padding: 18px; }
        }

        @media (max-width: 820px) {
            .layout { grid-template-columns: 1fr; }
            .sidebar { display: flex; gap: 8px; border-right: none; border-bottom: 1px solid rgba(115, 170, 240, 0.18); }
            .menu-item { margin-bottom: 0; }
            .topbar { flex-wrap: wrap; height: auto; padding: 12px; gap: 10px; }
            h1 { font-size: 32px; }
            .panel h2 { font-size: 26px; }
            .card-value { font-size: 36px; }
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
    $resolvePublicPath = static function (?string $path): ?string {
        if (! is_string($path) || trim($path) === '') {
            return null;
        }
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }
        return \Illuminate\Support\Facades\Storage::disk('public')->url($path);
    };
@endphp

<div class="layout">
    <aside class="sidebar">
        <a class="menu-item active" href="{{ route('seller.dashboard') }}"><span>Dashboard</span></a>
        <a class="menu-item" href="{{ route('seller.products.index') }}"><span>Products</span></a>
        <a class="menu-item" href="{{ route('seller.settings.index') }}"><span>Settings</span></a>
    </aside>

    <main class="content">
        <h1>Dashboard</h1>

        <section class="cards">
            <article class="card">
                <div class="card-label">Total Products</div>
                <div class="card-value">{{ number_format($stats['total_products']) }}</div>
            </article>
            <article class="card">
                <div class="card-label">FASHN Credits</div>
                <div class="card-value">{{ number_format($stats['fashn_credits']['total'] ?? 0) }}</div>
                <div class="credit-breakdown">
                    <div class="credit-item">
                        <span>Subscription</span>
                        <strong>{{ number_format($stats['fashn_credits']['subscription'] ?? 0) }}</strong>
                    </div>
                    <div class="credit-item">
                        <span>On Demand</span>
                        <strong>{{ number_format($stats['fashn_credits']['on_demand'] ?? 0) }}</strong>
                    </div>
                </div>
            </article>
            <article class="card">
                <div class="card-label">Model</div>
                <div class="card-value-text">{{ $stats['fashn_model_label'] }}</div>
                <div class="credit-breakdown">
                    @if($stats['fashn_model'] === 'tryon-v1.6')
                        <div class="credit-item">
                            <span>Mode</span>
                            <strong>{{ ucfirst($stats['fashn_model_config']['mode'] ?? 'balanced') }}</strong>
                        </div>
                        <div class="credit-item">
                            <span>Samples</span>
                            <strong>{{ (int) ($stats['fashn_model_config']['samples'] ?? 1) }}</strong>
                        </div>
                        <div class="credit-item">
                            <span>Format</span>
                            <strong>{{ strtoupper((string) ($stats['fashn_model_config']['format'] ?? 'png')) }}</strong>
                        </div>
                    @else
                        <div class="credit-item">
                            <span>Mode</span>
                            <strong>{{ ucfirst($stats['fashn_model_config']['generation_mode'] ?? 'balanced') }}</strong>
                        </div>
                        <div class="credit-item">
                            <span>Resolution</span>
                            <strong>{{ strtoupper((string) ($stats['fashn_model_config']['resolution'] ?? '1k')) }}</strong>
                        </div>
                        <div class="credit-item">
                            <span>Format</span>
                            <strong>{{ strtoupper((string) ($stats['fashn_model_config']['format'] ?? 'png')) }}</strong>
                        </div>
                    @endif
                </div>
            </article>
        </section>

        <section class="split">
            <div class="panel" style="border-color: rgba(53,229,239,.6); box-shadow: inset 0 0 26px rgba(34,211,238,.2), 0 0 28px rgba(34,211,238,.16);">
                <h2>Recent Try-On</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Request ID</th>
                            <th>Model</th>
                            <th>Created</th>
                            <th>Status</th>
                            <th>Product</th>
                            <th>IP</th>
                            <th>Preview</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stats['recent_tryon'] as $session)
                            @php
                                $productImage = optional($session->product?->images?->firstWhere('is_primary', true) ?? $session->product?->images?->first())->image_url;
                                $modelImage = $resolvePublicPath($session->customer_photo_path);
                                $resultImage = $resolvePublicPath($session->result_path);
                                $sessionModel = is_string($session->provider_model) && trim($session->provider_model) !== ''
                                    ? trim($session->provider_model)
                                    : $stats['fashn_model'];
                                $statusClass = $session->status === 'failed' ? 'badge-failed' : (($session->status === 'processing' || $session->status === 'pending') ? 'badge-processing' : '');
                                $requestPayload = [
                                    'model' => $sessionModel,
                                    'product' => $session->product?->name,
                                    'source_channel' => $session->source_channel,
                                    'ip' => $session->ip_address,
                                    'inputs' => [
                                        'model_image' => $modelImage,
                                        'product_image' => $productImage,
                                    ],
                                ];
                                $responsePayload = [
                                    'id' => $session->provider_job_id ?: ('local-'.$session->id),
                                    'status' => $session->status,
                                    'error' => $session->error_message,
                                    'output' => $resultImage ? [$resultImage] : [],
                                    'token_cost' => (int) ($session->token_cost ?? 0),
                                ];
                            @endphp
                            <tr>
                                <td class="req-id">{{ $session->provider_job_id ?: ('local-'.$session->id) }}</td>
                                <td><span class="req-model">{{ $sessionModel }}</span></td>
                                <td>{{ $session->created_at->format('Y-m-d H:i:s') }}</td>
                                <td>
                                    <span class="badge {{ $statusClass }}">
                                        {{ ucfirst($session->status) }}
                                    </span>
                                </td>
                                <td>{{ $session->product?->name ?? '-' }}</td>
                                <td>{{ \App\Support\IpMasker::mask($session->ip_address) }}</td>
                                <td>
                                    @if($resultImage)
                                        <img class="preview-thumb" src="{{ $resultImage }}" alt="Preview">
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <button
                                        type="button"
                                        class="details-btn js-open-request-modal"
                                        data-request-id="{{ $session->provider_job_id ?: ('local-'.$session->id) }}"
                                        data-model="{{ $sessionModel }}"
                                        data-created="{{ $session->created_at->format('Y-m-d H:i:s') }}"
                                        data-status="{{ $session->status }}"
                                        data-credits="{{ (int) ($session->token_cost ?? 0) }}"
                                        data-product="{{ $session->product?->name ?? '-' }}"
                                        data-ip="{{ \App\Support\IpMasker::mask($session->ip_address) }}"
                                        data-model-image="{{ $modelImage }}"
                                        data-product-image="{{ $productImage }}"
                                        data-output-image="{{ $resultImage }}"
                                        data-request-json='@json($requestPayload)'
                                        data-response-json='@json($responsePayload)'
                                    >
                                        Details
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8">Belum ada request.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</div>

<div id="requestModal" class="modal-backdrop" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="modal-card">
        <div class="modal-header">
            <h3 class="modal-title">Request Details</h3>
            <button type="button" id="requestModalClose" class="modal-close">&times;</button>
        </div>
        <div class="modal-grid">
            <div class="modal-kv"><div class="k">Request ID</div><div class="v" id="mRequestId">-</div></div>
            <div class="modal-kv"><div class="k">Model</div><div class="v" id="mModel">-</div></div>
            <div class="modal-kv"><div class="k">Status</div><div class="v" id="mStatus">-</div></div>
            <div class="modal-kv"><div class="k">Credits</div><div class="v" id="mCredits">0</div></div>
            <div class="modal-kv"><div class="k">Product</div><div class="v" id="mProduct">-</div></div>
            <div class="modal-kv"><div class="k">IP</div><div class="v" id="mIp">-</div></div>
            <div class="modal-kv"><div class="k">Created at</div><div class="v" id="mCreated">-</div></div>
        </div>
        <div class="modal-tabs">
            <button type="button" class="tab-btn active" data-tab="tabInput">Input</button>
            <button type="button" class="tab-btn" data-tab="tabOutput">Output</button>
        </div>
        <section id="tabInput" class="tab-content active">
            <div class="image-grid">
                <div class="image-block">
                    <h4>Model</h4>
                    <img id="mModelImage" src="" alt="Model image">
                    <div id="mModelImageEmpty" class="image-empty" style="display:none;">No image</div>
                </div>
                <div class="image-block">
                    <h4>Product</h4>
                    <img id="mProductImage" src="" alt="Product image">
                    <div id="mProductImageEmpty" class="image-empty" style="display:none;">No image</div>
                </div>
            </div>
            <div class="json-box"><pre id="mRequestJson">{}</pre></div>
        </section>
        <section id="tabOutput" class="tab-content">
            <div class="image-grid">
                <div class="image-block">
                    <h4>Output</h4>
                    <img id="mOutputImage" src="" alt="Output image">
                    <div id="mOutputImageEmpty" class="image-empty" style="display:none;">No output image</div>
                </div>
            </div>
            <div class="json-box"><pre id="mResponseJson">{}</pre></div>
        </section>
    </div>
</div>

<script>
    const requestModal = document.getElementById('requestModal');
    const requestModalClose = document.getElementById('requestModalClose');
    const detailButtons = document.querySelectorAll('.js-open-request-modal');
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');

    const mRequestId = document.getElementById('mRequestId');
    const mModel = document.getElementById('mModel');
    const mStatus = document.getElementById('mStatus');
    const mCredits = document.getElementById('mCredits');
    const mProduct = document.getElementById('mProduct');
    const mIp = document.getElementById('mIp');
    const mCreated = document.getElementById('mCreated');
    const mModelImage = document.getElementById('mModelImage');
    const mProductImage = document.getElementById('mProductImage');
    const mOutputImage = document.getElementById('mOutputImage');
    const mModelImageEmpty = document.getElementById('mModelImageEmpty');
    const mProductImageEmpty = document.getElementById('mProductImageEmpty');
    const mOutputImageEmpty = document.getElementById('mOutputImageEmpty');
    const mRequestJson = document.getElementById('mRequestJson');
    const mResponseJson = document.getElementById('mResponseJson');

    const showImage = (imgEl, emptyEl, url) => {
        if (url && String(url).trim() !== '') {
            imgEl.src = url;
            imgEl.style.display = 'block';
            emptyEl.style.display = 'none';
        } else {
            imgEl.removeAttribute('src');
            imgEl.style.display = 'none';
            emptyEl.style.display = 'flex';
        }
    };

    detailButtons.forEach((btn) => {
        btn.addEventListener('click', () => {
            mRequestId.textContent = btn.dataset.requestId || '-';
            mModel.textContent = btn.dataset.model || '-';
            mStatus.textContent = btn.dataset.status || '-';
            mCredits.textContent = btn.dataset.credits || '0';
            mProduct.textContent = btn.dataset.product || '-';
            mIp.textContent = btn.dataset.ip || '-';
            mCreated.textContent = btn.dataset.created || '-';

            showImage(mModelImage, mModelImageEmpty, btn.dataset.modelImage || '');
            showImage(mProductImage, mProductImageEmpty, btn.dataset.productImage || '');
            showImage(mOutputImage, mOutputImageEmpty, btn.dataset.outputImage || '');

            let requestJson = {};
            let responseJson = {};
            try { requestJson = JSON.parse(btn.dataset.requestJson || '{}'); } catch (e) {}
            try { responseJson = JSON.parse(btn.dataset.responseJson || '{}'); } catch (e) {}
            mRequestJson.textContent = JSON.stringify(requestJson, null, 2);
            mResponseJson.textContent = JSON.stringify(responseJson, null, 2);

            tabButtons.forEach((tabBtn) => tabBtn.classList.remove('active'));
            tabContents.forEach((content) => content.classList.remove('active'));
            document.querySelector('[data-tab="tabInput"]').classList.add('active');
            document.getElementById('tabInput').classList.add('active');

            requestModal.classList.add('open');
            requestModal.setAttribute('aria-hidden', 'false');
        });
    });

    tabButtons.forEach((tabBtn) => {
        tabBtn.addEventListener('click', () => {
            const target = tabBtn.dataset.tab;
            tabButtons.forEach((x) => x.classList.remove('active'));
            tabContents.forEach((x) => x.classList.remove('active'));
            tabBtn.classList.add('active');
            document.getElementById(target).classList.add('active');
        });
    });

    const closeModal = () => {
        requestModal.classList.remove('open');
        requestModal.setAttribute('aria-hidden', 'true');
    };

    requestModalClose.addEventListener('click', closeModal);
    requestModal.addEventListener('click', (event) => {
        if (event.target === requestModal) {
            closeModal();
        }
    });
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && requestModal.classList.contains('open')) {
            closeModal();
        }
    });
</script>
</body>
</html>
