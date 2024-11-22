@props([
    'icon_button' => null,
    'color' => 'green',
    'style' => 'filled',
    'size' => 'small',
    'title' => $slot ?? null,
    'type' => 'button',
    'icon' => null,
    'iconPosition' => 'right',
    'link' => null,
    'livewire' => null,
    'eventTo' => null,
    'event' => null,
    'eventAttr' => null,
    'disabled' => false,
    'key' => false,
])
@if ($icon_button === 'delete')
    <button wire:key="{!! $key !!}" class="button-trash" title="{!! $title !!}"
        type="{!! $type !!}" tabindex="1">
        <span class="button-trash__text">@lang('buttons.delete')</span> <x-icons name="trash-lid" />
    </button>
@else
    <button {!! $key ? 'wire:key="btn-' . $key . '"' : null !!}
        @if ($event && !$eventTo) wire:click="$dispatch('{!! $event !!}')" @endif
        @if ($event && $eventTo && !$eventAttr) wire:click="$dispatchTo('{!! $eventTo !!}', '{!! $event !!}')" @endif
        @if ($event && $eventTo && $eventAttr) wire:click="$dispatchTo('{!! $eventTo !!}', '{!! $event !!}', {!! $eventAttr !!})" @endif
        {!! $livewire ?? null !!} {!! $attributes->merge(['class' => 'button ' . 'btn-' . $color . '-' . $style . ' ' . 'btn-' . $size]) !!} title="{!! $title !!}" type="{!! $type !!}"
        tabindex="1" {!! $disabled ? 'disabled' : null !!}>
        @if ($link)
            <a class="card-link" href="{!! $link !!}">{!! $slot !!}</a>
        @endif
        {!! ucfirst($slot) !!}
        @if ($icon)
            <x-icons :name="$icon" :position="$iconPosition" />
        @endif
    </button>
@endif
