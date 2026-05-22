<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products - AI Try-On Core App</title>
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
            font-size: 16px;
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
            font-size: 44px;
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
            font-size: 31px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 15px;
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
            font-size: 13px;
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
            font-size: 12px;
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
            font-size: 12px;
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
            font-size: 13px;
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
            font-size: 14px;
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
            width: min(760px, 100%);
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

        .modal-title {
            margin: 0;
            font-size: 42px;
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
            font-size: 12px;
            cursor: pointer;
        }

        .close-btn:hover {
            color: #e5efff;
            border-color: rgba(49, 217, 241, 0.38);
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px 14px;
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
            font-size: 13px;
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
            font-size: 13px;
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
            min-height: 148px;
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
            font-size: 14px;
            text-align: center;
            padding: 10px;
        }

        .preview-hint {
            margin-top: 6px;
            color: var(--muted);
            font-size: 12px;
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
            font-size: 12px;
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
            font-size: 12px;
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
                font-size: 30px;
            }

            .panel h2 {
                font-size: 24px;
            }

            .panel-head {
                flex-direction: column;
                align-items: flex-start;
            }

            .modal-title {
                font-size: 30px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .field.preview-field {
                grid-column: auto;
                grid-row: auto;
            }

            .preview-wrap {
                min-height: 170px;
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
                                    <button class="btn btn-ghost" type="button" onclick="openEditModal({{ $product->id }}, '{{ addslashes($product->name) }}', '{{ addslashes($product->sku ?? '') }}', '{{ addslashes($product->category ?? '') }}', '{{ $product->status }}', '{{ addslashes($primaryImage?->image_url ?? '') }}')">Edit</button>
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
            <button class="close-btn" type="button" onclick="closeModal('createModal')">Close</button>
        </div>
        <form method="POST" action="{{ route('seller.products.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-grid">
                <div class="field"><label>Product Name</label><input name="name" required></div>
                <div class="field"><label>Status</label><select name="status"><option value="active">active</option><option value="inactive">inactive</option></select></div>
                <div class="field"><label>SKU</label><input name="sku"></div>
                <div class="field"><label>Media</label><input id="createImageFile" type="file" name="image" accept="image/*"></div>
                <div class="field"><label>Category</label><input name="category" placeholder="Select Category..."></div>
                <div class="field"><label>Or Public URL</label><input id="createImageUrl" type="url" name="image_url" placeholder="https://..."></div>
                <div class="field preview-field">
                    <label>Image Preview</label>
                    <div class="preview-wrap">
                        <img id="createImagePreview" class="preview-img" alt="Create preview">
                        <span class="preview-placeholder">Image will appear here</span>
                    </div>
                    <div class="preview-hint">Pilih file atau isi URL untuk melihat preview.</div>
                </div>
            </div>
            <div class="modal-actions">
                <button class="btn btn-cancel" type="button" onclick="closeModal('createModal')">Cancel</button>
                <button class="btn btn-primary" type="submit">Create Product</button>
            </div>
        </form>
    </div>
</div>

<div id="editModal" class="modal" onclick="closeOnBackdrop(event, 'editModal')">
    <div class="modal-card">
        <div class="modal-head">
            <h3 class="modal-title">Edit Product</h3>
            <button class="close-btn" type="button" onclick="closeModal('editModal')">Close</button>
        </div>
        <form id="editForm" method="POST" action="" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            <input id="editProductId" type="hidden" name="edit_product_id" value="">
            <div class="form-grid">
                <div class="field"><label>Product Name</label><input id="editName" name="name" required></div>
                <div class="field"><label>Status</label><select id="editStatus" name="status"><option value="active">active</option><option value="inactive">inactive</option></select></div>
                <div class="field"><label>SKU</label><input id="editSku" name="sku"></div>
                <div class="field"><label>Replace Image File</label><input id="editImageFile" type="file" name="image" accept="image/*"></div>
                <div class="field"><label>Category</label><input id="editCategory" name="category"></div>
                <div class="field"><label>Or Replace with Public URL</label><input id="editImageUrl" type="url" name="image_url" placeholder="https://..."></div>
                <div class="field preview-field">
                    <label>Image Preview</label>
                    <div class="preview-wrap">
                        <img id="editImagePreview" class="preview-img" alt="Edit preview">
                        <span class="preview-placeholder">Image will appear here</span>
                    </div>
                    <div class="preview-hint">Pilih file baru atau isi URL baru untuk preview.</div>
                </div>
            </div>
            <div class="modal-actions">
                <button class="btn btn-cancel" type="button" onclick="closeModal('editModal')">Cancel</button>
                <button class="btn btn-primary" type="submit">Save</button>
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
        openModal('createModal');
    }

    function openEditModal(id, name, sku, category, status, imageUrl) {
        const form = document.getElementById('editForm');
        form.action = `/dashboard/products/${id}`;
        document.getElementById('editProductId').value = id;
        document.getElementById('editName').value = name;
        document.getElementById('editSku').value = sku;
        document.getElementById('editCategory').value = category;
        document.getElementById('editStatus').value = status;
        resetPreview('editImageFile', 'editImageUrl', 'editImagePreview');
        setPreviewFromUrl('editImagePreview', imageUrl);
        openModal('editModal');
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

    @if($errors->any() && old('edit_product_id'))
        openEditModal(
            {{ (int) old('edit_product_id') }},
            @json(old('name', '')),
            @json(old('sku', '')),
            @json(old('category', '')),
            @json(old('status', 'active')),
            @json(old('image_url', ''))
        );
    @endif
</script>
</body>
</html>
