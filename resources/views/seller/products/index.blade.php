<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products - AI Try-On Core App</title>
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
            --overlay: rgba(2, 7, 14, 0.72);
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
        .topbar { height: 74px; padding: 0 24px; border-bottom: 1px solid rgba(120,170,255,.25); background: linear-gradient(90deg,#0b162f,#0a1b3d); display:flex; align-items:center; justify-content:space-between; }
        .brand { font-size: 32px; font-weight: 700; display:flex; gap:12px; align-items:center; }
        .brand-dot { width:36px; height:36px; border-radius:10px; background:rgba(34,211,238,.15); display:inline-flex; align-items:center; justify-content:center; color:var(--primary); }
        .topnav { display:flex; gap:16px; align-items:center; }
        .store-logo-link {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-size: 16px;
            font-weight: 700;
            color: #052a31;
            background: linear-gradient(160deg, #3b82f6, #22d3ee);
            box-shadow: 0 0 16px rgba(34,211,238,.28);
        }
        .topnav button { color: var(--text); border:1px solid transparent; padding:10px 14px; border-radius:10px; background:rgba(255,255,255,.04); font-size:24px; cursor:pointer; }

        .layout { display:grid; grid-template-columns: 280px 1fr; min-height: calc(100vh - 74px); }
        .sidebar { border-right:1px solid rgba(120,170,255,.2); background:linear-gradient(180deg, rgba(11,18,32,.9), rgba(8,14,24,.95)); padding:18px; }
        .menu-item { display:flex; align-items:center; color:var(--muted); text-decoration:none; padding:14px 16px; border-radius:10px; margin-bottom:10px; font-size:28px; }
        .menu-item.active { color:var(--primary); background:rgba(34,211,238,.12); border:1px solid rgba(34,211,238,.3); }

        .content { padding: 26px; }
        h1 { font-size: 44px; margin: 0 0 20px; }

        .panel { background: var(--panel); border:1px solid var(--panel-border); border-radius:14px; padding:18px; margin-bottom:18px; box-shadow: inset 0 0 32px rgba(56,189,248,.08), 0 8px 28px rgba(0,0,0,.35); }
        .panel h2 { font-size: 36px; margin: 0; }
        .panel-head { display:flex; align-items:center; justify-content:space-between; margin-bottom: 14px; }

        .form-grid { display:grid; grid-template-columns: repeat(3,minmax(0,1fr)); gap: 14px 18px; }
        .field { display:flex; flex-direction:column; }
        label { font-size:22px; color:#cad7ea; margin-bottom:6px; }
        input, select { width:100%; height:52px; border-radius:10px; border:1px solid rgba(54,198,230,.45); background:rgba(6,14,26,.65); color:var(--text); padding:0 12px; font-size:24px; }
        .form-actions { margin-top:16px; }

        .btn { border:none; border-radius:12px; padding:12px 20px; font-size:22px; cursor:pointer; }
        .btn-primary { background:linear-gradient(180deg,#35e5ef,#1ac6d7); color:#052a31; font-weight:700; box-shadow:0 0 24px rgba(34,211,238,.4); }
        .btn-ghost { background:rgba(34,211,238,.14); color:var(--primary); border:1px solid rgba(34,211,238,.35); }

        table { width:100%; border-collapse: collapse; font-size:22px; }
        th, td { padding:12px 10px; border-bottom:1px solid rgba(130,170,230,.18); text-align:left; vertical-align:middle; }
        th { color:#b9c7da; font-weight:600; font-size:20px; background:rgba(255,255,255,.03); }
        .status-badge { display:inline-block; padding:4px 10px; border-radius:999px; font-size:18px; border:1px solid rgba(45,212,191,.45); color:var(--success); background:rgba(45,212,191,.12); }
        .status-inactive { color:#f9c74f; border-color:rgba(249,199,79,.45); background:rgba(249,199,79,.15); }
        .actions { display:flex; gap:8px; flex-wrap:wrap; }
        .thumb { width:56px; height:56px; border-radius:8px; object-fit:cover; border:1px solid rgba(130,170,230,.3); background:rgba(255,255,255,.05); }
        .thumb-fallback { width:56px; height:56px; border-radius:8px; border:1px solid rgba(130,170,230,.3); background:rgba(255,255,255,.04); display:flex; align-items:center; justify-content:center; color:#8fb2d9; font-size:28px; }

        .flash { margin-bottom:14px; padding:12px 14px; border-radius:10px; font-size:22px; }
        .flash-success { border:1px solid rgba(45,212,191,.45); color:#78f6dc; background:rgba(45,212,191,.12); }
        .flash-error { border:1px solid rgba(248,113,113,.45); color:#fecaca; background:rgba(248,113,113,.13); }

        .modal { position:fixed; inset:0; background:var(--overlay); display:none; align-items:center; justify-content:center; z-index:1000; padding:16px; }
        .modal.active { display:flex; }
        .modal-card { width:min(980px,100%); background:var(--panel); border:1px solid var(--panel-border); border-radius:14px; box-shadow:0 10px 28px rgba(0,0,0,.45); padding:18px; }
        .modal-head { display:flex; justify-content:space-between; align-items:center; margin-bottom:14px; }
        .modal-title { font-size:32px; margin:0; }
        .close-btn { background:transparent; color:var(--muted); border:1px solid rgba(157,176,200,.3); border-radius:10px; padding:8px 12px; font-size:18px; cursor:pointer; }
        .modal-actions { margin-top:16px; display:flex; gap:10px; justify-content:flex-end; }
        .preview-wrap { margin-top: 8px; }
        .preview-img { width: 96px; height: 96px; border-radius: 10px; border: 1px solid rgba(130,170,230,.35); object-fit: cover; background: rgba(255,255,255,.04); display: none; }
        .preview-hint { margin-top: 6px; color: var(--muted); font-size: 16px; }

        @media (max-width: 1400px) {
            .layout { grid-template-columns: 96px 1fr; }
            .menu-item span { display:none; }
            .form-grid { grid-template-columns: 1fr; }
            .brand { font-size:22px; }
            .topnav a, .topnav button { font-size:16px; }
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
        <a class="menu-item" href="{{ route('seller.dashboard') }}"><span>Settings</span></a>
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
            <div style="margin-top:12px;">{{ $products->links() }}</div>
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
                <div class="field"><label>SKU</label><input name="sku"></div>
                <div class="field"><label>Status</label><select name="status"><option value="active">active</option><option value="inactive">inactive</option></select></div>
                <div class="field"><label>Category</label><input name="category"></div>
                <div class="field">
                    <label>Upload File</label>
                    <input id="createImageFile" type="file" name="image" accept="image/*">
                </div>
                <div class="field">
                    <label>Or Public URL</label>
                    <input id="createImageUrl" type="url" name="image_url" placeholder="https://...">
                </div>
                <div class="field">
                    <label>Image Preview</label>
                    <div class="preview-wrap">
                        <img id="createImagePreview" class="preview-img" alt="Create preview">
                    </div>
                    <div class="preview-hint">Pilih file atau isi URL untuk melihat preview.</div>
                </div>
            </div>
            <div class="modal-actions">
                <button class="btn btn-ghost" type="button" onclick="closeModal('createModal')">Cancel</button>
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
                <div class="field"><label>SKU</label><input id="editSku" name="sku"></div>
                <div class="field"><label>Status</label><select id="editStatus" name="status"><option value="active">active</option><option value="inactive">inactive</option></select></div>
                <div class="field"><label>Category</label><input id="editCategory" name="category"></div>
                <div class="field">
                    <label>Replace Image File (Optional)</label>
                    <input id="editImageFile" type="file" name="image" accept="image/*">
                </div>
                <div class="field">
                    <label>Or Replace with Public URL (Optional)</label>
                    <input id="editImageUrl" type="url" name="image_url" placeholder="https://...">
                </div>
                <div class="field">
                    <label>Image Preview</label>
                    <div class="preview-wrap">
                        <img id="editImagePreview" class="preview-img" alt="Edit preview">
                    </div>
                    <div class="preview-hint">Pilih file baru atau isi URL baru untuk preview.</div>
                </div>
            </div>
            <div class="modal-actions">
                <button class="btn btn-ghost" type="button" onclick="closeModal('editModal')">Cancel</button>
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
