<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChangeEstablishmentController;
use App\Http\Controllers\ConfirmRegistrationController;
use App\Http\Controllers\EmailStyleController;
use App\Http\Controllers\EmailVerificationNotice;
use App\Http\Controllers\FranchiseRegistrationController;
use App\Http\Controllers\FranchiseSubscriptionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\UserAccountDeletionController;
use App\Http\Controllers\UserResetPasswordController;
use App\Livewire\Checkout\AfterCheckout;
use App\Livewire\Checkout\Checkout;
use App\Livewire\ShopRegistration\SellOnLokkalt;
use App\Models\Franchise;
use App\Models\Shop;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::domain(config('app.domains.mail'))->group(function () {
    Route::get('/', function () {
        return redirect(route('home'));
    });
});

Route::domain(config('app.domains.dashboard'))->group(function () {
    Route::get('/login', function () {
        //
    })->name('dashboard-login');

    Route::get('/change-franchise/{franchiseID}', [ChangeEstablishmentController::class, 'changeFranchise'])
        ->middleware(['auth', 'verified']);

    Route::get('/change-shop/{shopSlug}', [ChangeEstablishmentController::class, 'changeShop'])
        ->middleware(['auth', 'verified']);

    Route::get('/franchise-subscription', [FranchiseSubscriptionController::class, 'confirm'])
        ->middleware(['auth', 'verified'])
        ->name('franchise-subscription');
});

Route::domain(config('app.domains.admin'))->group(function () {
    Route::get('/login', function () {
        //
    })->name('admin-login');
});

Route::domain(config('app.domains.base'))
    ->group(function () {
        Route::group(
            [
                'prefix' => LaravelLocalization::setLocale(),
                'middleware' => ['localize', 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath', 'location', 'setCurrency'],
            ],
            function () {
                Route::get('/', [HomeController::class, 'index'])->name('home');

                Route::get(LaravelLocalization::transRoute('routes.categories'), [CategoryController::class, 'index']);
                Route::get(LaravelLocalization::transRoute('routes.category'), [CategoryController::class, 'show']);

                Route::get(LaravelLocalization::transRoute('routes.articles'), [ArticleController::class, 'index']);
                Route::get(LaravelLocalization::transRoute('routes.article'), [ArticleController::class, 'show']);

                Route::get(LaravelLocalization::transRoute('routes.shops'), [ShopController::class, 'index']);
                Route::get(LaravelLocalization::transRoute('routes.shop'), [ShopController::class, 'show']);

                Route::get(LaravelLocalization::transRoute('routes.cart'), function () {
                    return view('cart');
                })->name('cart');
                Route::get(LaravelLocalization::transRoute('routes.checkout'), Checkout::class)
                    ->middleware(['auth', 'verified'])->name('checkout');
                Route::get(LaravelLocalization::transRoute('routes.after-checkout'), AfterCheckout::class)->middleware(['auth', 'verified'])->name('after-checkout');

                Route::get(LaravelLocalization::transRoute('routes.my-orders'), function () {
                    return view('orders');
                })->middleware(['auth', 'verified'])->name('orders');

                Route::get(LaravelLocalization::transRoute('routes.login'), LoginController::class)->name('login');
                Route::get(LaravelLocalization::transRoute('routes.reset_password'), [UserResetPasswordController::class, 'form'])->name('reset-password-form');
                Route::get(LaravelLocalization::transRoute('routes.reset_password_update'), [UserResetPasswordController::class, 'update'])->name('reset-password-update');
                Route::get(LaravelLocalization::transRoute('routes.reset_password_notice'), [UserResetPasswordController::class, 'notice'])->name('reset-password-notice');

                Route::get(LaravelLocalization::transRoute('routes.create-my-account'), RegisterController::class)->name('register');

                Route::get(LaravelLocalization::transRoute('routes.my-profile'), function () {
                    return view('profile');
                })->middleware(['auth', 'verified'])->name('profile');

                Route::get(LaravelLocalization::transRoute('routes.my-favourites'), function () {
                    return view('favourites');
                })->middleware(['auth', 'verified'])->name('favourites');

                Route::get(LaravelLocalization::transRoute('routes.confirm-email'), EmailVerificationNotice::class)->name('confirm-email');
                Route::get(LaravelLocalization::transRoute('routes.confirmed-email'), ConfirmRegistrationController::class)
                    ->name('confirmed-email');

                Route::get(LaravelLocalization::transRoute('routes.account-deleted'), function () {
                    return view('account-deleted');
                })->name('account-deleted');

                Route::get(LaravelLocalization::transRoute('routes.my-notifications'), function () {
                    return view('notifications');
                })->middleware(['auth', 'verified'])->name('notifications');

                Route::get(LaravelLocalization::transRoute('routes.my-settings'), function () {
                    return view('preferences');
                })->middleware(['auth', 'verified'])->name('preferences');

                Route::get(LaravelLocalization::transRoute('routes.sell-on-lokkalt'), SellOnLokkalt::class)->name('sell-on-lokkalt');

                Route::get(LaravelLocalization::transRoute('routes.franchise-registration-notice'), [FranchiseRegistrationController::class, 'notice'])->name('franchise-register-notice');
                Route::get(LaravelLocalization::transRoute('routes.franchise-registration'), [FranchiseRegistrationController::class, 'confirm'])->name('franchise-register-confirm');

                Route::get(LaravelLocalization::transRoute('routes.support'), function () {
                    return view('support.contact');
                })->name('support');

                Livewire::setUpdateRoute(function ($handle) {
                    return Route::post('/livewire/update', $handle);
                });
            }
        );

        Route::post('/logout', LogoutController::class)->middleware(['auth', 'verified'])->name('logout');

        Route::get('/account/delete/{email}/{token}', UserAccountDeletionController::class)->name('delete-account');

        Route::post('/stripe/pay', [StripeController::class, 'pay'])->name('pay');

        Route::get('/sitemap', function () {
            return response()->file(public_path('sitemap.xml'));
        })->name('sitemap');
    });
