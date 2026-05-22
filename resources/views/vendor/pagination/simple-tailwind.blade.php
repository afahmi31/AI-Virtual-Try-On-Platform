@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation">
        <div>
            @if ($paginator->onFirstPage())
                <span aria-disabled="true" aria-label="@lang('pagination.previous')">
                    <span aria-hidden="true">@lang('pagination.previous')</span>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">@lang('pagination.previous')</a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">@lang('pagination.next')</a>
            @else
                <span aria-disabled="true" aria-label="@lang('pagination.next')">
                    <span aria-hidden="true">@lang('pagination.next')</span>
                </span>
            @endif
        </div>
    </nav>
@endif
