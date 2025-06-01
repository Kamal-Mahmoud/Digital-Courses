<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;
use Laravel\Cashier\Checkout;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function checkout()
    {
        $cart = Cart::session()->first();
        $prices = $cart->courses()->pluck('stripe_price_id')->toArray();
        $sessionOptions = [
            'success_url' => route('checkout.success').'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.cancel').'?session_id={CHECKOUT_SESSION_ID}',
            'metadata' => [
                'cart_id' => $cart->id,
            ],
        ];

        return Auth::user()->checkout($prices, $sessionOptions);
        // dd($payment);
    }

    public function enableCoupons()
    {
        $cart = Cart::session()->first();
        $prices = $cart->courses()->pluck('stripe_price_id')->toArray();
        $sessionOptions = [
            'success_url' => route('home', ['message' => 'payment successfully']),
            'cancel_url' => route('home', ['message' => 'payment failed']),
            // 'allow_promotion_codes' => true,

        ];

        return Auth::user()
            ->checkout($prices, $sessionOptions);
        // dd($payment);
    }

    // public function nonStripeProducts()
    // {
    //     $cart = Cart::session()->first();
    //     $amount = $cart->courses->sum('price');
    //     $sessionOptions = [
    //         'success_url' => route('home', ['message' => 'payment successfully']),
    //         'cancel_url' => route('home', ['message' => 'payment failed']),
    //     ];

    //      return Auth::user()->checkoutCharge( $amount , "course bundle" , 1 , $sessionOptions );
    // }
    public function lineItems()
    {
        $cart = Cart::session()->first();
        $courses = $cart->courses()->get()->map(function ($course) {
            return [
                'price_data' => [
                    'currency' => env('CASHIER_CURRENCY', 'usd'),
                    'product_data' => ['name' => $course->name],
                    'unit_amount' => $course->price,
                    'tax_behavior' => 'exclusive',
                ],
                'adjustable_quantity' => [ // بحدد الكميات من جوة الداشبورد بتاعت السترايب
                    'enabled' => true,
                    'minimum' => 1,
                    'maximum' => 10,
                ],
                'quantity' => 1,
            ];
        })->toArray();
        $sessionOptions = [
            'success_url' => route('home', ['message' => 'payment successfully']),
            'cancel_url' => route('home', ['message' => 'payment failed']),
            'line_items' => $courses,
        ];

        return Auth::user()->checkout(null, $sessionOptions);
    }

    public function guest()
    {
        $cart = Cart::session()->first();
        $courses = $cart->courses()->get()->map(function ($course) {
            return [
                'price_data' => [
                    'currency' => env('CASHIER_CURRENCY', 'usd'),
                    'product_data' => ['name' => $course->name],
                    'unit_amount' => $course->price,
                    'tax_behavior' => 'exclusive',
                ],
                'adjustable_quantity' => [ // بحدد الكميات من جوة الداشبورد بتاعت السترايب
                    'enabled' => true,
                    'minimum' => 1,
                    'maximum' => 10,
                ],
                'quantity' => 1,
            ];
        })->toArray();

        $sessionOptions = [
            'success_url' => route('home', ['message' => 'payment successfully']),
            'cancel_url' => route('home', ['message' => 'payment failed']),
            'line_items' => $courses,
        ];

        return Checkout::guest()->create(null,$sessionOptions,);
    }

    public function success(Request $request)
    {
        $session = $request->user()->stripe()->checkout->sessions->retrieve($request->get('session_id'));

        if ($session->payment_status == 'paid') {
            $cart = Cart::findOrFail($session->metadata->cart_id);
            $order = Order::create([
                'user_id' => $request->user()->id,
            ]);
            $order->courses()->attach($cart->courses->pluck('id')->toArray());
            $cart->delete();

            return redirect()->route('home', ['message' => 'Payment Successful']);
        }

    }

    public function cancel(Request $request)
    {
        $session = $request->user()->stripe()->checkout->sessions->retrieve($request->get('session_id'));
        dd($session);
    }
}
