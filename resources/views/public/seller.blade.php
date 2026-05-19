<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            min-height: 74px;
            padding: 0 24px;
            border-bottom: 1px solid rgba(120, 170, 255, 0.25);
            background: linear-gradient(90deg, #0b162f, #0a1b3d);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }
        .brand { font-size: 32px; font-weight: 700; display: flex; gap: 12px; align-items: center; }
        .brand-dot {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: rgba(34,211,238,.15);
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
            box-shadow: 0 0 16px rgba(34,211,238,.28);
        }
        .wrap { max-width: 1280px; margin: 0 auto; padding: 24px; }
        .hero {
            background: var(--panel);
            border: 1px solid var(--panel-border);
            border-radius: 14px;
            padding: 22px;
            box-shadow: inset 0 0 32px rgba(56,189,248,.08), 0 8px 28px rgba(0,0,0,.35);
            margin-bottom: 18px;
        }
        .hero h1 { margin: 0 0 8px; font-size: 44px; }
        .hero p { margin: 0; color: var(--muted); font-size: 20px; }
        .selected {
            margin-top: 12px;
            border: 1px solid rgba(34,211,238,.35);
            background: rgba(34,211,238,.09);
            border-radius: 10px;
            padding: 10px 12px;
            font-size: 18px;
        }
        .section-title { font-size: 30px; margin: 0 0 14px; }
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
            box-shadow: inset 0 0 24px rgba(56,189,248,.06), 0 6px 20px rgba(0,0,0,.32);
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
            box-shadow: inset 0 0 24px rgba(56,189,248,.06), 0 6px 20px rgba(0,0,0,.32);
            appearance: none;
            -webkit-appearance: none;
            padding: 0;
            margin: 0;
        }
        .card::-moz-focus-inner { border: 0; padding: 0; }
        .card:hover,
        .card:active,
        .card:focus,
        .card:focus-visible {
            transform: none;
            outline: none;
            box-shadow: inset 0 0 24px rgba(56,189,248,.06), 0 6px 20px rgba(0,0,0,.32);
        }
        .card.selected {
            border-color: rgba(34,211,238,.95);
        }
        .thumb-wrap {
            width: 100%;
            aspect-ratio: 1 / 1;
            background: rgba(255,255,255,.03);
            border-bottom: 1px solid rgba(130,170,230,.15);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        .thumb { width: 100%; height: 100%; object-fit: cover; display: block; }
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
        .product-name { margin: 0 0 6px; font-size: 22px; line-height: 1.3; }
        .meta { margin: 0; color: var(--muted); font-size: 16px; }
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
            box-shadow: inset 0 0 24px rgba(56,189,248,.06), 0 6px 20px rgba(0,0,0,.32);
        }
        .tryon-title { margin: 0 0 10px; font-size: 28px; }
        .field { margin-bottom: 12px; }
        .label { display: block; margin-bottom: 6px; color: #cad7ea; font-size: 16px; }
        .input-file, .select {
            width: 100%;
            border-radius: 10px;
            border: 1px solid rgba(54,198,230,.45);
            background: rgba(6,14,26,.65);
            color: var(--text);
            padding: 10px;
            font-size: 14px;
        }
        .preview-box {
            width: 100%;
            aspect-ratio: 1 / 1;
            border-radius: 12px;
            border: 1px solid rgba(130,170,230,.25);
            background: rgba(255,255,255,.03);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        .preview-box img { width: 100%; height: 100%; object-fit: cover; display: none; }
        .preview-placeholder { color: var(--muted); font-size: 14px; text-align: center; padding: 10px; }
        .selected-product {
            border-radius: 10px;
            border: 1px solid rgba(34,211,238,.35);
            background: rgba(34,211,238,.09);
            padding: 10px;
            font-size: 14px;
            margin-bottom: 12px;
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
            background: linear-gradient(180deg,#35e5ef,#1ac6d7);
            box-shadow: 0 0 24px rgba(34,211,238,.3);
        }
        .result-note {
            margin-top: 8px;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.45;
        }
        @media (max-width: 900px) {
            .brand { font-size: 22px; }
            .hero h1 { font-size: 34px; }
            .page-grid { grid-template-columns: 1fr; }
            .tryon-panel { position: static; }
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
        <p>Katalog produk untuk Virtual Try-On</p>

        @if (!empty($selectedProduct))
            <div class="selected">
                Produk dipilih: <strong>{{ $selectedProduct->name }}</strong>
            </div>
        @endif
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
                            data-product-image="{{ $image?->image_url ?? '' }}"
                            onclick="selectProduct(this)"
                        >
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
                    Produk terpilih: <strong id="selectedProductName">{{ $selectedProduct->name }}</strong>
                @else
                    Produk terpilih: <strong id="selectedProductName">Belum dipilih</strong>
                @endif
            </div>

            <div class="field">
                <label class="label" for="customerPhoto">Upload Foto Customer</label>
                <input class="input-file" id="customerPhoto" type="file" accept="image/*">
            </div>

            <div class="field">
                <label class="label">Preview Foto Customer</label>
                <div class="preview-box">
                    <img id="customerPreview" alt="Customer preview">
                    <div id="customerPlaceholder" class="preview-placeholder">Belum ada foto diupload</div>
                </div>
            </div>

            <div class="field">
                <label class="label">Hasil Try-On</label>
                <div class="preview-box">
                    <img id="resultPreview" alt="Try-on result">
                    <div id="resultPlaceholder" class="preview-placeholder">Hasil akan tampil di sini setelah proses generate.</div>
                </div>
                <div class="result-note">Saat ini panel ini siap untuk integrasi FASHN AI. Sementara, tombol Generate menampilkan simulasi preview.</div>
            </div>

            <button class="btn" type="button" onclick="simulateTryOn()">Generate Try-On</button>
        </aside>
    </section>
</main>
<script>
    function selectProduct(el) {
        const cards = document.querySelectorAll('#productGrid .card');
        cards.forEach((card) => card.classList.remove('selected'));
        el.classList.add('selected');

        const productName = el.getAttribute('data-product-name') || 'Belum dipilih';
        const productSlug = el.getAttribute('data-product-slug');
        document.getElementById('selectedProductName').textContent = productName;

        if (productSlug) {
            const newUrl = `/${@json($seller->slug)}/${productSlug}`;
            window.history.replaceState({}, '', newUrl);
        }
    }

    document.getElementById('customerPhoto').addEventListener('change', function () {
        const file = this.files && this.files[0] ? this.files[0] : null;
        const preview = document.getElementById('customerPreview');
        const placeholder = document.getElementById('customerPlaceholder');

        if (!file) {
            preview.removeAttribute('src');
            preview.style.display = 'none';
            placeholder.style.display = 'block';
            return;
        }

        const reader = new FileReader();
        reader.onload = function (event) {
            preview.src = event.target.result;
            preview.style.display = 'block';
            placeholder.style.display = 'none';
        };
        reader.readAsDataURL(file);
    });

    function simulateTryOn() {
        const selectedCard = document.querySelector('#productGrid .card.selected');
        const customerPreview = document.getElementById('customerPreview');
        const resultPreview = document.getElementById('resultPreview');
        const resultPlaceholder = document.getElementById('resultPlaceholder');

        if (!selectedCard) {
            alert('Pilih produk terlebih dahulu.');
            return;
        }

        if (!customerPreview.getAttribute('src')) {
            alert('Upload foto customer terlebih dahulu.');
            return;
        }

        resultPreview.src = customerPreview.getAttribute('src');
        resultPreview.style.display = 'block';
        resultPlaceholder.style.display = 'none';
    }

    (function ensureInitialSelectedProduct() {
        const current = document.querySelector('#productGrid .card.selected');
        if (current) {
            selectProduct(current);
        }
    })();
</script>
</body>
</html>
