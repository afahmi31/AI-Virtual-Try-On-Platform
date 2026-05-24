<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products - Try-On Commerce Studio</title>
    <style>
        :root {
            --bg: #050b17;
            --bg-soft: #071329;
            --panel: rgba(10, 20, 38, 0.92);
            --panel-strong: rgba(8, 18, 34, 0.98);
            --panel-border: rgba(61, 177, 255, 0.26);
            --text: #e8f1ff;
            --muted: #9ab0cd;
            --primary: #31d9f1;
            --primary-strong: #11bfd8;
            --danger: #ff8a8a;
            --success: #31d8be;
            --overlay: rgba(1, 5, 12, 0.76);
            --fs-caption: 12px;
            --fs-label: 13px;
            --fs-control: 14px;
            --fs-body: 15px;
            --fs-nav: 16px;
            --fs-section-title: 30px;
            --fs-page-title: 40px;
            --fs-modal-title: 34px;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text);
            background:
                radial-gradient(circle at 18% 22%, rgba(38, 222, 255, 0.16), transparent 32%),
                radial-gradient(circle at 84% 72%, rgba(45, 130, 255, 0.18), transparent 30%),
                linear-gradient(140deg, #040914 0%, #051127 38%, #041029 100%);
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

        .topnav {
            display: flex;
            gap: 12px;
            align-items: center;
        }

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
        .store-icon-svg { width: 18px; height: 18px; stroke: #032a33; stroke-width: 1.9; fill: none; }

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

        .layout {
            display: grid;
            grid-template-columns: 220px minmax(0, 1fr);
            min-height: calc(100vh - 72px);
        }

        .sidebar {
            border-right: 1px solid rgba(115, 170, 240, 0.18);
            background: linear-gradient(180deg, rgba(8, 16, 30, 0.95), rgba(6, 13, 24, 0.96));
            padding: 18px 14px;
        }

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

        .menu-item:hover {
            color: #d8e6fb;
            border-color: rgba(120, 160, 220, 0.2);
        }

        .menu-item.active {
            color: #3be0f5;
            background: rgba(52, 219, 242, 0.12);
            border-color: rgba(52, 219, 242, 0.3);
        }

        .content {
            padding: 26px;
        }

        h1 {
            margin: 0 0 18px;
            font-size: var(--fs-page-title);
            line-height: 1.06;
            color: #e9f3ff;
        }

        .panel {
            background: var(--panel);
            border: 1px solid var(--panel-border);
            border-radius: 14px;
            padding: 18px;
            margin-bottom: 16px;
            box-shadow: inset 0 0 44px rgba(56, 189, 248, 0.06), 0 8px 30px rgba(0, 0, 0, 0.32);
        }

        .panel-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 14px;
        }

        .panel h2 {
            margin: 0;
            font-size: var(--fs-section-title);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: var(--fs-body);
            border: 1px solid rgba(127, 166, 218, 0.2);
            border-radius: 12px;
            overflow: hidden;
        }

        th,
        td {
            padding: 12px 10px;
            border-bottom: 1px solid rgba(127, 166, 218, 0.15);
            text-align: left;
            vertical-align: middle;
        }

        th {
            color: #b9cdea;
            font-weight: 600;
            font-size: var(--fs-label);
            letter-spacing: 0.3px;
            background: rgba(255, 255, 255, 0.035);
        }

        tr:last-child td {
            border-bottom: none;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: var(--fs-caption);
            border: 1px solid rgba(49, 216, 190, 0.45);
            color: var(--success);
            background: rgba(49, 216, 190, 0.12);
            font-weight: 600;
        }

        .status-inactive {
            color: #ffd99a;
            border-color: rgba(255, 198, 104, 0.45);
            background: rgba(255, 198, 104, 0.13);
        }

        .thumb,
        .thumb-fallback {
            width: 54px;
            height: 54px;
            border-radius: 10px;
            border: 1px solid rgba(130, 170, 230, 0.3);
            background: rgba(255, 255, 255, 0.04);
        }

        .thumb {
            object-fit: cover;
        }

        .thumb-fallback {
            display: flex;
            align-items: center;
            justify-content: center;
            color: #8fb2d9;
            font-size: var(--fs-caption);
            font-weight: 600;
        }

        .actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .btn {
            border: none;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: var(--fs-control);
            cursor: pointer;
            line-height: 1;
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .btn-primary {
            background: linear-gradient(180deg, #3ce2f3, #18c8db);
            color: #062932;
            font-weight: 700;
            box-shadow: 0 0 20px rgba(45, 216, 239, 0.32);
        }

        .btn-ghost {
            background: rgba(49, 217, 241, 0.11);
            color: #6be5f8;
            border: 1px solid rgba(49, 217, 241, 0.34);
        }

        .flash {
            margin-bottom: 12px;
            padding: 10px 12px;
            border-radius: 10px;
            font-size: var(--fs-control);
        }

        .flash-success {
            border: 1px solid rgba(49, 216, 190, 0.45);
            color: #83ffe5;
            background: rgba(49, 216, 190, 0.12);
        }

        .flash-error {
            border: 1px solid rgba(248, 113, 113, 0.45);
            color: #ffd2d2;
            background: rgba(248, 113, 113, 0.13);
        }

        .modal {
            position: fixed;
            inset: 0;
            background: var(--overlay);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            padding: 16px;
            backdrop-filter: blur(2px);
        }

        .modal.active {
            display: flex;
        }

        .modal-card {
            width: min(1040px, 100%);
            background:
                linear-gradient(145deg, rgba(11, 23, 43, 0.96), rgba(8, 18, 36, 0.98));
            border: 1px solid rgba(62, 177, 255, 0.35);
            border-radius: 14px;
            box-shadow: 0 20px 42px rgba(0, 0, 0, 0.5), inset 0 0 24px rgba(45, 154, 255, 0.12);
            padding: 18px 18px 16px;
        }

        .modal-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 14px;
        }
        .modal-head-right {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .modal-title {
            margin: 0;
            font-size: var(--fs-modal-title);
            line-height: 1.08;
            letter-spacing: 0.3px;
            color: #f0f6ff;
        }

        .close-btn {
            background: rgba(255, 255, 255, 0.03);
            color: var(--muted);
            border: 1px solid rgba(157, 176, 200, 0.3);
            border-radius: 10px;
            padding: 7px 12px;
            font-size: var(--fs-caption);
            cursor: pointer;
        }

        .close-btn:hover {
            color: #e5efff;
            border-color: rgba(49, 217, 241, 0.38);
        }
        .status-toggle {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: var(--fs-label);
            color: #d4e3f8;
        }
        .status-toggle-switch {
            position: relative;
            width: 46px;
            height: 26px;
            border-radius: 999px;
            background: rgba(157, 176, 200, 0.28);
            border: 1px solid rgba(157, 176, 200, 0.35);
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .status-toggle-switch::after {
            content: '';
            position: absolute;
            top: 2px;
            left: 2px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #ffffff;
            transition: transform 0.2s ease;
        }
        .status-toggle input {
            display: none;
        }
        .status-toggle input:checked + .status-toggle-switch {
            background: rgba(49, 217, 241, 0.42);
            border-color: rgba(49, 217, 241, 0.55);
        }
        .status-toggle input:checked + .status-toggle-switch::after {
            transform: translateX(20px);
            background: #ddfbff;
        }
        .status-toggle-label {
            min-width: 54px;
            text-transform: capitalize;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px 14px;
        }

        .product-form-grid {
            display: grid;
            grid-template-columns: 340px minmax(0, 1fr);
            gap: 14px;
        }

        .product-left-col,
        .product-right-col {
            display: flex;
            flex-direction: column;
            gap: 12px;
            min-width: 0;
        }

        .field {
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .field.preview-field {
            grid-column: 2;
            grid-row: 4 / span 2;
        }

        label {
            margin-bottom: 6px;
            font-size: var(--fs-label);
            color: #d4e3f8;
            letter-spacing: 0.15px;
        }

        input,
        select {
            width: 100%;
            height: 40px;
            border-radius: 9px;
            border: 1px solid rgba(54, 198, 230, 0.5);
            background: rgba(6, 15, 29, 0.66);
            color: var(--text);
            padding: 0 11px;
            font-size: var(--fs-control);
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        input:focus,
        select:focus {
            border-color: rgba(80, 215, 242, 0.9);
            box-shadow: 0 0 0 3px rgba(42, 197, 223, 0.16);
        }

        .preview-wrap {
            border: 1px solid rgba(72, 175, 252, 0.34);
            border-radius: 10px;
            min-height: 470px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(5, 12, 24, 0.72);
            position: relative;
            overflow: hidden;
        }

        .preview-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: none;
            position: absolute;
            inset: 0;
        }

        .preview-placeholder {
            color: #90a7c6;
            font-size: var(--fs-control);
            text-align: center;
            padding: 10px;
        }

        .preview-change-overlay {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            z-index: 2;
            border: 1px solid rgba(49, 217, 241, 0.55);
            border-radius: 10px;
            background: linear-gradient(180deg, #42e6f4, #22cfe1);
            color: #032631;
            font-size: var(--fs-control);
            font-weight: 700;
            padding: 9px 14px;
            cursor: pointer;
            box-shadow: 0 0 18px rgba(49, 217, 241, 0.3);
        }

        .url-label {
            text-align: center;
            color: #63d9ef;
            font-size: var(--fs-label);
            line-height: 1;
            margin-top: 2px;
        }

        .modal-bottom-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-top: 2px;
        }

        .status-inline {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: #d4e3f8;
            font-size: var(--fs-control);
        }

        .preview-hint {
            margin-top: 6px;
            color: var(--muted);
            font-size: var(--fs-caption);
        }

        .modal-actions {
            margin-top: 14px;
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .btn-cancel {
            background: transparent;
            color: #6ee5f8;
            border: 1px solid rgba(49, 217, 241, 0.45);
        }

        .pagination-wrap {
            margin-top: 12px;
        }

        .pagination-wrap nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            color: var(--muted);
        }

        .pagination-wrap nav > div:first-child {
            font-size: var(--fs-caption);
            color: #9cb1cc;
        }

        .pagination-wrap nav > div:last-child > span,
        .pagination-wrap nav > div:last-child > a {
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .pagination-wrap a,
        .pagination-wrap span[aria-current="page"] span,
        .pagination-wrap span[aria-disabled="true"] span {
            min-width: 34px;
            height: 34px;
            padding: 0 10px;
            border-radius: 9px;
            border: 1px solid rgba(112, 162, 230, 0.28);
            background: rgba(10, 19, 35, 0.82);
            color: #d6e7fd;
            font-size: var(--fs-caption);
            line-height: 1;
            text-decoration: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }

        .pagination-wrap a:hover {
            border-color: rgba(57, 213, 240, 0.55);
            background: rgba(49, 217, 241, 0.12);
            box-shadow: 0 0 14px rgba(57, 213, 240, 0.18);
        }

        .pagination-wrap span[aria-current="page"] span {
            border-color: rgba(57, 213, 240, 0.65);
            background: linear-gradient(180deg, rgba(57, 213, 240, 0.3), rgba(22, 137, 211, 0.3));
            color: #ecf8ff;
            box-shadow: 0 0 16px rgba(57, 213, 240, 0.2);
            font-weight: 600;
        }

        .pagination-wrap span[aria-disabled="true"] span {
            opacity: 0.55;
            cursor: not-allowed;
        }

        .pagination-wrap svg {
            width: 14px;
            height: 14px;
        }

        @media (max-width: 1100px) {
            .layout {
                grid-template-columns: 84px minmax(0, 1fr);
            }

            .menu-item span {
                display: none;
            }

            .brand {
                font-size: 15px;
            }

            .content {
                padding: 18px;
            }
        }

        @media (max-width: 820px) {
            .layout {
                grid-template-columns: 1fr;
            }

            .sidebar {
                display: flex;
                gap: 8px;
                border-right: none;
                border-bottom: 1px solid rgba(115, 170, 240, 0.18);
            }

            .menu-item {
                margin-bottom: 0;
            }

            h1 {
                font-size: 32px;
            }

            .panel h2 {
                font-size: 25px;
            }

            .panel-head {
                flex-direction: column;
                align-items: flex-start;
            }

            .modal-title {
                font-size: 28px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .product-form-grid {
                grid-template-columns: 1fr;
            }

            .field.preview-field {
                grid-column: auto;
                grid-row: auto;
            }

            .preview-wrap {
                min-height: 320px;
            }

            .pagination-wrap nav {
                flex-direction: column;
                align-items: flex-start;
            }

            .topbar {
                flex-wrap: wrap;
                height: auto;
                padding: 12px;
                gap: 10px;
            }
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
        <a class="menu-item active" href="{{ route('seller.products.index') }}"><span>Products</span></a>
        <a class="menu-item" href="{{ route('seller.settings.index') }}"><span>Settings</span></a>
    </aside>

    <main class="content">
        <h1>Manage Products</h1>

        @if(session('success'))
            <div class="flash flash-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="flash flash-error">
                @foreach($errors->all() as $err)
                    <div>{{ $err }}</div>
                @endforeach
            </div>
        @endif

        <section class="panel">
            <div class="panel-head">
                <h2>Product List</h2>
                <button class="btn btn-primary" type="button" onclick="openCreateModal()">Add New Product</button>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Product</th>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        @php $primaryImage = $product->images->firstWhere('is_primary', true) ?? $product->images->first(); @endphp
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>
                                @if($primaryImage)
                                    <img src="{{ $primaryImage->image_url }}" alt="{{ $product->name }}" class="thumb">
                                @else
                                    <div class="thumb-fallback" aria-label="No image">IMG</div>
                                @endif
                            </td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->slug }}</td>
                            <td>
                                <span class="status-badge {{ $product->status === 'inactive' ? 'status-inactive' : '' }}">
                                    {{ ucfirst($product->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="actions">
                                    <button class="btn btn-ghost" type="button" onclick="openEditModal({{ $product->id }}, '{{ addslashes($product->name) }}', '{{ addslashes($product->sku ?? '') }}', '{{ addslashes($product->category ?? '') }}', '{{ $product->status }}', '{{ addslashes($primaryImage?->image_url ?? '') }}', '{{ addslashes($product->ai_prompt ?? '') }}', '{{ addslashes($product->ai_category ?? 'auto') }}', '{{ addslashes($product->ai_garment_photo_type ?? 'auto') }}', '{{ (int) ($product->ai_segmentation_free ?? true) }}')">Edit</button>
                                    <form method="POST" action="{{ route('seller.products.destroy', $product->id) }}" onsubmit="return confirm('Hapus produk ini?');" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-ghost" type="submit" style="color:var(--danger); border-color: rgba(248,113,113,.4);">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6">Belum ada produk.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="pagination-wrap">{{ $products->links() }}</div>
        </section>
    </main>
</div>

<div id="createModal" class="modal" onclick="closeOnBackdrop(event, 'createModal')">
    <div class="modal-card">
        <div class="modal-head">
            <h3 class="modal-title">Create New Product</h3>
            <div class="modal-head-right">
                <label class="status-toggle">
                    <span>Status</span>
                    <input id="createStatusToggle" type="checkbox" checked>
                    <span class="status-toggle-switch"></span>
                    <span id="createStatusLabel" class="status-toggle-label">active</span>
                </label>
                <button class="close-btn" type="button" onclick="closeModal('createModal')">Close</button>
            </div>
        </div>
        <form method="POST" action="{{ route('seller.products.store') }}" enctype="multipart/form-data">
            @csrf
            <input id="createStatus" type="hidden" name="status" value="active">
            <div class="product-form-grid">
                <div class="product-left-col">
                    <div class="preview-wrap">
                        <img id="createImagePreview" class="preview-img" alt="Create preview">
                        <button class="preview-change-overlay" type="button" onclick="document.getElementById('createImageFile').click()">Change Image</button>
                    </div>
                    <input id="createImageFile" type="file" name="image" accept="image/*" style="display:none;">
                    <div class="url-label">Or Replace with Public URL</div>
                    <input id="createImageUrl" type="url" name="image_url" placeholder="https://...">
                </div>
                <div class="product-right-col">
                    <div class="field"><label>Product Name</label><input name="name" required></div>
                    <div class="field"><label>SKU</label><input name="sku"></div>
                    <div class="field"><label>Category</label><input name="category" placeholder="Select Category..."></div>
                    <div class="field">
                        <label>AI Prompt <span class="preview-hint">(Try-On Max)</span></label>
                        <input name="ai_prompt" placeholder="Opsional, contoh: long modest muslim dress for 12-year-old girl">
                    </div>
                    <div class="field">
                        <label>AI Category <span class="preview-hint">(Try-On v1.6)</span></label>
                        <select name="ai_category">
                            <option value="auto" selected>auto - biarkan AI deteksi otomatis</option>
                            <option value="tops">tops - atasan (kemeja, blouse, t-shirt)</option>
                            <option value="bottoms">bottoms - bawahan (rok, celana)</option>
                            <option value="one-pieces">one-pieces - baju terusan (dress, gamis)</option>
                        </select>
                        <div class="preview-hint">Pilih sesuai jenis utama pakaian pada foto produk agar hasil try-on lebih pas.</div>
                    </div>
                    <div class="field">
                        <label>Garment Photo Type <span class="preview-hint">(Try-On v1.6)</span></label>
                        <select name="ai_garment_photo_type">
                            <option value="auto" selected>auto - biarkan AI deteksi otomatis</option>
                            <option value="flat-lay">flat-lay - foto produk tanpa dipakai model</option>
                            <option value="model">model - foto produk sedang dipakai model/manekin</option>
                        </select>
                        <div class="preview-hint">Sesuaikan dengan tipe foto garment yang di-upload supaya bentuk baju tidak salah baca.</div>
                    </div>
                    <div class="field">
                        <input type="hidden" name="ai_segmentation_free" value="0">
                        <label class="status-toggle" style="justify-content:flex-start;">
                            <span>Segmentation Free <span class="preview-hint">(Try-On v1.6)</span></span>
                            <input type="checkbox" name="ai_segmentation_free" value="1" checked>
                            <span class="status-toggle-switch"></span>
                            <span class="status-toggle-label">enabled</span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="modal-bottom-row">
                <div class="modal-actions" style="margin-top:0; margin-left:auto;">
                    <button class="btn btn-cancel" type="button" onclick="closeModal('createModal')">Cancel</button>
                    <button class="btn btn-primary" type="submit">Create Product</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div id="editModal" class="modal" onclick="closeOnBackdrop(event, 'editModal')">
    <div class="modal-card">
        <div class="modal-head">
            <h3 class="modal-title">Edit Product</h3>
            <div class="modal-head-right">
                <label class="status-toggle">
                    <span>Status</span>
                    <input id="editStatusToggle" type="checkbox" checked>
                    <span class="status-toggle-switch"></span>
                    <span id="editStatusLabel" class="status-toggle-label">active</span>
                </label>
                <button class="close-btn" type="button" onclick="closeModal('editModal')">Close</button>
            </div>
        </div>
        <form id="editForm" method="POST" action="" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            <input id="editProductId" type="hidden" name="edit_product_id" value="">
            <input id="editStatus" type="hidden" name="status" value="active">
            <div class="product-form-grid">
                <div class="product-left-col">
                    <div class="preview-wrap">
                        <img id="editImagePreview" class="preview-img" alt="Edit preview">
                        <button class="preview-change-overlay" type="button" onclick="document.getElementById('editImageFile').click()">Change Image</button>
                    </div>
                    <input id="editImageFile" type="file" name="image" accept="image/*" style="display:none;">
                    <div class="url-label">Or Replace with Public URL</div>
                    <input id="editImageUrl" type="url" name="image_url" placeholder="https://...">
                </div>
                <div class="product-right-col">
                    <div class="field"><label>Product Name</label><input id="editName" name="name" required></div>
                    <div class="field"><label>SKU</label><input id="editSku" name="sku"></div>
                    <div class="field"><label>Category</label><input id="editCategory" name="category"></div>
                    <div class="field">
                        <label>AI Prompt <span class="preview-hint">(Try-On Max)</span></label>
                        <input id="editAiPrompt" name="ai_prompt" placeholder="Opsional, contoh: long modest muslim dress for 12-year-old girl">
                    </div>
                    <div class="field">
                        <label>AI Category <span class="preview-hint">(Try-On v1.6)</span></label>
                        <select id="editAiCategory" name="ai_category">
                            <option value="auto">auto - biarkan AI deteksi otomatis</option>
                            <option value="tops">tops - atasan (kemeja, blouse, t-shirt)</option>
                            <option value="bottoms">bottoms - bawahan (rok, celana)</option>
                            <option value="one-pieces">one-pieces - baju terusan (dress, gamis)</option>
                        </select>
                        <div class="preview-hint">Pilih sesuai jenis utama pakaian pada foto produk agar hasil try-on lebih pas.</div>
                    </div>
                    <div class="field">
                        <label>Garment Photo Type <span class="preview-hint">(Try-On v1.6)</span></label>
                        <select id="editAiGarmentPhotoType" name="ai_garment_photo_type">
                            <option value="auto">auto - biarkan AI deteksi otomatis</option>
                            <option value="flat-lay">flat-lay - foto produk tanpa dipakai model</option>
                            <option value="model">model - foto produk sedang dipakai model/manekin</option>
                        </select>
                        <div class="preview-hint">Sesuaikan dengan tipe foto garment yang di-upload supaya bentuk baju tidak salah baca.</div>
                    </div>
                    <div class="field">
                        <input type="hidden" name="ai_segmentation_free" value="0">
                        <label class="status-toggle" style="justify-content:flex-start;">
                            <span>Segmentation Free <span class="preview-hint">(Try-On v1.6)</span></span>
                            <input id="editAiSegmentationFree" type="checkbox" name="ai_segmentation_free" value="1" checked>
                            <span class="status-toggle-switch"></span>
                            <span id="editAiSegmentationFreeLabel" class="status-toggle-label">enabled</span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="modal-bottom-row">
                <div class="modal-actions" style="margin-top:0; margin-left:auto;">
                    <button class="btn btn-cancel" type="button" onclick="closeModal('editModal')">Cancel</button>
                    <button class="btn btn-primary" type="submit">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal(id) { document.getElementById(id).classList.add('active'); }
    function closeModal(id) { document.getElementById(id).classList.remove('active'); }
    function closeOnBackdrop(event, id) { if (event.target.id === id) closeModal(id); }
    function openCreateModal() {
        resetPreview('createImageFile', 'createImageUrl', 'createImagePreview');
        setCreateStatus('active');
        openModal('createModal');
    }

    function openEditModal(id, name, sku, category, status, imageUrl, aiPrompt, aiCategory, aiGarmentPhotoType, aiSegmentationFree) {
        const form = document.getElementById('editForm');
        form.action = `/dashboard/products/${id}`;
        document.getElementById('editProductId').value = id;
        document.getElementById('editName').value = name;
        document.getElementById('editSku').value = sku;
        document.getElementById('editCategory').value = category;
        document.getElementById('editAiPrompt').value = aiPrompt || '';
        document.getElementById('editAiCategory').value = aiCategory || 'auto';
        document.getElementById('editAiGarmentPhotoType').value = aiGarmentPhotoType || 'auto';
        document.getElementById('editAiSegmentationFree').checked = String(aiSegmentationFree) !== '0';
        const editSegmentationLabel = document.getElementById('editAiSegmentationFreeLabel');
        if (editSegmentationLabel) {
            editSegmentationLabel.textContent = document.getElementById('editAiSegmentationFree').checked ? 'enabled' : 'disabled';
        }
        setEditStatus(status || 'active');
        resetPreview('editImageFile', 'editImageUrl', 'editImagePreview');
        document.getElementById('editImageUrl').value = imageUrl || '';
        setPreviewFromUrl('editImagePreview', imageUrl);
        openModal('editModal');
    }

    function setEditStatus(status) {
        const normalizedStatus = String(status || 'active').toLowerCase() === 'inactive' ? 'inactive' : 'active';
        const statusInput = document.getElementById('editStatus');
        const statusToggle = document.getElementById('editStatusToggle');
        const statusLabel = document.getElementById('editStatusLabel');
        if (!statusInput) {
            return;
        }

        statusInput.value = normalizedStatus;
        if (statusToggle) statusToggle.checked = normalizedStatus === 'active';
        if (statusLabel) statusLabel.textContent = normalizedStatus;
    }

    function setCreateStatus(status) {
        const normalizedStatus = String(status || 'active').toLowerCase() === 'inactive' ? 'inactive' : 'active';
        const statusInput = document.getElementById('createStatus');
        const statusToggle = document.getElementById('createStatusToggle');
        const statusLabel = document.getElementById('createStatusLabel');
        if (!statusInput) {
            return;
        }

        statusInput.value = normalizedStatus;
        if (statusToggle) statusToggle.checked = normalizedStatus === 'active';
        if (statusLabel) statusLabel.textContent = normalizedStatus;
    }

    function resetPreview(fileInputId, urlInputId, previewId) {
        const fileInput = document.getElementById(fileInputId);
        const urlInput = document.getElementById(urlInputId);
        const preview = document.getElementById(previewId);
        if (fileInput) fileInput.value = '';
        if (urlInput) urlInput.value = '';
        if (preview) {
            preview.removeAttribute('src');
            preview.style.display = 'none';
        }
    }

    function bindImagePreview(fileInputId, urlInputId, previewId) {
        const fileInput = document.getElementById(fileInputId);
        const urlInput = document.getElementById(urlInputId);
        const preview = document.getElementById(previewId);
        if (!fileInput || !urlInput || !preview) return;

        fileInput.addEventListener('change', function () {
            const file = this.files && this.files[0] ? this.files[0] : null;
            if (!file) {
                preview.removeAttribute('src');
                preview.style.display = 'none';
                return;
            }
            urlInput.value = '';
            const reader = new FileReader();
            reader.onload = function (event) {
                preview.src = event.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        });

        urlInput.addEventListener('input', function () {
            const value = this.value.trim();
            if (!value) {
                preview.removeAttribute('src');
                preview.style.display = 'none';
                return;
            }
            fileInput.value = '';
            setPreviewFromUrl(previewId, value);
        });
    }

    function setPreviewFromUrl(previewId, value) {
        const preview = document.getElementById(previewId);
        if (!preview) return;

        const url = (value || '').trim();
        if (!url) {
            preview.removeAttribute('src');
            preview.style.display = 'none';
            return;
        }

        preview.src = url;
        preview.style.display = 'block';
    }

    bindImagePreview('createImageFile', 'createImageUrl', 'createImagePreview');
    bindImagePreview('editImageFile', 'editImageUrl', 'editImagePreview');

    const editStatusToggle = document.getElementById('editStatusToggle');
    if (editStatusToggle) {
        editStatusToggle.addEventListener('change', function () {
            setEditStatus(this.checked ? 'active' : 'inactive');
        });
    }
    const createStatusToggle = document.getElementById('createStatusToggle');
    if (createStatusToggle) {
        createStatusToggle.addEventListener('change', function () {
            setCreateStatus(this.checked ? 'active' : 'inactive');
        });
    }

    const editAiSegmentationFree = document.getElementById('editAiSegmentationFree');
    const editAiSegmentationFreeLabel = document.getElementById('editAiSegmentationFreeLabel');
    if (editAiSegmentationFree && editAiSegmentationFreeLabel) {
        editAiSegmentationFree.addEventListener('change', function () {
            editAiSegmentationFreeLabel.textContent = this.checked ? 'enabled' : 'disabled';
        });
    }

    @if($errors->any() && old('edit_product_id'))
        openEditModal(
            {{ (int) old('edit_product_id') }},
            @json(old('name', '')),
            @json(old('sku', '')),
            @json(old('category', '')),
            @json(old('status', 'active')),
            @json(old('image_url', '')),
            @json(old('ai_prompt', '')),
            @json(old('ai_category', 'auto')),
            @json(old('ai_garment_photo_type', 'auto')),
            @json((int) old('ai_segmentation_free', 1))
        );
    @endif
</script>
</body>
</html>
