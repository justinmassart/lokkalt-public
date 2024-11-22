<div>
    @section('pageTitle', ucfirst(__('titles.sell_on_lokkalt')))
    <x-main-title icon="grid-plus">@lang('titles.create_your_online_shop')</x-main-title>
    <section class="sell" x-data="{ you: @entangle('youForm'), shop: @entangle('shopForm') }">
        @guest
            <section class="sell__you" x-show="you">
                <h3 class="section__title">@lang('titles.about_you')</h3>
                <form class="card-na form">
                    <x-inputs.form-base-input wireModel="firstname" for="register-firstname" type="text" label="firstname"
                        localization="firstname" />
                    <x-inputs.form-base-input wireModel="lastname" for="register-lastname" type="text" label="lastname"
                        localization="lastname" />
                    <x-inputs.form-base-input wireModel="email" for="register-email" type="email" label="email"
                        localization="email" />
                    <x-inputs.country wireModel="country" :required=true :required_display=false />
                    <x-inputs.form-base-input wireModel="address" for="register-address" type="text" label="address"
                        localization="address" />
                    <x-inputs.form-base-input wireModel="password" for="register-password" type="password" label="password"
                        localization="password" />
                    <x-inputs.form-base-input wireModel="confirmPassword" for="register-password-confirmation"
                        type="password" label="confirm_password" localization="password_confirmation" />
                    <div class="form__actions">
                        <x-button wire:click='verifyPerson'>@lang('buttons.next')</x-button>
                    </div>
                </form>
            </section>
        @endguest
        <section class="sell__shop" x-cloak x-show="shop">
            <h3 class="section__title">@lang('titles.about_your_franchise')</h3>
            <p class="sell__advices">Vous pouvez utiliser le numéro de TVA de votre premier commerce, certains
                champs seront pré-complétés pour vous grâce à ce dernier.
                La pré-complétion avec le numéro de TVA peut donner un nom moins beau que le nom de votre commerce.
                Modifiez le si besoin.</p>
            <form class="card-na form">
                <x-inputs.form-base-input wireModel="vat" for="register-vat" type="text" label="vat"
                    localization="vat" :loading=true />
                <x-inputs.country wireModel="shopCountry" :required=true :required_display=false />
                <x-inputs.form-base-input wireModel="shopName" for="register-shop-name" type="text" label="shopName"
                    localization="shopName" />
                <x-inputs.form-base-input wireModel="shopEmail" for="register-shop-email" type="email" label="email"
                    localization="email" />
                <x-inputs.form-base-input wireModel="shopPostalCode" for="register-shopPostalCode" type="number"
                    label="shopPostalCode" localization="shopPostalCode" />
                <x-inputs.form-base-input wireModel="shopCity" for="register-shopCity" type="text" label="shopCity"
                    localization="shopCity" />
                <x-inputs.form-base-input wireModel="shopAddress" for="register-shopAddress" type="text"
                    label="shopAddress" localization="shopAddress" />
                <x-inputs.form-base-input wireModel="shopPhone" for="register-shopPhone" type="tel"
                    label="phone_number" localization="phone" :required=false :required_display=true />
                <div class="form__actions">
                    @guest
                        <x-button wire:loading.attr='disabled' wire:click='goBack'>@lang('buttons.back')</x-button>
                    @endguest
                    <x-button wire:loading.attr='disabled' wire:click='verifyShop'>@lang('buttons.next')</x-button>
                </div>
            </form>
        </section>
    </section>
</div>
