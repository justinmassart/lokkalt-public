<?php

namespace Tests\Feature\Auth;

use App\Livewire\Login\LoginForm;
use App\Livewire\Login\ResetPasswordForm;
use App\Livewire\Login\UpdatePasswordForm;
use App\Mail\ResetPasswordMail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    $this->refreshApplicationWithLocale('fr-BE');
});

describe('Auth - Login', function () {
    it('can access the login page', function () {
        $response = $this->get(LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.login'));

        $response->assertStatus(200)->assertSeeLivewire(LoginForm::class);
    });

    it('cannot access the login page when the user is logged in', function () {
        $user = User::whereRole('user')->first();
        actingAs($user);

        $reponse = $this->get(LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.login'));

        $reponse->assertStatus(302)
            ->assertRedirect(route('home'));
    });

    it('can see errors when filling form fields before submitting', function () {
        Livewire::test(LoginForm::class)
            ->set('email', 'abcd')
            ->assertSet('email', 'abcd')
            ->assertHasErrors('email')
            ->set('password', '')
            ->assertSet('password', '')
            ->assertHasErrors('password');
    });

    it('can log into its account', function () {
        $user = User::whereRole('user')->first();

        $password = 'PFE-Seraing-2324-Lokkalt';
        $passwordCheck = Hash::check($password, $user->password);

        expect($passwordCheck)->toBe(true);

        Livewire::test(LoginForm::class)
            ->set('email', $user->email)
            ->set('password', $password)
            ->call('login');

        $auth = auth()->user()->slug;

        expect($auth)->toBe($user->slug);
    });

    it('can logout of its account', function () {
        $user = User::whereRole('user')->first();
        actingAs($user);

        $response = $this->post('/logout');

        $response->assertStatus(302)
            ->assertRedirect('/');

        expect(auth()->user())->toBe(null);
    });

    it('can see the correct content in the reset password mail', function () {
        $user = User::factory()->create();
        $resetToken = $user->passwordResetToken()->create([
            'token' => Str::uuid(),
        ]);

        $mailable = new ResetPasswordMail($user, $resetToken);

        $mailable->assertSeeInOrderInHtml([
            "$user->firstname $user->lastname",
            LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.reset_password_update', [
                'email' => $user->email,
                'token' => $resetToken->token,
            ]),
        ]);
    });

    it('can send a reset password mail', function () {
        Mail::fake();

        Mail::assertNothingQueued();
        Mail::assertNothingSent();

        $user = User::whereRole('user')->first();

        Livewire::test(ResetPasswordForm::class)
            ->set('email', $user->email)
            ->assertSet('email', $user->email)
            ->call('sendResetPasswordMail');

        $resetToken = $user->passwordResetToken->token;

        expect($resetToken)->not->toBe(false);

        Mail::assertQueued(ResetPasswordMail::class, function (ResetPasswordMail $mail) use ($user, $resetToken) {
            return $mail->hasTo($user->email)
                && $mail->resetToken->token === $resetToken;
        });
    });

    it('cannot send a reset password mail if the user hasn’t verified its email', function () {
        Mail::fake();

        Mail::assertNothingQueued();
        Mail::assertNothingSent();

        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        Livewire::test(ResetPasswordForm::class)
            ->set('email', $user->email)
            ->assertSet('email', $user->email)
            ->call('sendResetPasswordMail')
            ->assertHasErrors('email');

        $resetToken = $user->passwordResetToken;

        expect($resetToken)->toBe(null);

        Mail::assertNotQueued(ResetPasswordMail::class, function (ResetPasswordMail $mail) use ($user, $resetToken) {
            return $mail->hasTo($user->email)
                && $mail->resetToken->token === $resetToken;
        });
    });

    it('can reset its password with the link in the reset password mail', function () {
        $user = User::whereRole('user')->first();

        Livewire::test(ResetPasswordForm::class)
            ->set('email', $user->email)
            ->assertSet('email', $user->email)
            ->call('sendResetPasswordMail');

        $resetToken = $user->passwordResetToken;

        expect($resetToken->token)->not->toBe(null);

        $response = $this->get(LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.reset_password_update', [
            'email' => $user->email,
            'token' => $resetToken->token,
        ]));

        $response->assertSeeLivewire(UpdatePasswordForm::class);

        $oldPassword = $user->password;
        $newPassword = 'NewPassword123.';

        Livewire::test(UpdatePasswordForm::class, ['email' => $user->email, 'token' => $resetToken->token])
            ->set('password', $newPassword)
            ->assertSet('password', $newPassword)
            ->set('confirmPassword', $newPassword)
            ->assertSet('confirmPassword', $newPassword)
            ->call('resetPassword')
            ->assertRedirect(LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.login'));

        $user->refresh();

        $passwordCheck = Hash::check($newPassword, $user->password);
        $wrongPasswordCheck = Hash::check($oldPassword, $user->password);

        expect($passwordCheck)->toBe(true);
        expect($wrongPasswordCheck)->toBe(false);
    });
});
