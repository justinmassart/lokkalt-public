@props(['stock_status', 'class' => ''])
@php
    switch ($stock_status) {
        case 'in':
            $class = 'in';
            break;
        case 'limited':
            $class = 'warning';
            break;
        case 'out':
            $class = 'danger';
            break;
    }
@endphp
<div class="asset-btn asset-btn-{!! $class !!}">
    <p class="asset-btn__text asset-btn-{!! $class !!}__text">@lang('stock.' . $stock_status)</p>
</div>
