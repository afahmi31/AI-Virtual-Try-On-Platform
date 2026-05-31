<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('ui.product_requests_page.title') }} - Try-On Commerce Studio</title>
    @php
        $requestsFavicon = trim((string) ($seller->seo_logo_url ?? ''));
        $requestsFaviconVersion = (string) ($seller->updated_at?->timestamp ?? time());
    @endphp
    @if($requestsFavicon !== '')
        <link rel="icon" type="image/png" href="{{ $requestsFavicon }}?v={{ urlencode($requestsFaviconVersion) }}">
        <link rel="shortcut icon" href="{{ $requestsFavicon }}?v={{ urlencode($requestsFaviconVersion) }}">
        <link rel="apple-touch-icon" href="{{ $requestsFavicon }}?v={{ urlencode($requestsFaviconVersion) }}">
    @endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;500;600;700&family=Inter:wght@500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/seller-theme.css') }}">
</head>
<body class="product-requests-page">
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
            <button type="submit">{{ __('ui.common.logout') }}</button>
        </form>
    </nav>
</header>

<div class="layout">
    <aside class="sidebar">
        <a class="menu-item" href="{{ route('seller.dashboard') }}"><span>{{ __('ui.common.dashboard') }}</span></a>
        <a class="menu-item" href="{{ route('seller.products.index') }}"><span>{{ __('ui.common.products') }}</span></a>
        <a class="menu-item active" href="{{ route('seller.product-requests.index') }}">
            <span>{{ __('ui.common.product_requests') }}</span>
            @if(($newProductRequestCount ?? 0) > 0)
                <span class="menu-badge">{{ $newProductRequestCount }}</span>
            @endif
        </a>
        <a class="menu-item" href="{{ route('seller.settings.index') }}"><span>{{ __('ui.common.settings') }}</span></a>
    </aside>

    <main class="content">
        <header class="requests-hero">
            <h1>{{ __('ui.product_requests_page.title') }}</h1>
            <p class="requests-subtitle">{{ __('ui.product_requests_page.subtitle') }}</p>
        </header>

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

        <section class="panel requests-panel">
            <div class="panel-head requests-head">
                <h2>{{ __('ui.product_requests_page.title') }}</h2>
                <form method="GET" action="{{ route('seller.product-requests.index') }}" class="requests-filter-form">
                    <label for="requestStatusFilter">{{ __('ui.product_requests_page.filter_status') }}</label>
                    <select id="requestStatusFilter" name="status">
                        <option value="all" {{ $status === 'all' ? 'selected' : '' }}>{{ __('ui.product_requests_page.status_all') }}</option>
                        <option value="new" {{ $status === 'new' ? 'selected' : '' }}>{{ __('ui.product_requests_page.status_new') }}</option>
                        <option value="not_added" {{ $status === 'not_added' ? 'selected' : '' }}>{{ __('ui.product_requests_page.status_not_added') }}</option>
                        <option value="added" {{ $status === 'added' ? 'selected' : '' }}>{{ __('ui.product_requests_page.status_added') }}</option>
                    </select>
                    <button type="submit" class="btn btn-primary">{{ __('ui.store.apply') }}</button>
                </form>
            </div>

            <div class="table-wrap">
                <table class="requests-table">
                    <thead>
                        <tr>
                            <th>{{ __('ui.product_requests_page.table_id') }}</th>
                            <th>{{ __('ui.product_requests_page.table_url') }}</th>
                            <th>{{ __('ui.product_requests_page.table_status') }}</th>
                            <th>{{ __('ui.product_requests_page.table_linked_product') }}</th>
                            <th>{{ __('ui.product_requests_page.table_requested_at') }}</th>
                            <th>{{ __('ui.product_requests_page.table_reviewed_at') }}</th>
                            <th>{{ __('ui.product_requests_page.table_actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $requestItem)
                            @php
                                $rawStatus = strtolower(trim((string) $requestItem->status));
                                $normalizedStatus = in_array($rawStatus, ['new', 'not_added', 'added'], true) ? $rawStatus : 'new';
                                $isOldRow = (int) old('request_id', 0) === (int) $requestItem->id;
                                $selectedStatus = $isOldRow ? old('status', $normalizedStatus) : $normalizedStatus;
                                $currentLinkedProductId = $isOldRow
                                    ? old('linked_product_id', $requestItem->linked_product_id)
                                    : $requestItem->linked_product_id;
                                $statusBadgeClass = match ($normalizedStatus) {
                                    'added' => 'requests-status-added',
                                    'not_added' => 'requests-status-not-added',
                                    default => 'requests-status-new',
                                };
                            @endphp
                            <tr>
                                <td data-label="{{ __('ui.product_requests_page.table_id') }}">
                                    <span class="requests-id">#{{ $requestItem->id }}</span>
                                </td>
                                <td data-label="{{ __('ui.product_requests_page.table_url') }}" class="requests-url-cell">
                                    <a href="{{ $requestItem->shopee_product_url }}" target="_blank" rel="noopener noreferrer" class="requests-url-link">
                                        {{ $requestItem->shopee_product_url }}
                                    </a>
                                </td>
                                <td data-label="{{ __('ui.product_requests_page.table_status') }}">
                                    <span class="status-badge {{ $statusBadgeClass }}">
                                        @if($normalizedStatus === 'added')
                                            {{ __('ui.product_requests_page.status_added') }}
                                        @elseif($normalizedStatus === 'not_added')
                                            {{ __('ui.product_requests_page.status_not_added') }}
                                        @else
                                            {{ __('ui.product_requests_page.status_new') }}
                                        @endif
                                    </span>
                                </td>
                                <td data-label="{{ __('ui.product_requests_page.table_linked_product') }}">
                                    @if($requestItem->linkedProduct)
                                        <div class="requests-linked-product">
                                            <div>{{ $requestItem->linkedProduct->name }}</div>
                                            <a href="{{ route('public.seller.page', ['seller_slug' => $seller->slug, 'product_ref' => $requestItem->linkedProduct->slug]) }}" target="_blank" rel="noopener noreferrer">
                                                {{ $requestItem->linkedProduct->slug }}
                                            </a>
                                        </div>
                                    @else
                                        <span class="requests-linked-empty">{{ __('ui.product_requests_page.linked_product_empty') }}</span>
                                    @endif
                                </td>
                                <td data-label="{{ __('ui.product_requests_page.table_requested_at') }}">
                                    {{ optional($requestItem->created_at)->format('Y-m-d H:i:s') ?? '-' }}
                                </td>
                                <td data-label="{{ __('ui.product_requests_page.table_reviewed_at') }}">
                                    {{ optional($requestItem->reviewed_at)->format('Y-m-d H:i:s') ?? '-' }}
                                </td>
                                <td data-label="{{ __('ui.product_requests_page.table_actions') }}">
                                    <form method="POST" action="{{ route('seller.product-requests.update-status', ['requestId' => $requestItem->id]) }}" class="requests-action-form">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="request_id" value="{{ $requestItem->id }}">
                                        <input type="hidden" name="linked_product_id" value="{{ (string) $currentLinkedProductId }}">
                                        <select name="status" class="requests-status-select" onchange="submitStatusForm(this.form)">
                                            <option value="new" {{ $selectedStatus === 'new' ? 'selected' : '' }}>{{ __('ui.product_requests_page.status_new') }}</option>
                                            <option value="not_added" {{ $selectedStatus === 'not_added' ? 'selected' : '' }}>{{ __('ui.product_requests_page.status_not_added') }}</option>
                                            <option value="added" {{ $selectedStatus === 'added' ? 'selected' : '' }}>{{ __('ui.product_requests_page.status_added') }}</option>
                                        </select>
                                        <button type="button" class="btn btn-secondary requests-add-product-btn" onclick="openCreateModal()">
                                            + {{ __('ui.product_requests_page.add_product_button') }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">{{ __('ui.product_requests_page.empty') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($requests->hasPages())
                <div class="requests-footer-row">
                    <div class="pagination-wrap">{{ $requests->onEachSide(1)->links() }}</div>
                </div>
            @endif
        </section>
    </main>
</div>

<div id="createModal" class="modal" onclick="closeOnBackdrop(event, 'createModal')">
    <div class="modal-card">
        <div class="modal-head">
            <h3 class="modal-title">{{ __('ui.products_page.create_title') }}</h3>
            <div class="modal-head-right">
                <label class="status-toggle">
                    <span>{{ __('ui.products_page.status') }}</span>
                    <input id="createStatusToggle" type="checkbox" {{ old('status', 'active') === 'active' ? 'checked' : '' }}>
                    <span class="status-toggle-switch"></span>
                    <span id="createStatusLabel" class="status-toggle-label">{{ old('status', 'active') === 'active' ? __('ui.common.active') : __('ui.common.inactive') }}</span>
                </label>
                <button class="close-btn" type="button" onclick="closeModal('createModal')">{{ __('ui.common.close') }}</button>
            </div>
        </div>
        <form method="POST" action="{{ route('seller.products.store') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="from_product_requests" value="1">
            <input id="createStatus" type="hidden" name="status" value="{{ old('status', 'active') }}">
            <div class="product-form-grid">
                <div class="product-left-col">
                    <div class="preview-wrap">
                        <img id="createImagePreview" class="preview-img" alt="Create preview">
                        <button class="preview-change-overlay" type="button" onclick="document.getElementById('createImageFile').click()">{{ __('ui.products_page.change_image') }}</button>
                    </div>
                    <input id="createImageFile" type="file" name="image" accept="image/*" style="display:none;">
                    <div class="url-label">{{ __('ui.products_page.replace_public_url') }}</div>
                    <input id="createImageUrl" type="url" name="image_url" placeholder="https://..." value="{{ old('image_url') }}">
                </div>
                <div class="product-right-col">
                    <section class="form-section product-info-section">
                        <div class="form-section-head">
                            <h4>{{ __('ui.products_page.product_info_title') }}</h4>
                            <p>{{ __('ui.products_page.product_info_create_help') }}</p>
                        </div>
                        <div class="field"><label>{{ __('ui.products_page.product_name') }}</label><input id="createName" name="name" value="{{ old('name') }}" required></div>
                        <div class="field"><label>SKU</label><input name="sku" value="{{ old('sku') }}"></div>
                        <div class="field"><label>{{ __('ui.products_page.category') }}</label><input id="createCategory" name="category" placeholder="{{ __('ui.products_page.category_placeholder') }}" value="{{ old('category') }}"></div>
                        <div class="field"><label>Link Produk</label><input type="url" name="product_link_url" placeholder="https://..." value="{{ old('product_link_url') }}"></div>
                    </section>
                    <section class="form-section ai-config-section" id="createAiConfigSection">
                        <div class="form-section-head">
                            <div class="section-head-row">
                                <h4>{{ __('ui.products_page.ai_config_title') }}</h4>
                                <button type="button" class="section-toggle-btn" onclick="toggleAiSection('createAiConfigSection', this)">{{ __('ui.common.collapse') }}</button>
                            </div>
                            <p>{{ __('ui.products_page.ai_config_create_help') }}</p>
                            <div class="ai-summary" id="createAiSummary">
                                <span id="createSummaryCategory" class="summary-chip">AI Category: auto</span>
                                <span id="createSummaryPhotoType" class="summary-chip">Garment Photo Type: auto</span>
                                <span id="createSummarySegmentation" class="summary-chip">Segmentation: enabled</span>
                            </div>
                        </div>
                        <div class="field">
                            <label>AI Prompt <span class="preview-hint">(Try-On Max)</span></label>
                            <input name="ai_prompt" placeholder="{{ __('ui.products_page.ai_prompt_placeholder') }}" value="{{ old('ai_prompt') }}">
                        </div>
                        <div class="field">
                            <label>AI Category <span class="preview-hint">(Try-On v1.6)</span></label>
                            <input id="createAiCategory" type="hidden" name="ai_category" value="{{ old('ai_category', 'auto') }}">
                            <div class="pill-group" role="radiogroup" aria-label="AI Category">
                                <button type="button" class="pill-option" data-value="auto" onclick="setAiOption('create', 'category', 'auto')">auto</button>
                                <button type="button" class="pill-option" data-value="tops" onclick="setAiOption('create', 'category', 'tops')">tops</button>
                                <button type="button" class="pill-option" data-value="bottoms" onclick="setAiOption('create', 'category', 'bottoms')">bottoms</button>
                                <button type="button" class="pill-option" data-value="one-pieces" onclick="setAiOption('create', 'category', 'one-pieces')">one-pieces</button>
                            </div>
                            <div class="preview-hint">{{ __('ui.products_page.ai_category_hint') }}</div>
                        </div>
                        <div class="field">
                            <label>Garment Photo Type <span class="preview-hint">(Try-On v1.6)</span></label>
                            <input id="createAiGarmentPhotoType" type="hidden" name="ai_garment_photo_type" value="{{ old('ai_garment_photo_type', 'auto') }}">
                            <div class="pill-group" role="radiogroup" aria-label="Photo Type">
                                <button type="button" class="pill-option" data-value="auto" onclick="setAiOption('create', 'photoType', 'auto')">auto</button>
                                <button type="button" class="pill-option" data-value="flat-lay" onclick="setAiOption('create', 'photoType', 'flat-lay')">flat-lay</button>
                                <button type="button" class="pill-option" data-value="model" onclick="setAiOption('create', 'photoType', 'model')">model</button>
                            </div>
                            <div class="preview-hint">{{ __('ui.products_page.photo_type_hint') }}</div>
                        </div>
                        <div class="field">
                            <input type="hidden" name="ai_segmentation_free" value="0">
                            <label class="status-toggle" style="justify-content:flex-start;">
                                <span>Segmentation Free <span class="preview-hint">(Try-On v1.6)</span></span>
                                <input id="createAiSegmentationFree" type="checkbox" name="ai_segmentation_free" value="1" {{ old('ai_segmentation_free', 1) ? 'checked' : '' }}>
                                <span class="status-toggle-switch"></span>
                                <span id="createAiSegmentationFreeLabel" class="status-toggle-label">{{ old('ai_segmentation_free', 1) ? __('ui.common.enabled') : __('ui.common.disabled') }}</span>
                            </label>
                            <div class="preview-hint">{{ __('ui.products_page.segmentation_hint') }}</div>
                        </div>
                    </section>
                </div>
            </div>
            <div class="modal-bottom-row">
                <div class="modal-actions" style="margin-top:0; margin-left:auto;">
                    <button class="btn btn-cancel" type="button" onclick="closeModal('createModal')">{{ __('ui.common.cancel') }}</button>
                    <button class="btn btn-primary" type="submit">{{ __('ui.products_page.create_action') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    const I18N_PRODUCTS = {
        active: @json(__('ui.common.active')),
        inactive: @json(__('ui.common.inactive')),
        collapse: @json(__('ui.common.collapse')),
        expand: @json(__('ui.common.expand')),
        enabled: @json(__('ui.common.enabled')),
        disabled: @json(__('ui.common.disabled')),
        summaryAiCategory: @json(__('ui.products_page.summary_ai_category')),
        summaryPhotoType: @json(__('ui.products_page.summary_photo_type')),
        summarySegmentation: @json(__('ui.products_page.summary_segmentation')),
    };

    function submitStatusForm(form) {
        if (!form) {
            return;
        }

        if (typeof form.requestSubmit === 'function') {
            form.requestSubmit();
            return;
        }

        form.submit();
    }

    function openModal(id) { document.getElementById(id).classList.add('active'); }
    function closeModal(id) { document.getElementById(id).classList.remove('active'); }
    function closeOnBackdrop(event, id) { if (event.target.id === id) closeModal(id); }

    function openCreateModal() {
        resetPreview('createImageFile', 'createImageUrl', 'createImagePreview');
        const statusInput = document.getElementById('createStatus');
        setCreateStatus(statusInput ? statusInput.value : 'active');
        expandAiSection('createAiConfigSection');
        updateAiSummary('create');
        openModal('createModal');
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
        if (statusLabel) statusLabel.textContent = normalizedStatus === 'active' ? I18N_PRODUCTS.active : I18N_PRODUCTS.inactive;
    }

    function resetPreview(fileInputId, urlInputId, previewId) {
        const fileInput = document.getElementById(fileInputId);
        const urlInput = document.getElementById(urlInputId);
        const preview = document.getElementById(previewId);
        if (fileInput) fileInput.value = '';
        if (urlInput && !urlInput.value) urlInput.value = '';
        if (preview && (!urlInput || !urlInput.value)) {
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

    function toggleAiSection(sectionId, triggerBtn) {
        const section = document.getElementById(sectionId);
        if (!section) return;
        section.classList.toggle('collapsed');
        if (triggerBtn) {
            triggerBtn.textContent = section.classList.contains('collapsed') ? I18N_PRODUCTS.expand : I18N_PRODUCTS.collapse;
        }
    }

    function expandAiSection(sectionId) {
        const section = document.getElementById(sectionId);
        if (!section) return;
        section.classList.remove('collapsed');
        const btn = section.querySelector('.section-toggle-btn');
        if (btn) btn.textContent = I18N_PRODUCTS.collapse;
    }

    function updateAiSummary(prefix) {
        const aiCategory = document.getElementById(prefix === 'create' ? 'createAiCategory' : 'editAiCategory');
        const photoType = document.getElementById(prefix === 'create' ? 'createAiGarmentPhotoType' : 'editAiGarmentPhotoType');
        const segmentation = document.getElementById(prefix === 'create' ? 'createAiSegmentationFree' : 'editAiSegmentationFree');
        const summaryCategory = document.getElementById(prefix === 'create' ? 'createSummaryCategory' : 'editSummaryCategory');
        const summaryPhotoType = document.getElementById(prefix === 'create' ? 'createSummaryPhotoType' : 'editSummaryPhotoType');
        const summarySegmentation = document.getElementById(prefix === 'create' ? 'createSummarySegmentation' : 'editSummarySegmentation');

        if (summaryCategory && aiCategory) summaryCategory.textContent = `${I18N_PRODUCTS.summaryAiCategory}: ${aiCategory.value || 'auto'}`;
        if (summaryPhotoType && photoType) summaryPhotoType.textContent = `${I18N_PRODUCTS.summaryPhotoType}: ${photoType.value || 'auto'}`;
        if (summarySegmentation && segmentation) summarySegmentation.textContent = `${I18N_PRODUCTS.summarySegmentation}: ${segmentation.checked ? I18N_PRODUCTS.enabled : I18N_PRODUCTS.disabled}`;
    }

    function setAiOption(prefix, field, value) {
        const isCategory = field === 'category';
        const inputId = prefix === 'create'
            ? (isCategory ? 'createAiCategory' : 'createAiGarmentPhotoType')
            : (isCategory ? 'editAiCategory' : 'editAiGarmentPhotoType');
        const input = document.getElementById(inputId);
        if (!input) return;

        input.value = value;

        const fieldWrap = input.closest('.field');
        if (fieldWrap) {
            fieldWrap.querySelectorAll('.pill-option').forEach(function (btn) {
                const active = btn.getAttribute('data-value') === value;
                btn.classList.toggle('active', active);
                btn.setAttribute('aria-pressed', active ? 'true' : 'false');
            });
        }

        updateAiSummary(prefix);
    }

    bindImagePreview('createImageFile', 'createImageUrl', 'createImagePreview');

    const createAiCategory = document.getElementById('createAiCategory');
    const createAiGarmentPhotoType = document.getElementById('createAiGarmentPhotoType');
    const createAiSegmentationFree = document.getElementById('createAiSegmentationFree');
    const createAiSegmentationFreeLabel = document.getElementById('createAiSegmentationFreeLabel');
    setAiOption('create', 'category', createAiCategory ? createAiCategory.value : 'auto');
    setAiOption('create', 'photoType', createAiGarmentPhotoType ? createAiGarmentPhotoType.value : 'auto');

    if (createAiSegmentationFree && createAiSegmentationFreeLabel) {
        createAiSegmentationFree.addEventListener('change', function () {
            createAiSegmentationFreeLabel.textContent = this.checked ? I18N_PRODUCTS.enabled : I18N_PRODUCTS.disabled;
            updateAiSummary('create');
        });
    }

    const createStatusToggle = document.getElementById('createStatusToggle');
    if (createStatusToggle) {
        createStatusToggle.addEventListener('change', function () {
            setCreateStatus(this.checked ? 'active' : 'inactive');
        });
    }

    const initialStatusInput = document.getElementById('createStatus');
    const initialImageUrlInput = document.getElementById('createImageUrl');
    setCreateStatus(initialStatusInput ? initialStatusInput.value : 'active');
    setPreviewFromUrl('createImagePreview', initialImageUrlInput ? initialImageUrlInput.value : '');

    @if($errors->any() && old('from_product_requests'))
        openCreateModal();
    @endif
</script>

</body>
</html>
