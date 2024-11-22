<div>
    {{-- TODO: add inputs for each about options --}}
    @if ($hasErrorOccured)
        <div class="support__error">
            <p>@lang('support.error')</p>
        </div>
    @endif
    @if ($messageHasBeenSent)
        <div class="support__success">
            <p>@lang('support.success')</p>
        </div>
    @endif
    <form wire:submit='sendMessage' class="form card-na">
        <x-inputs.form-base-input wireModel="firstname" for="support-firstname" type="text" label="firstname"
            localization="firstname" />
        <x-inputs.form-base-input wireModel="lastname" for="support-lastname" type="text" label="lastname"
            localization="lastname" />
        <x-inputs.form-base-input wireModel="email" for="support-email" type="email" label="email"
            localization="email" />
        <div class="form__field">
            <label class="form__field__label" for="about">@lang('inputs.about_label')</label>
            <select wire:model.blur='about' class="form__field__input" type="select" name="about" id="about">
                <option value="null" selected>@lang('inputs.select_about')</option>
                <option value="articles">@lang('titles.articles')</option>
                <option value="shops">@lang('titles.shops')</option>
                <option value="orders">@lang('titles.orders')</option>
                <option value="evaluations">@lang('titles.evaluations')</option>
                <option value="comments">@lang('titles.comments')</option>
                <option value="other">@lang('titles.other')</option>
            </select>
            @error('about')
                @foreach ($errors->get('about') as $message)
                    <span class="form__field__error">{!! $message !!}</span>
                @endforeach
            @enderror
        </div>
        <x-inputs.form-base-input wireModel="message" for="support-message" type="textarea" label="message"
            localization="message" maxLength="500" />
        <x-button type="submit">@lang('buttons.send')</x-button>
    </form>
</div>
