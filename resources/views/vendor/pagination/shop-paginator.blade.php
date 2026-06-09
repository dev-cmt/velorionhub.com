@if ($paginator->hasPages())
    <ul class="wg-pagination wd-load">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="disabled" aria-disabled="true">
                <span class="link"><i class="icon-arrow-left-lg"></i></span>
            </li>
        @else
            <li>
                <a href="{{ $paginator->previousPageUrl() }}" class="link" rel="prev">
                    <i class="icon-arrow-left-lg"></i>
                </a>
            </li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li>
                    <p class="title-normal">{{ $element }}</p>
                </li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="active" aria-current="page">
                            <p class="title-normal link">{{ $page }}</p>
                        </li>
                    @else
                        <li>
                            <a href="{{ $url }}" class="title-normal link">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li>
                <a href="{{ $paginator->nextPageUrl() }}" class="link" rel="next">
                    <i class="icon-arrow-right-lg"></i>
                </a>
            </li>
        @else
            <li class="disabled" aria-disabled="true">
                <span class="link"><i class="icon-arrow-right-lg"></i></span>
            </li>
        @endif
    </ul>
@endif
