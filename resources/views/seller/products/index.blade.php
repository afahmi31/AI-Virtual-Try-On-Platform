<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products - Try-On Commerce Studio</title>
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
                            <option value="auto" selected>auto</option>
                            <option value="tops">tops - atasan (kemeja, blouse, t-shirt)</option>
                            <option value="bottoms">bottoms - bawahan (rok, celana)</option>
                            <option value="one-pieces">one-pieces - baju terusan (dress, gamis)</option>
                        </select>
                        <div class="preview-hint">Pilih sesuai jenis utama pakaian pada foto produk agar hasil try-on lebih pas.</div>
                    </div>
                    <div class="field">
                        <label>Garment Photo Type <span class="preview-hint">(Try-On v1.6)</span></label>
                        <select name="ai_garment_photo_type">
                            <option value="auto" selected>auto</option>
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
                        <div class="preview-hint">Aktifkan untuk membiarkan AI memproses tanpa segmentasi ketat garment, cocok untuk banyak foto katalog umum.</div>
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
                            <option value="auto">auto</option>
                            <option value="tops">tops - atasan (kemeja, blouse, t-shirt)</option>
                            <option value="bottoms">bottoms - bawahan (rok, celana)</option>
                            <option value="one-pieces">one-pieces - baju terusan (dress, gamis)</option>
                        </select>
                        <div class="preview-hint">Pilih sesuai jenis utama pakaian pada foto produk agar hasil try-on lebih pas.</div>
                    </div>
                    <div class="field">
                        <label>Garment Photo Type <span class="preview-hint">(Try-On v1.6)</span></label>
                        <select id="editAiGarmentPhotoType" name="ai_garment_photo_type">
                            <option value="auto">auto</option>
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
                        <div class="preview-hint">Aktifkan untuk membiarkan AI memproses tanpa segmentasi ketat garment, cocok untuk banyak foto katalog umum.</div>
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


