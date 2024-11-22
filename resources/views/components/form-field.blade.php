@props(['id', 'placeholder', 'type' => 'text', 'isRequired' => false])
<div class="form__field">
    <label class="form__field__label" for="{!! $id !!}">@lang('inputs.' . $slot)</label>
    <input class="form__field__input" id="{!! $id !!}" type="{!! $type !!}"
        placeholder="@lang('inputs.' . $placeholder . '_placeholder')" {!! $isRequired ? 'required' : '' !!}>
</div>
