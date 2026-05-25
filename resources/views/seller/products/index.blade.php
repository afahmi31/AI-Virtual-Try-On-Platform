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
<body class="products-page">
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
        <section class="products-hero">
            <div>
                <h1>Kelola Produk</h1>
                <p class="products-hero-subtitle">Kelola produk dan konfigrasi FASHN AI untuk setiap produk.</p>
            </div>
            <div class="products-toolbar">
                <form method="GET" action="{{ route('seller.products.index') }}" class="products-toolbar-form">
                    <div class="search-input-wrap">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M21 21l-4.35-4.35"></path>
                            <circle cx="11" cy="11" r="6"></circle>
                        </svg>
                        <input type="text" name="q" value="{{ $search ?? '' }}" placeholder="Cari nama, slug, SKU, atau ID produk...">
                    </div>
                    <details class="filter-popover">
                        <summary class="btn btn-ghost toolbar-btn" aria-label="Filter">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M4 6h16"></path>
                                <path d="M7 12h10"></path>
                                <path d="M10 18h4"></path>
                            </svg>
                            <span>Filter</span>
                        </summary>
                        <div class="filter-popover-panel">
                            <label for="statusFilter">Status</label>
                            <select id="statusFilter" name="status" aria-label="Filter status">
                                <option value="all" @selected(($status ?? 'all') === 'all')>Semua Status</option>
                                <option value="active" @selected(($status ?? 'all') === 'active')>Active</option>
                                <option value="inactive" @selected(($status ?? 'all') === 'inactive')>Inactive</option>
                            </select>
                            <label for="perPageFilter">Per Page</label>
                            <select id="perPageFilter" name="per_page" aria-label="Jumlah data per halaman">
                                <option value="5" @selected(($perPage ?? 20) === 5)>5 / page</option>
                                <option value="10" @selected(($perPage ?? 20) === 10)>10 / page</option>
                                <option value="20" @selected(($perPage ?? 20) === 20)>20 / page</option>
                                <option value="50" @selected(($perPage ?? 20) === 50)>50 / page</option>
                            </select>
                            <label for="aiCategoryFilter">AI Category</label>
                            <select id="aiCategoryFilter" name="ai_category" aria-label="Filter AI category">
                                <option value="all" @selected(($aiCategory ?? 'all') === 'all')>Semua AI Category</option>
                                <option value="auto" @selected(($aiCategory ?? 'all') === 'auto')>auto</option>
                                <option value="tops" @selected(($aiCategory ?? 'all') === 'tops')>tops</option>
                                <option value="bottoms" @selected(($aiCategory ?? 'all') === 'bottoms')>bottoms</option>
                                <option value="one-pieces" @selected(($aiCategory ?? 'all') === 'one-pieces')>one-pieces</option>
                            </select>
                            <label for="photoTypeFilter">Photo Type</label>
                            <select id="photoTypeFilter" name="photo_type" aria-label="Filter photo type">
                                <option value="all" @selected(($photoType ?? 'all') === 'all')>Semua Photo Type</option>
                                <option value="auto" @selected(($photoType ?? 'all') === 'auto')>auto</option>
                                <option value="flat-lay" @selected(($photoType ?? 'all') === 'flat-lay')>flat-lay</option>
                                <option value="model" @selected(($photoType ?? 'all') === 'model')>model</option>
                            </select>
                            <button class="btn btn-primary filter-apply-btn" type="submit">Apply Filter</button>
                        </div>
                    </details>
                </form>
                <button class="btn btn-primary toolbar-add-btn" type="button" onclick="openCreateModal()">+ Tambah Produk Baru</button>
            </div>
        </section>

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

        <section class="panel products-panel">
            <div class="table-wrap">
            <table class="product-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Product</th>
                        <th>Name</th>
                        <th>AI Category</th>
                        <th>Photo Type</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        @php $primaryImage = $product->images->firstWhere('is_primary', true) ?? $product->images->first(); @endphp
                        <tr>
                            <td data-label="ID"><span class="cell-id">#{{ $product->id }}</span></td>
                            <td data-label="Product">
                                @if($primaryImage)
                                    <img src="{{ $primaryImage->image_url }}" alt="{{ $product->name }}" class="thumb">
                                @else
                                    <div class="thumb-fallback" aria-label="No image">IMG</div>
                                @endif
                            </td>
                            <td data-label="Name">
                                <div class="product-name">{{ $product->name }}</div>
                                <div class="product-slug-row">
                                    <code class="slug-chip">{{ $product->slug }}</code>
                                    <a
                                        class="slug-link-icon"
                                        href="{{ route('public.seller.page', ['seller_slug' => $seller->slug, 'product_ref' => $product->slug]) }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        title="Buka produk di halaman store"
                                        aria-label="Buka produk di halaman store"
                                    >
                                        <svg viewBox="0 0 24 24" aria-hidden="true">
                                            <path d="M14 4h6v6"></path>
                                            <path d="M10 14L20 4"></path>
                                            <path d="M20 14v6H4V4h6"></path>
                                        </svg>
                                    </a>
                                </div>
                                @if($product->sku)
                                    <div class="product-meta">SKU: {{ $product->sku }}</div>
                                @endif
                            </td>
                            <td data-label="AI Category">
                                <span class="ai-category-badge">{{ $product->ai_category ?? 'auto' }}</span>
                            </td>
                            <td data-label="Photo Type">
                                <span class="ai-photo-type-badge">{{ $product->ai_garment_photo_type ?? 'auto' }}</span>
                            </td>
                            <td data-label="Status">
                                <span class="status-badge {{ $product->status === 'inactive' ? 'status-inactive' : '' }}">
                                    {{ ucfirst($product->status) }}
                                </span>
                            </td>
                            <td data-label="Actions">
                                <div class="actions">
                                    <button class="btn btn-ghost" type="button" onclick="openEditModal({{ $product->id }}, '{{ addslashes($product->name) }}', '{{ addslashes($product->sku ?? '') }}', '{{ addslashes($product->category ?? '') }}', '{{ $product->status }}', '{{ addslashes($primaryImage?->image_url ?? '') }}', '{{ addslashes($product->ai_prompt ?? '') }}', '{{ addslashes($product->ai_category ?? 'auto') }}', '{{ addslashes($product->ai_garment_photo_type ?? 'auto') }}', '{{ (int) ($product->ai_segmentation_free ?? true) }}')">Edit</button>
                                    <form method="POST" action="{{ route('seller.products.destroy', $product->id) }}" onsubmit="return openDeleteConfirm(event, this);" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-ghost" type="submit" style="color:var(--danger); border-color: rgba(248,113,113,.4);">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7">Belum ada produk.</td></tr>
                    @endforelse
                </tbody>
            </table>
            </div>
            <div class="products-footer-row">
                <div class="products-footer-meta">
                    Menampilkan {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} dari {{ $products->total() }} produk
                </div>
                <div class="pagination-wrap">{{ $products->links() }}</div>
            </div>
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
                    <section class="form-section product-info-section">
                        <div class="form-section-head">
                            <h4>Product Information</h4>
                            <p>Informasi inti produk untuk katalog toko Anda.</p>
                        </div>
                        <div class="field"><label>Product Name</label><input id="createName" name="name" required></div>
                        <div class="field"><label>SKU</label><input name="sku"></div>
                        <div class="field"><label>Category</label><input id="createCategory" name="category" placeholder="Select Category..."></div>
                    </section>
                    <section class="form-section ai-config-section" id="createAiConfigSection">
                        <div class="form-section-head">
                            <div class="section-head-row">
                                <h4>FASHN AI Configuration</h4>
                                <button type="button" class="section-toggle-btn" onclick="toggleAiSection('createAiConfigSection', this)">Collapse</button>
                            </div>
                            <p>Atur metadata AI dengan tepat agar hasil generated try-on lebih akurat.</p>
                            <div class="ai-summary" id="createAiSummary">
                                <span id="createSummaryCategory" class="summary-chip">AI Category: auto</span>
                                <span id="createSummaryPhotoType" class="summary-chip">Garment Photo Type: auto</span>
                                <span id="createSummarySegmentation" class="summary-chip">Segmentation: enabled</span>
                            </div>
                        </div>
                        <div class="field">
                            <label>AI Prompt <span class="preview-hint">(Try-On Max)</span></label>
                            <input name="ai_prompt" placeholder="Opsional, contoh: long modest muslim dress for 12-year-old girl">
                        </div>
                        <div class="field">
                            <label>AI Category <span class="preview-hint">(Try-On v1.6)</span></label>
                            <select id="createAiCategory" name="ai_category">
                                <option value="auto" selected>auto</option>
                                <option value="tops">tops - atasan (kemeja, blouse, t-shirt)</option>
                                <option value="bottoms">bottoms - bawahan (rok, celana)</option>
                                <option value="one-pieces">one-pieces - baju terusan (dress, gamis)</option>
                            </select>
                            <div class="preview-hint">Pilih sesuai jenis utama pakaian pada foto produk agar hasil try-on lebih pas.</div>
                        </div>
                        <div class="field">
                            <label>Garment Photo Type <span class="preview-hint">(Try-On v1.6)</span></label>
                            <select id="createAiGarmentPhotoType" name="ai_garment_photo_type">
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
                                <input id="createAiSegmentationFree" type="checkbox" name="ai_segmentation_free" value="1" checked>
                                <span class="status-toggle-switch"></span>
                                <span class="status-toggle-label">enabled</span>
                            </label>
                            <div class="preview-hint">Aktifkan untuk membiarkan AI memproses tanpa segmentasi ketat garment, cocok untuk banyak foto katalog umum.</div>
                        </div>
                    </section>
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
                    <section class="form-section product-info-section">
                        <div class="form-section-head">
                            <h4>Product Information</h4>
                            <p>Perbarui informasi utama produk yang tampil di katalog.</p>
                        </div>
                        <div class="field"><label>Product Name</label><input id="editName" name="name" required></div>
                        <div class="field"><label>SKU</label><input id="editSku" name="sku"></div>
                        <div class="field"><label>Category</label><input id="editCategory" name="category"></div>
                    </section>
                    <section class="form-section ai-config-section" id="editAiConfigSection">
                        <div class="form-section-head">
                            <div class="section-head-row">
                                <h4>FASHN AI Configuration</h4>
                                <button type="button" class="section-toggle-btn" onclick="toggleAiSection('editAiConfigSection', this)">Collapse</button>
                            </div>
                            <p>Optimalkan parameter AI saat generated try-on.</p>
                            <div class="ai-summary" id="editAiSummary">
                                <span id="editSummaryCategory" class="summary-chip">AI Category: auto</span>
                                <span id="editSummaryPhotoType" class="summary-chip">Garment Photo Type: auto</span>
                                <span id="editSummarySegmentation" class="summary-chip">Segmentation: enabled</span>
                            </div>
                        </div>
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
                    </section>
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

<div id="deleteConfirmModal" class="modal delete-confirm-modal" onclick="closeOnBackdrop(event, 'deleteConfirmModal')">
    <div class="modal-card delete-confirm-card" role="dialog" aria-modal="true" aria-labelledby="deleteConfirmTitle" aria-describedby="deleteConfirmMessage">
        <div class="delete-confirm-icon" aria-hidden="true">!</div>
        <h3 id="deleteConfirmTitle">Delete Product</h3>
        <p id="deleteConfirmMessage">Produk yang dihapus tidak dapat dikembalikan. Lanjutkan hapus produk ini?</p>
        <div class="delete-confirm-actions">
            <button id="deleteConfirmCancelBtn" class="btn btn-cancel" type="button" onclick="closeDeleteConfirm()">Cancel</button>
            <button class="btn btn-danger-solid" type="button" onclick="submitDeleteConfirm()">Delete Product</button>
        </div>
    </div>
</div>

<script>
    let pendingDeleteForm = null;

    function openModal(id) { document.getElementById(id).classList.add('active'); }
    function closeModal(id) { document.getElementById(id).classList.remove('active'); }
    function closeOnBackdrop(event, id) { if (event.target.id === id) closeModal(id); }
    function openCreateModal() {
        resetPreview('createImageFile', 'createImageUrl', 'createImagePreview');
        setCreateStatus('active');
        expandAiSection('createAiConfigSection');
        updateAiSummary('create');
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
        expandAiSection('editAiConfigSection');
        updateAiSummary('edit');
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

    function openDeleteConfirm(event, form) {
        if (event) event.preventDefault();
        pendingDeleteForm = form || null;
        openModal('deleteConfirmModal');
        const cancelBtn = document.getElementById('deleteConfirmCancelBtn');
        if (cancelBtn) cancelBtn.focus();
        return false;
    }

    function closeDeleteConfirm() {
        pendingDeleteForm = null;
        closeModal('deleteConfirmModal');
    }

    function submitDeleteConfirm() {
        const form = pendingDeleteForm;
        pendingDeleteForm = null;
        closeModal('deleteConfirmModal');
        if (form) form.submit();
    }

    function toggleAiSection(sectionId, triggerBtn) {
        const section = document.getElementById(sectionId);
        if (!section) return;
        section.classList.toggle('collapsed');
        if (triggerBtn) {
            triggerBtn.textContent = section.classList.contains('collapsed') ? 'Expand' : 'Collapse';
        }
    }

    function expandAiSection(sectionId) {
        const section = document.getElementById(sectionId);
        if (!section) return;
        section.classList.remove('collapsed');
        const btn = section.querySelector('.section-toggle-btn');
        if (btn) btn.textContent = 'Collapse';
    }
    function updateAiSummary(prefix) {
        const aiCategory = document.getElementById(prefix === 'create' ? 'createAiCategory' : 'editAiCategory');
        const photoType = document.getElementById(prefix === 'create' ? 'createAiGarmentPhotoType' : 'editAiGarmentPhotoType');
        const segmentation = document.getElementById(prefix === 'create' ? 'createAiSegmentationFree' : 'editAiSegmentationFree');
        const summaryCategory = document.getElementById(prefix === 'create' ? 'createSummaryCategory' : 'editSummaryCategory');
        const summaryPhotoType = document.getElementById(prefix === 'create' ? 'createSummaryPhotoType' : 'editSummaryPhotoType');
        const summarySegmentation = document.getElementById(prefix === 'create' ? 'createSummarySegmentation' : 'editSummarySegmentation');

        if (summaryCategory && aiCategory) summaryCategory.textContent = `AI Category: ${aiCategory.value || 'auto'}`;
        if (summaryPhotoType && photoType) summaryPhotoType.textContent = `Garment Photo Type: ${photoType.value || 'auto'}`;
        if (summarySegmentation && segmentation) summarySegmentation.textContent = `Segmentation: ${segmentation.checked ? 'enabled' : 'disabled'}`;
    }

    bindImagePreview('createImageFile', 'createImageUrl', 'createImagePreview');
    bindImagePreview('editImageFile', 'editImageUrl', 'editImagePreview');

    const createAiCategory = document.getElementById('createAiCategory');
    const createAiGarmentPhotoType = document.getElementById('createAiGarmentPhotoType');
    const createAiSegmentationFree = document.getElementById('createAiSegmentationFree');
    const editAiCategory = document.getElementById('editAiCategory');
    const editAiGarmentPhotoType = document.getElementById('editAiGarmentPhotoType');
    if (createAiCategory) createAiCategory.addEventListener('change', function () { updateAiSummary('create'); });
    if (createAiGarmentPhotoType) createAiGarmentPhotoType.addEventListener('change', function () { updateAiSummary('create'); });
    if (createAiSegmentationFree) createAiSegmentationFree.addEventListener('change', function () { updateAiSummary('create'); });
    if (editAiCategory) editAiCategory.addEventListener('change', function () { updateAiSummary('edit'); });
    if (editAiGarmentPhotoType) editAiGarmentPhotoType.addEventListener('change', function () { updateAiSummary('edit'); });

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
            updateAiSummary('edit');
        });
    }

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            const deleteConfirm = document.getElementById('deleteConfirmModal');
            if (deleteConfirm && deleteConfirm.classList.contains('active')) {
                closeDeleteConfirm();
            }
        }
    });

    document.addEventListener('click', function (event) {
        const openPopovers = document.querySelectorAll('.filter-popover[open]');
        openPopovers.forEach(function (popover) {
            if (!popover.contains(event.target)) {
                popover.removeAttribute('open');
            }
        });
    });

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
