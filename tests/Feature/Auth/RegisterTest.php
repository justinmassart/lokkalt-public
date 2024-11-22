<?php

namespace Tests\Feature\Auth;

use App\Livewire\Register\RegisterForm;
use App\Mail\RegisterConfirmationMail;
use App\Models\EmailVerificationToken;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    $this->refreshApplicationWithLocale('fr-BE');
});

describe('Auth - Register', function () {
    it('can access the register page', function () {
        $response = $this->get(LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.create-my-account'));

        $response->assertStatus(200);
        $response->assertSeeLivewire(RegisterForm::class);
    });

    it('can’t access the register page if user is logged in', function () {
        $user = User::whereRole('user')->first();
        actingAs($user);

        $authUser = auth()->user();

        expect($authUser)->toBe($user);

        $response = $this->get(LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.create-my-account'));

        $response->assertStatus(302);
    });

    it('can see form fields errors before submitting the form', function () {
        Livewire::test(RegisterForm::class)
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
            ->assertHasErrors('country')
            ->set('password', '123')
            ->assertSet('password', '123')
            ->assertHasErrors('password')
            ->set('confirmPassword', '456')
            ->assertSet('confirmPassword', '456')
            ->assertHasErrors('confirmPassword');
    });

    it('can create an account', function () {
        $usersCount = User::count();

        Livewire::test(RegisterForm::class)
            ->set('firstname', 'John')
            ->set('lastname', 'Doe')
            ->set('email', 'john@doe.com')
            ->set('country', 'BE')
            ->set('password', 'Change_This123.')
            ->set('confirmPassword', 'Change_This123.')
            ->call('register')
            ->assertRedirect(LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.confirm-email', ['email' => 'john@doe.com']))
            ->assertSee('john@doe.com');

        $newUserExists = User::whereEmail('john@doe.com')->exists();
        $newUsersCount = User::count();

        expect($newUsersCount)->toBe($usersCount + 1);
        expect($newUserExists)->toBe(true);
    });

    it('has the correct content for RegisterConfirmationMail', function () {
        Mail::fake();

        $user = User::factory()->create();
        $emailToken = $user->emailVerificationToken()->create([
            'token' => Str::uuid(),
        ]);

        $mailable = new RegisterConfirmationMail($user, $emailToken);

        $mailable->assertSeeInOrderInHtml([
            "$user->firstname $user->lastname",
            LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.confirmed-email', [
                'email' => $user->email,
                'token' => $emailToken->token,
            ]),
        ]);
    });

    it('can send an confirmation email to a newly created user', function () {
        Mail::fake();

        Mail::assertNothingQueued();
        Mail::assertNothingSent();

        Livewire::test(RegisterForm::class)
            ->set('firstname', 'John')
            ->set('lastname', 'Doe')
            ->set('email', 'john@doe.com')
            ->set('country', 'BE')
            ->set('password', 'Change_This123.')
            ->set('confirmPassword', 'Change_This123.')
            ->call('register');

        $user = User::whereEmail('john@doe.com')->first();
        $emailToken = EmailVerificationToken::whereUserId($user->id)->first()->token;

        expect($emailToken)->not->toBe(false);

        Mail::assertQueued(RegisterConfirmationMail::class, function (RegisterConfirmationMail $mail) use ($user, $emailToken) {
            return $mail->hasTo($user->email)
                && $mail->emailToken->token === $emailToken;
        });
    });

    it('can confirm an account with the link from the confirmation mail', function () {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);
        $emailToken = $user->emailVerificationToken()->create([
            'token' => Str::uuid(),
        ]);

        expect($user->email_verified_at)->toBe(null);

        $response = $this->get(LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.confirmed-email', [
            'email' => $user->email,
            'token' => $emailToken->token,
        ]));

        $response->assertRedirect(LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.login'));

        $user->refresh();

        expect($user->email_verified_at)->not->toBe(null);
    });

    it('cannot confirm an email with a wrong token', function () {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);
        $user->emailVerificationToken()->create([
            'token' => Str::uuid(),
        ]);

        $wrongEmailToken = str()->random(32);

        $response = $this->get(LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.confirmed-email', [
            'email' => $user->email,
            'token' => $wrongEmailToken,
        ]));

        $response->assertRedirect(route('home'));

        $user->refresh();

        expect($user->email_verified_at)->toBe(null);
    });

    it('deletes the token once used', function () {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);
        $emailToken = $user->emailVerificationToken()->create([
            'token' => Str::uuid(),
        ]);

        $this->get(LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.confirmed-email', [
            'email' => $user->email,
            'token' => $emailToken->token,
        ]));

        expect($emailToken->exists())->toBe(false);
    });
});
