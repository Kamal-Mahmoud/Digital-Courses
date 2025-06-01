<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\PaymentIntentController;
use App\Http\Controllers\PaymentMethodCheckoutController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SetupIntentController;
use App\Models\Cart;
use App\Models\Course;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $courses = Course::all();

    //  $cart = Cart::with("courses")->where('session_id', session()->getId())->first();
    return view('home', get_defined_vars());
})->name('home');

Route::controller(CourseController::class)->group(function () {
    Route::get('/courses/{course:slug}', 'show')->name('courses.show');
});
Route::controller(CartController::class)->group(function () {
    Route::get('cart', 'index')->name('cart.index');
    // Route::get('addToCart/{course:slug}', 'addToCart')->name('addToCart');
});

Route::get('addToCart/{course:slug}', [CartController::class, 'addToCart'])->name('addToCart');
Route::get('removeFromCart/{course:slug}', [CartController::class, 'removeFromCart'])->name('removeFromCart');

Route::middleware('auth')->group(function () {
    Route::get('checkout', [CheckoutController::class, 'checkout'])->name('checkout');
    Route::get('checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('checkout/cancel', [CheckoutController::class, 'cancel'])->name('checkout.cancel');
    Route::get('checkout/enableCoupons', [CheckoutController::class, 'enableCoupons'])->name('enableCoupons');

    Route::get('checkout/nonStripeProducts', [CheckoutController::class, 'enableCoupnonStripeProductsons'])->name('nonStripeProducts');
    Route::get('checkout/lineItems', [CheckoutController::class, 'lineItems'])->name('lineItems');
});
Route::get('checkout/guest', [CheckoutController::class, 'guest'])->name('checkout.guest');
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// direct integration ....
Route::middleware('auth')->group(function () {
    Route::get('direct/paymentMethod', [PaymentMethodCheckoutController::class, 'index'])->name('direct.paymentMethod');
    Route::post('direct/paymentMethod/post', [PaymentMethodCheckoutController::class, 'post'])->name('direct.paymentMethod.post');
    Route::get('direct/paymentMethod/oneClick', [PaymentMethodCheckoutController::class, 'oneClick'])->middleware('ProtectOneClickCheckout')->name('direct.paymentMethod.oneClick');
});

// direct integration "Payment Intent" ....
Route::middleware('auth')->group(function () {
    Route::get('direct/paymentIntent', [PaymentIntentController::class, 'index'])->name('direct.paymentIntent');
    Route::post('direct/paymentIntent/post', [PaymentIntentController::class, 'post'])->name('direct.paymentIntent.post');

});
// direct integration "Setup Intent" ....
Route::middleware('auth')->group(function () {
    Route::get('direct/setupIntent', [SetupIntentController::class, 'index'])->name('direct.setupIntent');
    Route::post('direct/setupIntent/post', [SetupIntentController::class, 'post'])->name('direct.setupIntent.post');

});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
