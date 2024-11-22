@props(['for', 'type', 'label', 'label_localization', 'placeholder_localization'])
<div class="form__field">
    <label class="form__field__label" for="{!! $for !!}">{!! $label ?? __('inputs.' . $label_localization) !!}</label>
    <input class="form__field__input" type="{!! $type !!}" name="{!! $for !!}"
        id="{!! $for !!}" placeholder="{!! __('inputs.' . $placeholder_localization) !!}">
    @error($for)
        @foreach ($errors->get($for) as $message)
            <span class="error">{!! $message !!}</span>
        @endforeach
    @enderror
</div>
