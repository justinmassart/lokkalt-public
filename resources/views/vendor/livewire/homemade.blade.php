<div>
    @if ($paginator->hasPages())
        <div class="pagination">
            @if ($paginator->onFirstPage())
                <span class="disabled pagination__item chevron-left" aria-disabled="true"><x-icons name="chevron" /></span>
            @else
                <a wire:click.prevent="gotoPage({{ $paginator->currentPage() - 1 }})"
                    @click.prevent="scrollToElement('.results__list')" class="pagination__item chevron-left"
                    rel="prev"><x-icons name="chevron" /></a>
            @endif

            @php
                $start = $paginator->currentPage() - 2;
                $end = $paginator->currentPage() + 2;

                if ($start < 1) {
                    $start = 1;
                }

                if ($end > $paginator->lastPage()) {
                    $end = $paginator->lastPage();
                }

                $range = $paginator->getUrlRange($start, $end);
            @endphp

            @if ($paginator->currentPage() > 3)
                <a wire:click.prevent="gotoPage(1)" @click.prevent="scrollToElement('.results__list')"
                    class="pagination__item" href="{{ $paginator->url(1) }}">1</a>
                <span class="pagination__item">...</span>
            @endif

            @foreach ($range as $page => $url)
                @php
                    info($page);
                @endphp
                @if ($page == $paginator->currentPage())
                    <span class="active pagination__item" aria-current="page">{{ $page }}</span>
                @else
                    <a wire:click.prevent="gotoPage({{ $page }})"
                        @click.prevent="scrollToElement('.results__list')" class="pagination__item"
                        href="{{ $url }}">{{ $page }}</a>
                @endif
            @endforeach

            @if ($paginator->currentPage() < $paginator->lastPage() - 3)
                <span class="pagination__item">...</span>
                <a wire:click.prevent="gotoPage({{ $paginator->lastPage() }})"
                    @click.prevent="scrollToElement('.results__list')" class="pagination__item"
                    href="{{ $paginator->url($paginator->lastPage()) }}">{{ $paginator->lastPage() }}</a>
            @endif

            @if ($paginator->hasMorePages())
                <a wire:click.prevent="gotoPage({{ $paginator->currentPage() + 1 }})"
                    @click.prevent="scrollToElement('.results__list')" class="chevron-right pagination__item"
                    rel="next"><x-icons name="chevron" /></a>
            @else
                <span class="disabled pagination__item chevron-right" aria-disabled="true"><x-icons
                        name="chevron" /></span>
            @endif
        </div>
    @endif
</div>
