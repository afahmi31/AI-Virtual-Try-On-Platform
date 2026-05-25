<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $seoTitle = trim((string) ($seller->seo_title ?? '')) ?: ('Katalog Produk '.$seller->store_name);
        $seoDescription = trim((string) ($seller->seo_description ?? '')) ?: ('Belanja produk terbaik dari '.$seller->store_name.'.');
        $seoImage = trim((string) ($seller->seo_logo_url ?? ''));
        $canonicalUrl = route('public.seller.page', ['seller_slug' => $seller->slug]);
    @endphp
    <title>{{ $seoTitle }}</title>
    <meta name="description" content="{{ $seoDescription }}">
    <link rel="canonical" href="{{ $canonicalUrl }}">
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $seoTitle }}">
    <meta property="og:description" content="{{ $seoDescription }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    @if($seoImage !== '')
    <meta property="og:image" content="{{ $seoImage }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:image" content="{{ $seoImage }}">
    @else
    <meta name="twitter:card" content="summary">
    @endif
    <meta name="twitter:title" content="{{ $seoTitle }}">
    <meta name="twitter:description" content="{{ $seoDescription }}">
    <style>
        :root {
            --bg: #f6f2e6;
            --surface: #fffdf7;
            --surface-soft: #fff8ed;
            --text: #2f1f16;
            --muted: #7d6a57;
            --orange: #ef7c3f;
            --orange-deep: #dc6a31;
            --blue-soft: #c7e0ff;
            --blue-text: #6f93bf;
            --radius-xl: 24px;
            --radius-lg: 18px;
            --radius-md: 14px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Nunito", "Segoe UI", sans-serif;
            color: var(--text);
            background:
                radial-gradient(1200px 420px at 16% -12%, #fff8e1 0%, transparent 60%),
                radial-gradient(900px 400px at 90% -10%, #eaf4ff 0%, transparent 65%),
                var(--bg);
        }

        .topbar-wrap {
            padding: 0 18px 0;
        }

        .topbar {
            max-width: 1300px;
            margin: 0 auto;
            background: var(--surface);
            border-radius: 0 0 22px 22px;
            box-shadow: 0 14px 30px rgba(76, 52, 31, 0.12);
            padding: 18px 26px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: 0;
        }

        .brand-label {
            font-size: 34px;
            font-weight: 900;
            letter-spacing: .2px;
            color: #6f93bf;
            line-height: 1;
        }

        .wrap {
            max-width: 1300px;
            margin: 0 auto;
            padding: 26px;
            position: relative;
        }

        .section-title {
            margin: 0 0 16px;
            font-size: 46px;
            line-height: 1.06;
            color: #b35e26;
        }

        .section-subtitle {
            margin: 0 0 20px;
            font-size: 18px;
            color: var(--muted);
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(245px, 1fr));
            gap: 16px;
        }

        .card {
            display: flex;
            flex-direction: column;
            border: 1px solid #eedfcb;
            border-radius: var(--radius-lg);
            background: var(--surface);
            overflow: hidden;
            cursor: pointer;
            text-align: left;
            padding: 0;
            transition: transform .14s ease, box-shadow .14s ease, border-color .14s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 20px rgba(116, 75, 43, 0.12);
            border-color: #f3c8a8;
        }

        .card.selected {
            border-color: #ef7c3f;
            box-shadow: 0 10px 18px rgba(222, 122, 64, 0.2);
        }

        .thumb-wrap {
            width: 100%;
            aspect-ratio: 1 / 1;
            background: #fff;
            border-bottom: 1px solid #f4e8d7;
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
            color: #ceb69f;
            font-weight: 700;
            font-size: 14px;
        }

        .card-body {
            padding: 12px;
            display: flex;
            flex-direction: column;
            gap: 6px;
            min-height: 120px;
        }

        .product-name {
            margin: 0;
            font-size: 18px;
            line-height: 1.22;
            color: #302015;
        }

        .meta {
            margin: 0;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.3;
        }

        .empty {
            border: 1px dashed #e4ceb3;
            border-radius: var(--radius-lg);
            background: var(--surface);
            padding: 26px;
            text-align: center;
            color: var(--muted);
        }

        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(43, 23, 9, 0.5);
            backdrop-filter: blur(3px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 18px;
            z-index: 1500;
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: opacity .24s ease, visibility .24s ease;
        }

        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }

        .modal {
            width: min(980px, 100%);
            max-height: 92vh;
            overflow: auto;
            background: linear-gradient(180deg, #ffffff, #fff6e9);
            border-radius: var(--radius-xl);
            border: 1px solid #ebd4ba;
            box-shadow: 0 22px 60px rgba(80, 49, 22, 0.26);
            padding: 16px;
            transform: translateY(14px) scale(.98);
            opacity: 0;
            transition: transform .24s ease, opacity .24s ease;
        }

        .modal-overlay.active .modal {
            transform: translateY(0) scale(1);
            opacity: 1;
        }

        .modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 14px;
        }

        .modal-title {
            margin: 0;
            font-size: 31px;
            color: #c4662f;
        }

        .modal-close {
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: #f9e7d4;
            color: #9b5327;
            font-size: 22px;
            cursor: pointer;
        }

        .modal-info-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.6fr) minmax(220px, 0.8fr);
            gap: 12px;
            margin-bottom: 12px;
        }

        .selected-product {
            padding: 2px 0;
            font-size: 14px;
            margin: 0;
        }

        .quota-box {
            border: none;
            background: transparent;
            color: #8c4c22;
            padding: 2px 0;
            font-size: 13px;
            margin: 0;
            text-align: right;
            white-space: nowrap;
            justify-self: end;
            width: auto;
            max-width: none;
        }

        .field {
            border-radius: var(--radius-md);
            background: var(--surface);
            border: 1px solid #edd9c3;
            padding: 12px;
        }

        .label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 700;
            color: #7f5739;
        }

        .label-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 8px;
        }

        .label-row .label {
            margin-bottom: 0;
        }

        .dummy-toggle {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            color: #7f5739;
            user-select: none;
            cursor: pointer;
        }

        .dummy-toggle input {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
            pointer-events: none;
        }

        .dummy-toggle-switch {
            position: relative;
            width: 40px;
            height: 22px;
            border-radius: 999px;
            background: #e5d6c2;
            border: 1px solid #d8c3a9;
            transition: background .2s ease, border-color .2s ease;
            flex: 0 0 auto;
        }

        .dummy-toggle-switch::after {
            content: '';
            position: absolute;
            top: 2px;
            left: 2px;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: #ffffff;
            box-shadow: 0 1px 3px rgba(43, 23, 9, 0.24);
            transition: transform .2s ease;
        }

        .dummy-toggle input:checked + .dummy-toggle-switch {
            background: #f29a63;
            border-color: #ea8344;
        }

        .dummy-toggle input:checked + .dummy-toggle-switch::after {
            transform: translateX(18px);
        }

        .dummy-toggle-text {
            font-weight: 700;
            line-height: 1;
        }

        .input-file {
            display: none;
        }

        .upload-trigger {
            width: 100%;
            height: 48px;
            border: none;
            border-radius: 12px;
            background: linear-gradient(180deg, #f89b5f, #ec7a3f);
            color: #fff;
            font-size: 17px;
            font-weight: 800;
            cursor: pointer;
        }

        .status-note {
            margin-top: 10px;
            min-height: 20px;
            font-size: 13px;
            line-height: 1.4;
            color: var(--muted);
        }

        .status-error {
            color: #bf3e2e;
        }

        .status-success {
            color: #206a52;
        }

        .modal-preview-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
            margin-bottom: 14px;
        }

        .preview-box {
            position: relative;
            width: 100%;
            aspect-ratio: 4 / 5;
            border-radius: var(--radius-md);
            border: 1px solid #e8d3bc;
            background: #fff;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .preview-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: none;
        }

        .preview-placeholder {
            color: #9f8772;
            text-align: center;
            padding: 14px;
            font-size: 14px;
            line-height: 1.35;
        }

        .preview-remove {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 30px;
            height: 30px;
            border: none;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.9);
            color: #cb4f3e;
            font-size: 20px;
            cursor: pointer;
            display: none;
        }

        .loading-dots {
            display: inline-flex;
            align-items: flex-end;
            gap: 8px;
            height: 26px;
        }

        .loading-dots span {
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: #eb7a3f;
            animation: dot-bounce 0.9s ease-in-out infinite;
        }

        .loading-dots span:nth-child(2) {
            animation-delay: 0.15s;
        }

        .loading-dots span:nth-child(3) {
            animation-delay: 0.3s;
        }

        @keyframes dot-bounce {
            0%,
            80%,
            100% {
                transform: translateY(0);
                opacity: 0.5;
            }

            40% {
                transform: translateY(-10px);
                opacity: 1;
            }
        }

        .generate-btn {
            width: 100%;
            height: 50px;
            border: none;
            border-radius: 14px;
            background: linear-gradient(180deg, #f99657, #ec7a3f);
            color: #fff;
            font-size: 19px;
            font-weight: 900;
            cursor: pointer;
        }

        .generate-btn:disabled {
            opacity: .6;
            cursor: not-allowed;
        }

        .floating-history-btn {
            position: fixed;
            right: max(18px, calc((100vw - 1300px) / 2 + 26px));
            bottom: 26px;
            width: 56px;
            height: 56px;
            border: none;
            border-radius: 999px;
            background: linear-gradient(180deg, #f89b5f, #ec7a3f);
            color: #fff;
            box-shadow: 0 14px 28px rgba(116, 75, 43, 0.28);
            cursor: pointer;
            z-index: 1400;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            font-weight: 900;
        }

        .floating-history-panel {
            position: fixed;
            right: max(18px, calc((100vw - 1300px) / 2 + 26px));
            bottom: 96px;
            width: min(360px, calc(100vw - 36px));
            max-height: 62vh;
            overflow: auto;
            background: #fffdf7;
            border: 1px solid #edd9c3;
            border-radius: 14px;
            box-shadow: 0 18px 36px rgba(56, 34, 20, 0.24);
            z-index: 1400;
            padding: 10px;
            display: none;
        }

        .floating-history-panel.active {
            display: block;
        }

        .floating-history-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            margin-bottom: 10px;
        }

        .floating-history-title {
            margin: 0;
            font-size: 14px;
            font-weight: 800;
            color: #7f5739;
        }

        .floating-history-close {
            border: none;
            width: 28px;
            height: 28px;
            border-radius: 8px;
            background: #f5e4d2;
            color: #9b5327;
            cursor: pointer;
        }

        .history-wrap {
            margin: 8px 0 14px;
            border: 1px solid #edd9c3;
            border-radius: var(--radius-md);
            background: var(--surface);
            padding: 10px;
        }

        .history-title {
            margin: 0 0 8px;
            font-size: 13px;
            font-weight: 800;
            color: #7f5739;
        }

        .history-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(84px, 1fr));
            gap: 8px;
        }

        .history-item {
            border: 1px solid #e7cfb3;
            border-radius: 10px;
            background: #fff;
            overflow: hidden;
            padding: 0;
            cursor: pointer;
            min-height: 112px;
        }

        .history-item img {
            width: 100%;
            aspect-ratio: 1 / 1;
            object-fit: cover;
            display: block;
        }

        .history-item-time {
            display: block;
            font-size: 10px;
            color: #8c725b;
            padding: 5px 6px 6px;
            text-align: center;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .history-empty {
            margin: 0;
            font-size: 12px;
            color: #8c725b;
        }

        @media (max-width: 900px) {
            .topbar {
                flex-direction: column;
                align-items: stretch;
            }

            .brand {
                gap: 0;
            }

            .brand-label {
                font-size: 26px;
            }

            .section-title {
                font-size: 36px;
            }

            .modal-preview-grid {
                grid-template-columns: 1fr;
            }

            .modal-info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <div class="topbar-wrap">
        <header class="topbar">
            <div class="brand">
                <span class="brand-label">Katalog Produk</span>
            </div>
        </header>
    </div>

    <main class="wrap">
@if($products->isEmpty())
        <div class="empty">Belum ada produk aktif.</div>
        @else
        <section class="grid" id="productGrid">
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
                data-product-category="{{ $product->category }}"
                data-product-image="{{ $image?->image_url ?? '' }}"
                onclick="openTryOnModal(this)">
                <div class="thumb-wrap">
                    @if($image)
                    <img src="{{ $image->image_url }}" alt="{{ $product->name }}" class="thumb">
                    @else
                    <div class="thumb-fallback">No Image</div>
                    @endif
                </div>
                <div class="card-body">
                    <h3 class="product-name">{{ $product->name }}</h3>
                    <p class="meta">Category: {{ $product->category ?: '-' }}</p>
                </div>
            </button>
            @endforeach
        </section>
        @endif
        <button id="floatingHistoryBtn" type="button" class="floating-history-btn" aria-label="Lihat riwayat try-on">
            &#128340;
        </button>
        <div id="floatingHistoryPanel" class="floating-history-panel" aria-hidden="true">
            <div class="floating-history-header">
                <p class="floating-history-title">Riwayat Try-On</p>
                <button id="floatingHistoryClose" type="button" class="floating-history-close" aria-label="Tutup riwayat">&times;</button>
            </div>
            <div id="floatingHistoryList" class="history-list"></div>
            <p id="floatingHistoryEmpty" class="history-empty">Belum ada riwayat generate.</p>
        </div>
    </main>

    <div class="modal-overlay" id="tryOnModal" aria-hidden="true">
        <div class="modal" role="dialog" aria-modal="true" aria-labelledby="tryOnModalTitle">
            <div class="modal-header">
                <h2 class="modal-title" id="tryOnModalTitle">Try-On Tool</h2>
                <button type="button" class="modal-close" onclick="closeTryOnModal()" aria-label="Tutup">&times;</button>
            </div>

            <div class="modal-info-grid">
                <div id="selectedProductInfo" class="selected-product">
                    Produk: <strong id="selectedProductName">Belum dipilih</strong>
                </div>

                <div id="quotaBox" class="quota-box">
                    Sisa generate hari ini: <strong id="remainingQuotaText">-</strong>
                </div>
            </div>

            <input class="input-file" id="customerPhoto" type="file" accept="image/*">

            <div class="modal-preview-grid">
                <div class="field">
                    <div class="label-row">
                        <label class="label">Foto</label>
                        <label class="dummy-toggle" id="dummyModelToggleWrap" style="display:none;">
                            <input type="checkbox" id="useDummyModelToggle">
                            <span class="dummy-toggle-switch" aria-hidden="true"></span>
                            <span class="dummy-toggle-text">Use Dummy Model</span>
                        </label>
                    </div>
                    <div class="preview-box">
                        <img id="customerPreview" alt="Customer preview">
                        <button id="removePhotoBtn" class="preview-remove" type="button" aria-label="Hapus foto">&times;</button>
                        <div id="customerPlaceholder" class="preview-placeholder">
                            <p>Upload foto</p>
                            <button type="button" class="upload-trigger" onclick="document.getElementById('customerPhoto').click()">Pilih File</button>
                        </div>
                    </div>
                </div>

                <div class="field">
                    <label class="label">Try-On Generated</label>
                    <div class="preview-box">
                        <img id="resultPreview" alt="Try-on result">
                        <div id="resultPlaceholder" class="preview-placeholder"></div>
                    </div>
                    <div id="statusNote" class="status-note" role="status" aria-live="polite"></div>
                </div>
            </div>

            <div class="history-wrap">
                <p class="history-title">Riwayat Generate</p>
                <div id="historyList" class="history-list"></div>
                <p id="historyEmpty" class="history-empty">Belum ada riwayat generate.</p>
            </div>

            <button id="generateBtn" class="generate-btn" type="button" onclick="submitTryOn()">Try-On</button>
        </div>
    </div>

    <script>
        let selectedProductId = @json(optional($selectedProduct)->id);
        let pollTimer = null;
        const TRYON_DEVICE_KEY = 'tryon_device_id_v1';
        const TRYON_DUMMY = @json($tryOnDummy ?? ['enabled' => false, 'model_image_url' => '', 'result_url' => '']);
        const TRYON_HISTORY_URL = @json(route('public.tryon.sessions.history', ['seller_slug' => $seller->slug]));
        let remainingDailyQuota = null;
        let useDummyModelForRealGenerate = Boolean(TRYON_DUMMY.model_image_url);

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

        function openTryOnModal(el) {
            selectProduct(el);
            const modal = document.getElementById('tryOnModal');
            modal.classList.add('active');
            modal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
            refreshHistory();
        }

        function closeTryOnModal() {
            const modal = document.getElementById('tryOnModal');
            modal.classList.remove('active');
            modal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
        }

        document.getElementById('tryOnModal').addEventListener('click', function(event) {
            if (event.target === this) {
                closeTryOnModal();
            }
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeTryOnModal();
            }
        });

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
            if (!note) {
                return;
            }
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

        function showResultPlaceholderMessage(message, type = '') {
            const resultPreview = document.getElementById('resultPreview');
            const resultPlaceholder = document.getElementById('resultPlaceholder');
            if (!resultPlaceholder || !resultPreview) {
                return;
            }

            resultPreview.removeAttribute('src');
            resultPreview.style.display = 'none';
            resultPlaceholder.style.display = 'block';
            resultPlaceholder.textContent = message || '';
            resultPlaceholder.classList.remove('status-error', 'status-success');
            if (type === 'error') resultPlaceholder.classList.add('status-error');
            if (type === 'success') resultPlaceholder.classList.add('status-success');
        }

        function formatHistoryDateLabel(isoDate) {
            if (!isoDate) {
                return '-';
            }

            const dt = new Date(isoDate);
            if (Number.isNaN(dt.getTime())) {
                return '-';
            }

            return new Intl.DateTimeFormat('id-ID', {
                day: '2-digit',
                month: 'short',
                hour: '2-digit',
                minute: '2-digit',
            }).format(dt);
        }

        function showHistoryResult(url) {
            if (!url) {
                return;
            }

            const resultPreview = document.getElementById('resultPreview');
            const resultPlaceholder = document.getElementById('resultPlaceholder');
            resultPreview.src = url;
            resultPreview.style.display = 'block';
            resultPlaceholder.style.display = 'none';
            setStatus('Menampilkan hasil dari riwayat.', 'success');
        }

        function renderHistory(items) {
            const listEl = document.getElementById('historyList');
            const emptyEl = document.getElementById('historyEmpty');
            if (!listEl || !emptyEl) {
                return;
            }

            listEl.innerHTML = '';

            if (!Array.isArray(items) || items.length === 0) {
                emptyEl.style.display = 'block';
                return;
            }

            emptyEl.style.display = 'none';
            items.forEach((item) => {
                if (!item || !item.result_url) {
                    return;
                }

                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'history-item';
                btn.setAttribute('aria-label', 'Lihat hasil riwayat');

                const img = document.createElement('img');
                img.src = item.result_url;
                img.alt = 'Riwayat hasil try-on';

                const time = document.createElement('span');
                time.className = 'history-item-time';
                time.textContent = formatHistoryDateLabel(item.created_at);

                btn.appendChild(img);
                btn.appendChild(time);
                btn.addEventListener('click', () => showHistoryResult(item.result_url));
                listEl.appendChild(btn);
            });
        }

        function renderFloatingHistory(items) {
            const listEl = document.getElementById('floatingHistoryList');
            const emptyEl = document.getElementById('floatingHistoryEmpty');
            if (!listEl || !emptyEl) {
                return;
            }

            listEl.innerHTML = '';

            if (!Array.isArray(items) || items.length === 0) {
                emptyEl.style.display = 'block';
                return;
            }

            emptyEl.style.display = 'block';
            emptyEl.style.display = 'none';

            items.forEach((item) => {
                if (!item || !item.result_url) {
                    return;
                }

                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'history-item';
                btn.setAttribute('aria-label', 'Lihat hasil riwayat');

                const img = document.createElement('img');
                img.src = item.result_url;
                img.alt = 'Riwayat hasil try-on';

                const time = document.createElement('span');
                time.className = 'history-item-time';
                time.textContent = formatHistoryDateLabel(item.created_at);

                btn.appendChild(img);
                btn.appendChild(time);
                btn.addEventListener('click', () => {
                    const currentCard = document.querySelector('#productGrid .card.selected') || document.querySelector('#productGrid .card');
                    if (currentCard) {
                        openTryOnModal(currentCard);
                    }
                    showHistoryResult(item.result_url);
                    closeFloatingHistoryPanel();
                });
                listEl.appendChild(btn);
            });
        }

        function openFloatingHistoryPanel() {
            const panel = document.getElementById('floatingHistoryPanel');
            if (!panel) {
                return;
            }
            panel.classList.add('active');
            panel.setAttribute('aria-hidden', 'false');
            refreshHistory();
        }

        function closeFloatingHistoryPanel() {
            const panel = document.getElementById('floatingHistoryPanel');
            if (!panel) {
                return;
            }
            panel.classList.remove('active');
            panel.setAttribute('aria-hidden', 'true');
        }

        async function refreshHistory() {
            try {
                const response = await fetch(TRYON_HISTORY_URL, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Tryon-Device-Id': resolveTryOnDeviceId(),
                    },
                });

                const payload = await response.json();
                if (!response.ok) {
                    return;
                }

                renderHistory(payload.items || []);
                renderFloatingHistory(payload.items || []);
            } catch (error) {
                // History failure should not block the page.
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

        function consumeDummyQuotaUI() {
            if (remainingDailyQuota === null) {
                return;
            }

            remainingDailyQuota = Math.max(remainingDailyQuota - 1, 0);
            const quotaEl = document.getElementById('remainingQuotaText');
            if (quotaEl) {
                const parts = quotaEl.textContent.split('/');
                const limit = Number((parts[1] || '').trim());
                if (!Number.isNaN(limit) && limit > 0) {
                    quotaEl.textContent = `${remainingDailyQuota} / ${limit}`;
                }
            }
        }

        function applyDummyModelSelectionUI() {
            const customerPhotoInput = document.getElementById('customerPhoto');
            const customerPreview = document.getElementById('customerPreview');
            const customerPlaceholder = document.getElementById('customerPlaceholder');
            const removePhotoBtn = document.getElementById('removePhotoBtn');
            const toggleWrap = document.getElementById('dummyModelToggleWrap');
            const toggle = document.getElementById('useDummyModelToggle');
            const hasDummyModelUrl = Boolean(TRYON_DUMMY.model_image_url);

            if (!toggleWrap || !toggle) {
                return;
            }

            if (!hasDummyModelUrl || TRYON_DUMMY.enabled) {
                toggleWrap.style.display = 'none';
                useDummyModelForRealGenerate = false;
                toggle.checked = false;
                customerPhotoInput.disabled = false;
                return;
            }

            toggleWrap.style.display = 'inline-flex';
            useDummyModelForRealGenerate = toggle.checked;

            if (useDummyModelForRealGenerate) {
                customerPhotoInput.value = '';
                customerPhotoInput.disabled = true;
                removePhotoBtn.style.display = 'none';
                customerPreview.src = TRYON_DUMMY.model_image_url;
                customerPreview.style.display = 'block';
                customerPlaceholder.style.display = 'none';
                return;
            }

            customerPhotoInput.disabled = false;
            if (!customerPhotoInput.files || !customerPhotoInput.files[0]) {
                customerPreview.removeAttribute('src');
                customerPreview.style.display = 'none';
                customerPlaceholder.style.display = 'block';
                removePhotoBtn.style.display = 'none';
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
                // Quota failure should not block the page.
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

            if (TRYON_DUMMY.enabled) {
                if (!TRYON_DUMMY.model_image_url) {
                    setStatus('Dummy aktif, tetapi Dummy Model Image URL belum diisi.', 'error');
                    showResultPlaceholderMessage('Dummy Model Image URL belum diisi.', 'error');
                    return;
                }

                if (!TRYON_DUMMY.result_url) {
                    setStatus('Dummy aktif, tetapi Dummy Result URL belum diisi.', 'error');
                    showResultPlaceholderMessage('Dummy Result URL belum diisi.', 'error');
                    return;
                }

                if (remainingDailyQuota !== null && remainingDailyQuota <= 0) {
                    setStatus('Batas generate harian sudah habis (0).', 'error');
                    showResultPlaceholderMessage('Batas generate harian sudah habis.', 'error');
                    setLoading(false);
                    return;
                }

                setLoading(true);
                setStatus('', '');
                resultPreview.removeAttribute('src');
                resultPreview.style.display = 'none';
                resultPlaceholder.style.display = 'block';

                await new Promise((resolve) => window.setTimeout(resolve, 1800));

                resultPreview.src = TRYON_DUMMY.result_url;
                resultPreview.style.display = 'block';
                resultPlaceholder.style.display = 'none';
                consumeDummyQuotaUI();
                setStatus('Generate selesai (dummy mode).', 'success');
                setLoading(false);
                refreshHistory();
                return;
            }

            const useDummyModelImage = Boolean(TRYON_DUMMY.model_image_url) && useDummyModelForRealGenerate;
            const file = customerPhotoInput.files && customerPhotoInput.files[0] ? customerPhotoInput.files[0] : null;
            if (!useDummyModelImage && (!file || !customerPreview.getAttribute('src'))) {
                setStatus('Upload foto model terlebih dahulu.', 'error');
                showResultPlaceholderMessage('Upload foto model terlebih dahulu.', 'error');
                return;
            }

            if (remainingDailyQuota !== null && remainingDailyQuota <= 0) {
                setStatus('Batas generate harian sudah habis (0).', 'error');
                showResultPlaceholderMessage('Batas generate harian sudah habis.', 'error');
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
                formData.append('use_dummy_model', useDummyModelImage ? '1' : '0');
                if (!useDummyModelImage && file) {
                    formData.append('customer_photo', file);
                }

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

                await pollTryOnStatus(createPayload.id);
            } catch (error) {
                setStatus(error.message || 'Terjadi kesalahan saat generate try-on.', 'error');
                showResultPlaceholderMessage(error.message || 'Terjadi kesalahan saat generate try-on.', 'error');
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

                        setStatus('Generate selesai.', 'success');
                        setLoading(false);
                        refreshHistory();
                        return;
                    }

                    if (payload.status === 'failed') {
                        clearInterval(pollTimer);
                        pollTimer = null;
                        const errorMessage = payload.error_message || 'Try-on gagal diproses.';
                        setStatus(errorMessage, 'error');
                        showResultPlaceholderMessage(errorMessage, 'error');
                        setLoading(false);
                        return;
                    }

                    if (attempts >= maxAttempts) {
                        clearInterval(pollTimer);
                        pollTimer = null;
                        const timeoutMessage = 'Proses masih berjalan. Silakan coba lagi sebentar.';
                        setStatus(timeoutMessage, 'error');
                        showResultPlaceholderMessage(timeoutMessage, 'error');
                        setLoading(false);
                    }
                } catch (error) {
                    clearInterval(pollTimer);
                    pollTimer = null;
                    const pollingErrorMessage = error.message || 'Gagal polling status try-on.';
                    setStatus(pollingErrorMessage, 'error');
                    showResultPlaceholderMessage(pollingErrorMessage, 'error');
                    setLoading(false);
                }
            }, 2000);
        }

        (function initPage() {
            const current = document.querySelector('#productGrid .card.selected');
            if (current) {
                selectProduct(current);
            }

            const floatingBtn = document.getElementById('floatingHistoryBtn');
            const floatingClose = document.getElementById('floatingHistoryClose');
            const floatingPanel = document.getElementById('floatingHistoryPanel');
            if (floatingBtn) {
                floatingBtn.addEventListener('click', openFloatingHistoryPanel);
            }
            if (floatingClose) {
                floatingClose.addEventListener('click', closeFloatingHistoryPanel);
            }
            if (floatingPanel) {
                document.addEventListener('click', function(event) {
                    if (!floatingPanel.classList.contains('active')) {
                        return;
                    }
                    if (floatingPanel.contains(event.target) || (floatingBtn && floatingBtn.contains(event.target))) {
                        return;
                    }
                    closeFloatingHistoryPanel();
                });
            }

            const toggle = document.getElementById('useDummyModelToggle');
            if (toggle) {
                toggle.checked = Boolean(TRYON_DUMMY.model_image_url);
                toggle.addEventListener('change', function() {
                    useDummyModelForRealGenerate = this.checked;
                    applyDummyModelSelectionUI();
                });
            }
            applyDummyModelSelectionUI();
            refreshQuota();
            refreshHistory();
        })();
    </script>
</body>

</html>
