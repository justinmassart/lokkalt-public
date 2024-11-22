@props([
    'required' => true,
    'required_display' => false,
    'label' => null,
    'wireModel' => null,
])
<div class="form__field country-input">
    <label class="form__field__label" for="country">@lang('inputs.country_label') @if ($required_display)
            <span class="form__field__label__optional">(@lang('inputs.optional'))</span>
        @endif
    </label>
    <select {!! $wireModel ? "wire:model.blur='$wireModel'" : null !!} class="form__field__input" type="select" name="country" id="country"
        {!! $required ? 'required' : '' !!}>
        <option value="false" selected>@lang('inputs.select_country')</option>
        <option value="BE">@lang('countries.belgium')</option>
        <option value="DE">@lang('countries.germany')</option>
        <option value="FR">@lang('countries.france')</option>
        <option value="LU">@lang('countries.luxembourg')</option>
        <option value="NL">@lang('countries.netherlands')</option>
    </select>
    @error($wireModel)
        @foreach ($errors->get($wireModel) as $message)
            <span class="form__field__error">{!! $message !!}</span>
        @endforeach
    @enderror
</div>
