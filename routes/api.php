<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Enums\UserRole;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PublisherController;
use App\Http\Controllers\Api\AuthorController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\NewPasswordController;
use App\Http\Controllers\Api\EmailVerificationController;
use App\Http\Controllers\Api\GenreController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\DiscountController;
use App\Http\Controllers\Api\ShoppingCartController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/* Auth Routes */

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
    Route::post('/forgot-password', [NewPasswordController::class, 'forgotPassword'])->name('password.email');
    Route::post('/reset-password', [NewPasswordController::class, 'resetPassword'])->name('password.update')->middleware('auth:sanctum');
});
/* End of Auth Routes */
/* -------------------------------------------------------------------------- */


/* Email Verification Routes */
Route::middleware(['auth:sanctum',])->group(function () {
    Route::get(
        '/email/verify/{id}/{hash}',
        [EmailVerificationController::class, 'verify']
    )->name('verification.verify');

    Route::post(
        '/email/verification-notification',
        [EmailVerificationController::class, 'sendVerificationEmail']
    )->name('verification.send');
});
/* End of Email Verification Routes */
/* -------------------------------------------------------------------------- */


/* Admin Routes */
Route::group([
    'middleware' => ['auth:sanctum', 'role:' . UserRole::getKey(UserRole::Admin)],
    'prefix' => 'admin'
], function () {
    Route::apiResource('/publishers', PublisherController::class);
    Route::apiResource('/authors', AuthorController::class);
    Route::apiResource('/books', BookController::class);
    Route::apiResource('/genres', GenreController::class);
    Route::apiResource('/discounts', DiscountController::class);
});
/* End of Admin Routes */
/* -------------------------------------------------------------------------- */


/* User Routes */
Route::group([
    'middleware' => ['auth:sanctum', 'active'],
], function () {
    Route::group([
        'prefix' => 'user'
    ], function () {
        Route::get('/profile', [UserController::class, 'getProfile'])->name('users.getProfile');
        Route::post('/profile', [UserController::class, 'createOrUpdateProfile'])->name('users.createOrUpdateProfile');
        Route::put('/password', [UserController::class, 'updatePassword'])->name('user.password.update');
    });

    Route::group([
        'prefix' => 'books'
    ], function () {
        Route::get('/', [BookController::class, 'index'])->name('books.index');
        Route::get('/{book}', [BookController::class, 'show'])->name('books.show');
    });

    Route::group([
        'prefix' => 'authors'
    ], function () {
        Route::get('/', [AuthorController::class, 'index'])->name('authors.index');
        Route::get('/{author}', [AuthorController::class, 'show'])->name('authors.show');
    });

    Route::group([
        'prefix' => 'publishers'
    ], function () {
        Route::get('/', [PublisherController::class, 'index'])->name('publishers.index');
        Route::get('/{publisher}', [PublisherController::class, 'show'])->name('publishers.show');
    });

    Route::group([
        'prefix' => 'genres'
    ], function () {
        Route::get('/', [GenreController::class, 'index'])->name('genres.index');
        Route::get('/{genre}', [GenreController::class, 'show'])->name('genres.show');
    });

    Route::group([
        'prefix' => 'cart'
    ], function () {
        Route::get('/get', [ShoppingCartController::class, 'getCart'])->name('cart.get');
        Route::post('/add', [ShoppingCartController::class, 'addToCart'])->name('cart.add');
        Route::post('/update', [ShoppingCartController::class, 'updateCart'])->name('cart.update');
        Route::post('/remove', [ShoppingCartController::class, 'removeFromCart'])->name('cart.remove');
    });

    Route::group([
        'prefix' => 'checkout'
    ], function () {
        Route::post('/payment', [CheckoutController::class, 'payment'])->name('checkout.payment');
        Route::post('/payment/confirm', [CheckoutController::class, 'confirmPayment'])->name('checkout.payment.confirm');
    });
});
/* End of User Routes */
/* -------------------------------------------------------------------------- */