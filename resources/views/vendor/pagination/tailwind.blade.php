@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation">
        <div>
            <p>
                Showing
                <span>{{ $paginator->firstItem() }}</span>
                to
                <span>{{ $paginator->lastItem() }}</span>
                of
                <span>{{ $paginator->total() }}</span>
                results
            </p>
        </div>

        <div>
            @if ($paginator->onFirstPage())
                <span aria-disabled="true" aria-label="@lang('pagination.previous')">
                    <span aria-hidden="true">@lang('pagination.previous')</span>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">@lang('pagination.previous')</a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span aria-disabled="true"><span>{{ $element }}</span></span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page"><span>{{ $page }}</span></span>
                        @else
                            <a href="{{ $url }}" aria-label="@lang('Go to page :page', ['page' => $page])">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

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
