<?php

namespace Tests\Feature\Auth;

use App\Livewire\Profile\ProfileForm;
use App\Mail\ConfirmAccountDeletionMail;
use App\Mail\UserEmailUpdateMail;
use App\Models\User;
use App\Models\UserAccountDeletionToken;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    $this->refreshApplicationWithLocale('fr-BE');
});

describe('Auth - Profile', function () {
    it('can access the profile page when logged in', function () {
        $user = User::whereRole('user')->first();
        actingAs($user);

        $response = $this->get(LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.my-profile'));

        $response->assertStatus(200)->assertSeeLivewire(ProfileForm::class);
    });

    it('can see the profile form with its infos', function () {
        $user = User::whereRole('user')->first();

        Livewire::actingAs($user)
            ->test(ProfileForm::class)
            ->assertViewHas('firstname', $user->firstname)
            ->assertViewHas('lastname', $user->lastname)
            ->assertViewHas('email', $user->email)
            ->assertViewHas('phone', $user->phone)
            ->assertViewHas('address', $user->address)
            ->assertViewHas('country', $user->country);
    });

    it('cannot access the profile page if not logged in', function () {
        $response = $this->get(LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.my-profile'));

        $response->assertStatus(302)->assertRedirect(LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.login'));
    });

    it('can see errors in the profile form before submitting', function () {
        $user = User::whereRole('user')->first();

        Livewire::actingAs($user)
            ->test(ProfileForm::class)
            ->set('firstname', 'John1')
            ->assertSet('firstname', 'John1')
            ->assertHasErrors('firstname')
            ->set('lastname', 'Doe2')
            ->assertSet('lastname', 'Doe2')
            ->assertHasErrors('lastname')
            ->set('email', 'justin@lokkalt.com')
            ->assertSet('email', 'justin@lokkalt.com')
            ->assertHasErrors('email')
            ->set('country', 'ABC')
            ->assertSet('country', 'ABC')
            ->assertHasErrors('country');
    });

    it('can update its informations', function () {
        $user = User::factory()->create([
            'role' => 'user',
            'phone' => '+32494391105',
        ]);

        $oldUser = clone $user;

        $countries = array_values(array_filter(array_keys(config('locales.supportedCountries')), function (string $country) use ($user) {
            if ($country !== $user->country) {
                return $country;
            }
        }));

        Livewire::actingAs($user)
            ->test(ProfileForm::class)
            ->set('firstname', 'Sbeb')
            ->set('lastname', 'Sboub')
            ->set('address', 'rue blabla 12, 4562 ICI')
            ->set('country', $countries[0])
            ->set('phone', '')
            ->assertHasNoErrors([
                'firstname',
                'lastname',
                'address',
                'country',
                'phone',
                'email',
            ])
            ->call('save');

        $user->refresh();

        $newUser = $user;

        expect($newUser->firstname)->not->toBe($oldUser->firstname);
        expect($newUser->lastname)->not->toBe($oldUser->lastname);
        expect($newUser->full_name)->not->toBe($oldUser->full_name);
        expect($newUser->slug)->not->toBe($oldUser->slug);
        expect($newUser->address)->not->toBe($oldUser->address);
        expect($newUser->country)->not->toBe($oldUser->country);
    });

    it('can update its password', function () {
        $user = User::factory()->create([
            'role' => 'user',
            'phone' => '+32494391105',
        ]);

        $oldPassword = 'PFE-Seraing-2324-Lokkalt';
        $newPassword = str()->random(8) . 'Pa1.';

        $checkOldPassword = Hash::check($oldPassword, $user->password);
        $checkNewPassword = Hash::check($newPassword, $user->password);

        expect($checkOldPassword)->toBe(true);
        expect($checkNewPassword)->toBe(false);

        Livewire::actingAs($user)
            ->test(ProfileForm::class, ['changePassword' => true])
            ->set('oldPassword', $oldPassword)
            ->set('newPassword', $newPassword)
            ->set('confirmNewPassword', $newPassword)
            ->call('save')
            ->assertStatus(200);

        $user->refresh();

        $checkUserNewPassword = Hash::check($newPassword, $user->password);

        expect($checkUserNewPassword)->toBe(true);
    });

    it('contains the correct infos in the email update mail', function () {
        Mail::fake();

        $user = User::whereRole('user')->first();

        $emailToken = $user->emailUpdateToken()->create([
            'token' => str()->random(10),
        ]);

        $mailable = new UserEmailUpdateMail($user, $emailToken);

        $mailable->assertSeeInOrderInHtml([
            "$user->firstname $user->lastname",
            $emailToken->token,
        ]);
    });

    /* it('can send the email update mail and update its email', function () {
        Mail::fake();

        Mail::assertNothingQueued();
        Mail::assertNothingSent();

        $user = User::whereRole('user')->first();

        Livewire::actingAs($user)
            ->test(ProfileForm::class)
            ->set('email', 'test@test.test')
            ->assertSet('email', 'test@test.test')
            ->call('save');

        expect($user->emailUpdateToken)->not->toBe(null);

        $emailToken = $user->emailUpdateToken()->firstOrCreate([
            'user_id' => $user->id,
        ], [
            'token' => str()->random(10),
        ]);

        expect($emailToken->token)->not->toBe(false);

        Mail::assertQueued(UserEmailUpdateMail::class, function (UserEmailUpdateMail $mail) use ($user, $emailToken) {
            return $mail->hasTo('test@test.test')
                && $mail->emailToken->token === $emailToken->token;
        });

        $oldEmail = $user->email;

        Livewire::actingAs($user)
            ->test(ProfileForm::class, ['changeEmail' => true])
            ->set('email', 'test@test.test')
            ->assertSet('email', 'test@test.test')
            ->set('emailUpdateToken', $emailToken->token)
            ->assertSet('emailUpdateToken', $emailToken->token)
            ->call('save');

        $user->refresh();

        expect($user->email)->not->toBe($oldEmail);
        expect($user->email)->toBe('test@test.test');
    }); */

    it('contains the correct infos in the delete account mail', function () {
        Mail::fake();

        $user = User::whereRole('user')->first();

        $deleteToken = $user->accountDeletionToken()->create([
            'token' => Str::uuid(),
        ]);

        $mailable = new ConfirmAccountDeletionMail($user, $deleteToken);

        $mailable->assertSeeInOrderInHtml([
            "$user->firstname $user->lastname",
            route('delete-account', [
                'email' => $user->email,
                'token' => $deleteToken->token,
            ]),
        ]);
    });

    it('can send the delete acount mail', function () {
        Mail::fake();

        Mail::assertNothingQueued();
        Mail::assertNothingSent();

        $user = User::whereRole('user')->first();

        Livewire::actingAs($user)
            ->test(ProfileForm::class)
            ->call('sendDeleteAccountMail');

        $deleteToken = UserAccountDeletionToken::whereUserId($user->id)->first()->token;

        expect($deleteToken)->not->toBe(false);

        Mail::assertQueued(ConfirmAccountDeletionMail::class, function (ConfirmAccountDeletionMail $mail) use ($user, $deleteToken) {
            return $mail->hasTo($user->email)
                && $mail->deleteToken->token === $deleteToken;
        });
    });

    it('can delete its account', function () {
        $user = User::whereRole('user')->first();
        $deleteToken = $user->accountDeletionToken()->create([
            'token' => Str::uuid(),
        ]);

        $response = $this->get(route('delete-account', ['email' => $user->email, 'token' => $deleteToken->token]));

        $response->assertStatus(302)
            ->assertRedirect(LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.account-deleted'));

        // ArticleSCore messing up

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    });
});
