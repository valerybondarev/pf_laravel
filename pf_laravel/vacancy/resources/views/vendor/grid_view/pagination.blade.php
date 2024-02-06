@php
/**
 * @var \Illuminate\Pagination\LengthAwarePaginator $paginator
 * @var array[] $elements
 */
@endphp

@if ($paginator->hasPages())
    <nav aria-label="...">
        <ul class="pagination justify-content-end mb-0">
            {{-- Previous Page Link --}}
            <li class="page-item @if($paginator->onFirstPage()) disabled @endif">
                <a class="page-link" href="{{ \Itstructure\GridView\Helpers\UrlSliderHelper::previousPageUrl(request(), $paginator) }}" rel="prev" @if($paginator->onFirstPage()) tabindex="-1" @endif>
                    <i class="fa fa-angle-left"></i>
                    <span class="sr-only">{{ __('admin.pagination.previous') }}</span>
                </a>
            </li>

            {{-- Pagination Elements --}}
            @foreach($elements as $key => $element)
                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ \Itstructure\GridView\Helpers\UrlSliderHelper::toPageUrl(request(), $paginator, $page) }}">{{ $page }}</a></li>
                        @endif
                    @endforeach

                {{-- "Three Dots" Separator --}}
                @elseif(is_string($element))
                    <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
                @endif
            @endforeach

            {{-- Next Page Link --}}
            <li class="page-item @if(!$paginator->hasMorePages()) disabled @endif">
                <a class="page-link" href="{{ \Itstructure\GridView\Helpers\UrlSliderHelper::nextPageUrl(request(), $paginator) }}" rel="next" @if(!$paginator->hasMorePages()) tabindex="-1" @endif>
                    <i class="fa fa-angle-right"></i>
                    <span class="sr-only">{{ __('admin.pagination.next') }}</span>
                </a>
            </li>
        </ul>
    </nav>
    <div class="clearfix"></div>
@endif
