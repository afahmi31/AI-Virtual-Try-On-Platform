<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $seller->store_name }} - AI Try-On Store</title>
    <style>
        :root {
            --bg: #060b14;
            --panel: rgba(16, 25, 40, 0.92);
            --panel-border: rgba(80, 180, 255, 0.25);
            --text: #e6edf7;
            --muted: #9db0c8;
            --primary: #22d3ee;
            --accent: #3b82f6;
        }

        * {
            box-sizing: border-box;
        }

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
            min-height: 74px;
            padding: 0 24px;
            border-bottom: 1px solid rgba(120, 170, 255, 0.25);
            background: linear-gradient(90deg, #0b162f, #0a1b3d);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .brand {
            font-size: 32px;
            font-weight: 700;
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .brand-dot {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: rgba(34, 211, 238, .15);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
        }

        .store-logo {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            font-weight: 700;
            color: #052a31;
            background: linear-gradient(160deg, #3b82f6, #22d3ee);
            box-shadow: 0 0 16px rgba(34, 211, 238, .28);
        }

        .wrap {
            max-width: 1280px;
            margin: 0 auto;
            padding: 24px;
        }

        .hero {
            background: var(--panel);
            border: 1px solid var(--panel-border);
            border-radius: 14px;
            padding: 22px;
            box-shadow: inset 0 0 32px rgba(56, 189, 248, .08), 0 8px 28px rgba(0, 0, 0, .35);
            margin-bottom: 18px;
        }

        .hero h1 {
            margin: 0 0 8px;
            font-size: 44px;
        }

        .hero p {
            margin: 0;
            color: var(--muted);
            font-size: 20px;
        }

        .selected {
            margin-top: 12px;
            border: 1px solid rgba(34, 211, 238, .35);
            background: rgba(34, 211, 238, .09);
            border-radius: 10px;
            padding: 10px 12px;
            font-size: 18px;
        }

        .section-title {
            font-size: 30px;
            margin: 0 0 14px;
        }

        .page-grid {
            display: grid;
            grid-template-columns: 1fr 360px;
            gap: 18px;
            align-items: start;
        }

        .catalog-panel {
            background: var(--panel);
            border: 1px solid var(--panel-border);
            border-radius: 14px;
            padding: 18px;
            box-shadow: inset 0 0 24px rgba(56, 189, 248, .06), 0 6px 20px rgba(0, 0, 0, .32);
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 16px;
        }

        .card {
            display: flex;
            flex-direction: column;
            width: 100%;
            height: 100%;
            text-align: left;
            cursor: pointer;
            font: inherit;
            line-height: inherit;
            text-decoration: none;
            color: var(--text);
            background: var(--panel);
            border: 1px solid var(--panel-border);
            border-radius: 14px;
            overflow: hidden;
            transition: border-color .16s ease;
            box-shadow: inset 0 0 24px rgba(56, 189, 248, .06), 0 6px 20px rgba(0, 0, 0, .32);
            appearance: none;
            -webkit-appearance: none;
            padding: 0;
            margin: 0;
        }

        .card::-moz-focus-inner {
            border: 0;
            padding: 0;
        }

        .card:hover,
        .card:active,
        .card:focus,
        .card:focus-visible {
            transform: none;
            outline: none;
            box-shadow: inset 0 0 24px rgba(56, 189, 248, .06), 0 6px 20px rgba(0, 0, 0, .32);
        }

        .card.selected {
            border-color: rgba(34, 211, 238, .95);
        }

        .thumb-wrap {
            width: 100%;
            aspect-ratio: 1 / 1;
            background: rgba(255, 255, 255, .03);
            border-bottom: 1px solid rgba(130, 170, 230, .15);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .thumb {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .thumb-fallback {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #8fb2d9;
            font-size: 32px;
        }

        .card-body {
            padding: 12px;
            display: flex;
            flex-direction: column;
            gap: 2px;
            min-height: 150px;
        }

        .product-name {
            margin: 0 0 6px;
            font-size: 22px;
            line-height: 1.3;
        }

        .meta {
            margin: 0;
            color: var(--muted);
            font-size: 16px;
        }

        .empty {
            background: var(--panel);
            border: 1px dashed rgba(80, 180, 255, 0.4);
            border-radius: 14px;
            padding: 28px;
            color: var(--muted);
            font-size: 20px;
            text-align: center;
        }

        .tryon-panel {
            position: sticky;
            top: 16px;
            background: var(--panel);
            border: 1px solid var(--panel-border);
            border-radius: 14px;
            padding: 16px;
            box-shadow: inset 0 0 24px rgba(56, 189, 248, .06), 0 6px 20px rgba(0, 0, 0, .32);
        }

        .tryon-title {
            margin: 0 0 10px;
            font-size: 28px;
        }

        .field {
            margin-bottom: 12px;
        }

        .label {
            display: block;
            margin-bottom: 6px;
            color: #cad7ea;
            font-size: 16px;
        }

        .input-file,
        .select {
            width: 100%;
            border-radius: 10px;
            border: 1px solid rgba(54, 198, 230, .45);
            background: rgba(6, 14, 26, .65);
            color: var(--text);
            padding: 10px;
            font-size: 14px;
        }

        .preview-box {
            position: relative;
            width: 100%;
            aspect-ratio: 1 / 1;
            border-radius: 12px;
            border: 1px solid rgba(130, 170, 230, .25);
            background: rgba(255, 255, 255, .03);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .preview-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: none;
        }

        .preview-placeholder {
            color: var(--muted);
            font-size: 14px;
            text-align: center;
            padding: 10px;
        }
        .loading-dots {
            display: inline-flex;
            align-items: flex-end;
            gap: 8px;
            height: 28px;
        }
        .loading-dots span {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #35e5ef;
            box-shadow: 0 0 12px rgba(53, 229, 239, 0.45);
            animation: dot-bounce 0.9s ease-in-out infinite;
        }
        .loading-dots span:nth-child(2) { animation-delay: 0.15s; }
        .loading-dots span:nth-child(3) { animation-delay: 0.3s; }
        @keyframes dot-bounce {
            0%, 80%, 100% { transform: translateY(0); opacity: 0.5; }
            40% { transform: translateY(-10px); opacity: 1; }
        }
        .upload-center {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-top: 10px;
            padding: 10px 14px;
            border-radius: 10px;
            border: 1px solid rgba(54, 198, 230, .45);
            background: rgba(6, 14, 26, .65);
            color: var(--text);
            font-size: 13px;
            cursor: pointer;
        }
        .preview-remove {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 28px;
            height: 28px;
            border: none;
            border-radius: 8px;
            background: rgba(7, 14, 24, 0.85);
            color: #fca5a5;
            font-size: 20px;
            line-height: 1;
            display: none;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border: 1px solid rgba(248, 113, 113, 0.45);
        }

        .selected-product {
            border-radius: 10px;
            border: 1px solid rgba(34, 211, 238, .35);
            background: rgba(34, 211, 238, .09);
            padding: 10px;
            font-size: 14px;
            margin-bottom: 12px;
        }

        .quota-box {
            border-radius: 10px;
            border: 1px solid rgba(248, 113, 113, 0.45);
            background: rgba(248, 113, 113, 0.12);
            padding: 10px;
            font-size: 13px;
            margin-bottom: 12px;
            color: #fecaca;
        }

        .btn {
            width: 100%;
            border: none;
            border-radius: 12px;
            padding: 12px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            color: #052a31;
            background: linear-gradient(180deg, #35e5ef, #1ac6d7);
            box-shadow: 0 0 24px rgba(34, 211, 238, .3);
        }

        .result-note {
            margin-top: 8px;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.45;
        }

        .status-note {
            margin-top: 10px;
            font-size: 13px;
            color: var(--muted);
            min-height: 18px;
        }

        .status-error {
            color: #fecaca;
        }

        .status-success {
            color: #78f6dc;
        }

        .btn:disabled {
            opacity: .65;
            cursor: not-allowed;
        }

        @media (max-width: 900px) {
            .brand {
                font-size: 22px;
            }

            .hero h1 {
                font-size: 34px;
            }

            .page-grid {
                grid-template-columns: 1fr;
            }

            .tryon-panel {
                position: static;
            }
        }
    </style>
</head>

<body>
    @php
    $storeInitials = strtoupper(substr(trim($seller->store_name), 0, 2));
    @endphp

    <header class="topbar">
        <div class="brand"><span class="brand-dot">AI</span>{{ $seller->store_name }}</div>
        <div class="store-logo">{{ $storeInitials }}</div>
    </header>

    <main class="wrap">
        <section class="hero">
            <h1>{{ $seller->store_name }}</h1>
            <p>Katalog produk</p>

        </section>

        <section class="page-grid">
            <div class="catalog-panel">
                <h2 class="section-title">Product Catalog</h2>

                @if($products->isEmpty())
                <div class="empty">Belum ada produk aktif.</div>
                @else
                <div class="grid" id="productGrid">
                    @foreach ($products as $product)
                    @php
                    $image = $product->images->firstWhere('is_primary', true) ?? $product->images->first();
                    $isSelected = !empty($selectedProduct) && $selectedProduct->id === $product->id;
                    @endphp
                    <button
                        type="button"
                        class="card {{ $isSelected ? 'selected' : '' }}"
                        data-product-id="{{ $product->id }}"
                        data-product-name="{{ $product->name }}"
                        data-product-slug="{{ $product->slug }}"
                        data-product-sku="{{ $product->sku }}"
                        data-product-image="{{ $image?->image_url ?? '' }}"
                        onclick="selectProduct(this)">
                        <div class="thumb-wrap">
                            @if($image)
                            <img src="{{ $image->image_url }}" alt="{{ $product->name }}" class="thumb">
                            @else
                            <div class="thumb-fallback">IMG</div>
                            @endif
                        </div>
                        <div class="card-body">
                            <h3 class="product-name">{{ $product->name }}</h3>
                            <p class="meta">SKU: {{ $product->sku ?: '-' }}</p>
                            <p class="meta">Category: {{ $product->category ?: '-' }}</p>
                        </div>
                    </button>
                    @endforeach
                </div>
                @endif
            </div>

            <aside class="tryon-panel">
                <h2 class="tryon-title">Try-On Tool</h2>

                <div id="selectedProductInfo" class="selected-product">
                    @if(!empty($selectedProduct))
                    Produk: <strong id="selectedProductName">{{ $selectedProduct->name }}</strong>
                    @else
                    Produk: <strong id="selectedProductName">Belum dipilih</strong>
                    @endif
                </div>

                <div id="quotaBox" class="quota-box">
                    Sisa generate hari ini: <strong id="remainingQuotaText">-</strong>
                </div>

                <input class="input-file" id="customerPhoto" type="file" accept="image/*" style="display:none">

                <div class="field">
                    <label class="label">Model</label>
                    <div class="preview-box">
                        <img id="customerPreview" alt="Customer preview">
                        <button id="removePhotoBtn" class="preview-remove" type="button" aria-label="Hapus foto">×</button>
                        <div id="customerPlaceholder" class="preview-placeholder">
                            <label for="customerPhoto" class="upload-center">Pilih File</label>
                        </div>
                    </div>
                </div>

                <div class="field">
                    <label class="label">Try-On</label>
                    <div class="preview-box">
                        <img id="resultPreview" alt="Try-on result">
                        <div id="resultPlaceholder" class="preview-placeholder"></div>
                    </div>
                </div>

                <button id="generateBtn" class="btn" type="button" onclick="submitTryOn()">Try-On</button>
                <div id="statusNote" class="status-note"></div>
            </aside>
        </section>
    </main>
    <script>
        let selectedProductId = @json(optional($selectedProduct)->id);
        let pollTimer = null;
        const TRYON_DEVICE_KEY = 'tryon_device_id_v1';
        let remainingDailyQuota = null;

        function resolveTryOnDeviceId() {
            try {
                const existing = window.localStorage.getItem(TRYON_DEVICE_KEY);
                if (existing && typeof existing === 'string') {
                    return existing;
                }

                const generated = (window.crypto && window.crypto.randomUUID)
                    ? window.crypto.randomUUID()
                    : `dev-${Date.now()}-${Math.random().toString(36).slice(2, 12)}`;

                window.localStorage.setItem(TRYON_DEVICE_KEY, generated);
                return generated;
            } catch (error) {
                return `dev-fallback-${Date.now()}`;
            }
        }

        function selectProduct(el) {
            const cards = document.querySelectorAll('#productGrid .card');
            cards.forEach((card) => card.classList.remove('selected'));
            el.classList.add('selected');

            const productName = el.getAttribute('data-product-name') || 'Belum dipilih';
            selectedProductId = Number(el.getAttribute('data-product-id')) || null;
            const productSlug = el.getAttribute('data-product-slug');
            const productSku = el.getAttribute('data-product-sku');
            document.getElementById('selectedProductName').textContent = productName;

            const productRef = productSku || productSlug;
            if (productRef) {
                const newUrl = `/${@json($seller->slug)}/${productRef}`;
                window.history.replaceState({}, '', newUrl);
            }
        }

        document.getElementById('customerPhoto').addEventListener('change', function() {
            const file = this.files && this.files[0] ? this.files[0] : null;
            const preview = document.getElementById('customerPreview');
            const placeholder = document.getElementById('customerPlaceholder');
            const removeBtn = document.getElementById('removePhotoBtn');

            if (!file) {
                preview.removeAttribute('src');
                preview.style.display = 'none';
                placeholder.style.display = 'block';
                removeBtn.style.display = 'none';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(event) {
                preview.src = event.target.result;
                preview.style.display = 'block';
                placeholder.style.display = 'none';
                removeBtn.style.display = 'inline-flex';
            };
            reader.readAsDataURL(file);
        });

        document.getElementById('removePhotoBtn').addEventListener('click', function() {
            const input = document.getElementById('customerPhoto');
            const preview = document.getElementById('customerPreview');
            const placeholder = document.getElementById('customerPlaceholder');
            const removeBtn = document.getElementById('removePhotoBtn');

            input.value = '';
            preview.removeAttribute('src');
            preview.style.display = 'none';
            placeholder.style.display = 'block';
            removeBtn.style.display = 'none';
            setStatus('', '');
        });

        function setStatus(message, type) {
            const note = document.getElementById('statusNote');
            note.textContent = message || '';
            note.classList.remove('status-error', 'status-success');
            if (type === 'error') note.classList.add('status-error');
            if (type === 'success') note.classList.add('status-success');
        }

        function setLoading(loading) {
            const btn = document.getElementById('generateBtn');
            const resultPlaceholder = document.getElementById('resultPlaceholder');
            const mustDisableByQuota = remainingDailyQuota !== null && remainingDailyQuota <= 0;
            btn.disabled = loading || mustDisableByQuota;
            btn.textContent = loading ? 'Processing...' : 'Try-On';
            if (resultPlaceholder) {
                resultPlaceholder.innerHTML = loading
                    ? '<div class="loading-dots" aria-label="Loading"><span></span><span></span><span></span></div>'
                    : '';
            }
        }

        function updateQuotaUI(quota) {
            const el = document.getElementById('remainingQuotaText');
            if (!el || !quota) {
                return;
            }

            const limit = Number(quota.daily_limit ?? 3);
            const remaining = Number(quota.remaining ?? 0);
            remainingDailyQuota = remaining;
            el.textContent = `${remaining} / ${limit}`;

            if (remaining <= 0) {
                setStatus('Batas generate harian sudah habis (0).', 'error');
            }
            const btn = document.getElementById('generateBtn');
            if (btn && !btn.disabled && remaining <= 0) {
                btn.disabled = true;
            }
        }

        async function refreshQuota() {
            try {
                const response = await fetch(@json(route('public.tryon.quota.show', ['seller_slug' => $seller->slug])), {
                    headers: {
                        'Accept': 'application/json',
                        'X-Tryon-Device-Id': resolveTryOnDeviceId(),
                    },
                });

                const payload = await response.json();
                if (!response.ok) {
                    return;
                }

                updateQuotaUI(payload);
            } catch (error) {
                // Keep silent; quota endpoint failure should not break page interaction.
            }
        }

        async function submitTryOn() {
            const customerPreview = document.getElementById('customerPreview');
            const resultPreview = document.getElementById('resultPreview');
            const resultPlaceholder = document.getElementById('resultPlaceholder');
            const customerPhotoInput = document.getElementById('customerPhoto');
            const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            if (!selectedProductId) {
                setStatus('Pilih produk terlebih dahulu.', 'error');
                return;
            }

            const file = customerPhotoInput.files && customerPhotoInput.files[0] ? customerPhotoInput.files[0] : null;
            if (!file || !customerPreview.getAttribute('src')) {
                setStatus('upload foto model', 'error');
                return;
            }

            if (remainingDailyQuota !== null && remainingDailyQuota <= 0) {
                setStatus('Batas generate harian sudah habis (0).', 'error');
                setLoading(false);
                return;
            }

            setLoading(true);
            setStatus('', '');
            resultPreview.removeAttribute('src');
            resultPreview.style.display = 'none';
            resultPlaceholder.style.display = 'block';

            try {
                const formData = new FormData();
                formData.append('product_id', String(selectedProductId));
                formData.append('customer_photo', file);

                const createResponse = await fetch(@json(route('public.tryon.sessions.store', ['seller_slug' => $seller->slug])), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'X-Tryon-Device-Id': resolveTryOnDeviceId(),
                        'Accept': 'application/json',
                    },
                    body: formData,
                });

                const createPayload = await createResponse.json();
                if (!createResponse.ok) {
                    if (createPayload.quota) {
                        updateQuotaUI(createPayload.quota);
                    }
                    throw new Error(createPayload.message || 'Gagal membuat session try-on.');
                }

                if (createPayload.quota) {
                    updateQuotaUI(createPayload.quota);
                }
                setStatus('', '');
                await pollTryOnStatus(createPayload.id);
            } catch (error) {
                setStatus(error.message || 'Terjadi kesalahan saat generate try-on.', 'error');
                setLoading(false);
            }
        }

        async function pollTryOnStatus(sessionId) {
            const resultPreview = document.getElementById('resultPreview');
            const resultPlaceholder = document.getElementById('resultPlaceholder');
            const statusUrlBase = @json(url('/'));

            if (pollTimer) {
                clearInterval(pollTimer);
                pollTimer = null;
            }

            let attempts = 0;
            const maxAttempts = 90;

            pollTimer = setInterval(async () => {
                attempts += 1;
                try {
                    const response = await fetch(`${statusUrlBase}/${@json($seller->slug)}/try-on/sessions/${sessionId}`, {
                        headers: {
                            'Accept': 'application/json'
                        },
                    });
                    const payload = await response.json();
                    if (!response.ok) {
                        throw new Error(payload.message || 'Gagal cek status try-on.');
                    }

                    if (payload.status === 'completed') {
                        clearInterval(pollTimer);
                        pollTimer = null;

                        if (payload.result_url) {
                            resultPreview.src = payload.result_url;
                            resultPreview.style.display = 'block';
                            resultPlaceholder.style.display = 'none';
                        }
                    setStatus('', '');
                    setLoading(false);
                    return;
                    }

                    if (payload.status === 'failed') {
                        clearInterval(pollTimer);
                        pollTimer = null;
                        setStatus(payload.error_message || 'Try-on gagal diproses.', 'error');
                        setLoading(false);
                        return;
                    }

                    if (attempts >= maxAttempts) {
                        clearInterval(pollTimer);
                        pollTimer = null;
                        setStatus('Proses masih berjalan. Silakan coba lagi sebentar.', 'error');
                        setLoading(false);
                    }
                } catch (error) {
                    clearInterval(pollTimer);
                    pollTimer = null;
                    setStatus(error.message || 'Gagal polling status try-on.', 'error');
                    setLoading(false);
                }
            }, 2000);
        }

        (function ensureInitialSelectedProduct() {
            const current = document.querySelector('#productGrid .card.selected');
            if (current) {
                selectProduct(current);
            }
            refreshQuota();
        })();
    </script>
</body>

</html>


