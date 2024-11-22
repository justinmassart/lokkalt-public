@if ($paginator->hasPages())
    <div class="quick-articles__slider slider">
        <div class="quick__articles__slider__arrow-left slider__arrow-left">
            @if ($paginator->onFirstPage())
                <span class="disabled" aria-disabled="true"><x-icons name="arrow-thick" /></span>
            @else
                <a wire:click.prevent="gotoPage({{ $paginator->currentPage() - 1 }})"><x-icons name="arrow-thick" /></a>
            @endif
        </div>
        <div class="quick__articles__slider__dots slider__dots">
            @for ($i = 1; $i <= $paginator->lastPage(); $i++)
                <div
                    class="quick__articles__slider__dots__dot slider__dots__dot {{ $i == $paginator->currentPage() ? 'active' : '' }}">
                    @if ($i == $paginator->currentPage())
                        <span aria-current="page">{{ $i }}</span>
                    @else
                        <a class="card-link" wire:click.prevent="gotoPage({{ $i }})">{{ $i }}</a>
                    @endif
                </div>
            @endfor
        </div>
        <div class="quick__articles__slider__arrow-right slider__arrow-right">
            @if ($paginator->hasMorePages())
                <a wire:click.prevent="gotoPage({{ $paginator->currentPage() + 1 }})"><x-icons name="arrow-thick" /></a>
            @else
                <span class="disabled" aria-disabled="true"><x-icons name="arrow-thick" /></span>
            @endif
        </div>
    </div>
@endif
