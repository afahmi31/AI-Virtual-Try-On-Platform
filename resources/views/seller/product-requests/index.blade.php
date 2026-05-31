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
                                $selectedProductId = $isOldRow ? old('linked_product_id', $requestItem->linked_product_id) : $requestItem->linked_product_id;
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
                                        <select name="status" class="requests-status-select" data-linked-select-id="linkedProduct{{ $requestItem->id }}">
                                            <option value="new" {{ $selectedStatus === 'new' ? 'selected' : '' }}>{{ __('ui.product_requests_page.status_new') }}</option>
                                            <option value="not_added" {{ $selectedStatus === 'not_added' ? 'selected' : '' }}>{{ __('ui.product_requests_page.status_not_added') }}</option>
                                            <option value="added" {{ $selectedStatus === 'added' ? 'selected' : '' }}>{{ __('ui.product_requests_page.status_added') }}</option>
                                        </select>
                                        <select id="linkedProduct{{ $requestItem->id }}" name="linked_product_id" class="requests-linked-select">
                                            <option value="">{{ __('ui.product_requests_page.linked_product_placeholder') }}</option>
                                            @foreach($catalogProducts as $catalogProduct)
                                                <option value="{{ $catalogProduct->id }}" {{ (string) $selectedProductId === (string) $catalogProduct->id ? 'selected' : '' }}>
                                                    {{ $catalogProduct->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="btn btn-primary">{{ __('ui.product_requests_page.update_button') }}</button>
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

<script>
    function syncLinkedProductSelect(select) {
        if (!select) {
            return;
        }

        const linkedSelectId = select.getAttribute('data-linked-select-id');
        const linkedSelect = linkedSelectId ? document.getElementById(linkedSelectId) : null;
        if (!linkedSelect) {
            return;
        }

        const mustLinkProduct = select.value === 'added';
        linkedSelect.disabled = !mustLinkProduct;
        linkedSelect.required = mustLinkProduct;
        linkedSelect.classList.toggle('is-disabled', !mustLinkProduct);
    }

    document.querySelectorAll('.requests-status-select').forEach((select) => {
        syncLinkedProductSelect(select);
        select.addEventListener('change', () => syncLinkedProductSelect(select));
    });
</script>
</body>
</html>
