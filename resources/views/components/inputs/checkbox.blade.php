@props(['for', 'label', 'disabled' => null, 'alpine' => null, 'behavior' => null, 'wireModel' => null])
<div class="form__checkbox">
    <input {!! $wireModel ? "wire:model.live='$wireModel'" : null !!} class="form__checkbox__input" type="checkbox" id="{!! $for !!}"
        name="{!! $for !!}" value="{!! true !!}" {!! $alpine !!} {!! $disabled !!}
        {!! $behavior !!}>
    <span class="checkmark form__checkbox__checkmark"></span>
    <label class="form__checkbox__label" for="{!! $for !!}">{!! $label !!}</label>
</div>
