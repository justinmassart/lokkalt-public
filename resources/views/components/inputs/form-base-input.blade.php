@props([
    'for',
    'type',
    'label',
    'localization',
    'required' => true,
    'required_display' => false,
    'wireModel' => null,
    'needEmailVerification' => false,
    'canResetPassword' => false,
    'emailUpdate' => false,
    'phoneUpdate' => false,
    'loading' => false,
    'maxLength' => 75,
])
<div class="form__field{!! $emailUpdate || $phoneUpdate ? ' update-field' : null !!}" {!! $wireModel === 'password' ||
$wireModel === 'confirmPassword' ||
$wireModel === 'oldPassword' ||
$wireModel === 'newPassword' ||
$wireModel === 'confirmNewPassword'
    ? 'x-data="{show: false}"'
    : null !!}>
    <label class="form__field__label" for="{!! $for !!}">@lang('inputs.' . $label . '_label') @if ($required_display)
            <span class="form__field__label__optional">({!! __('inputs.optional') !!})</span>
        @endif
        @if ($loading)
            <span wire:loading.delay.long class="loader"></span>
        @endif
    </label>
    @if (
        $wireModel === 'password' ||
            $wireModel === 'confirmPassword' ||
            $wireModel === 'oldPassword' ||
            $wireModel === 'newPassword' ||
            $wireModel === 'confirmNewPassword')
        <template x-if="show">
            <span @click="show = !show" class="form__field__show-password link">Cacher</span>
        </template>
        <template x-if="!show">
            <span @click="show = !show" class="form__field__show-password link">Montrer</span>
        </template>
    @endif
    @if ($emailUpdate)
        <p class="update-field__description">A mail has been sent to the new email address. Please enter the token
            included in the mail in the input
            below.</p>
        <p class="update-field__description">The mail can only be sent once every 24 hours.</p>
    @endif
    @if ($phoneUpdate)
        <p class="update-field__description">A message has been sent to the new phone number. Please enter the token
            included in the message in the input below.</p>
        <p class="update-field__description">The message can only be sent once every 24 hours.</p>
    @endif
    @if ($type !== 'textarea')
        <input {!! $wireModel && $wireModel === 'address' ? "wire:model.live.debounce.500ms='$wireModel'" : null !!} {!! $wireModel !== 'address' ? "wire:model.blur='$wireModel'" : null !!} class="form__field__input" type="{!! $type !!}"
            name="{!! $for !!}" id="{!! $for !!}" placeholder="{!! __('inputs.' . $label . '_placeholder') !!}"
            {!! $required ? 'required' : '' !!} {!! $wireModel === 'password' ||
            $wireModel === 'confirmPassword' ||
            $wireModel === 'oldPassword' ||
            $wireModel === 'newPassword' ||
            $wireModel === 'confirmNewPassword'
                ? ":type='show ? \"text\" : \"password\"'"
                : null !!}>
    @else
        <textarea wire:model.blur='{!! $wireModel !!}' name="{!! $for !!}" id="{!! $for !!}"
            maxlength="{!! $maxLength !!}"></textarea>
    @endif
    @if ($canResetPassword)
        <p wire:click='resetPassword' class="link form__field__reset-password">J’ai oublié mon mot de passe</p>
    @endif
    @error($wireModel)
        @foreach ($errors->get($wireModel) as $message)
            <span class="form__field__error">{!! $message !!}</span>
        @endforeach
    @enderror
    @if ($wireModel === 'email' && $needEmailVerification)
        <p class="form__field__error">You need to verify your email before being able to log into your
            account.<br>You can resend an email by clicking <span wire:click='resendVerificationEmail'
                class="underlined"><strong>here</strong></span>.</p>
    @endif
    @if ($wireModel === 'address' && $this->predictedAddresses)
        <ul class="form__field__addresses-list" @click.away="$wire.closeCompletedAddresses">
            <div class="form__field__addresses-list__header">
                <h3 class="form__field__addresses-list__header__title">Adresses pré-complétées</h3>
                <x-icons name="cross" livewire="wire:click='closeCompletedAddresses'" />
            </div>
            @foreach ($this->predictedAddresses as $index => $address)
                <li wire:click="selectAddress({!! $index !!})" class="form__field__addresses-list__item">
                    {!! $address !!}
                </li>
            @endforeach
        </ul>
    @endif
</div>
