<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentMethodCheckoutController extends Controller
{
    public function index()
    {
        return view('checkout.payment-method');
    }

    public function post(Request $request)
    {
        if ($request->payment_method) {
            Auth::user()->updateOrCreateStripeCustomer();
            Auth::user()->updateDefaultPaymentMethod($request->payment_method);
        }
        $cart = Cart::session()->first();
        $amount = $cart->courses->sum('price');
        $paymentMethod = $request->payment_method;
        $payment = Auth::user()->charge($amount, $paymentMethod, [
            'return_url' => route('home', ['message', 'payment successfully']),
        ]);
        // dd($request->payment_method);

        if ($payment->status == 'succeeded') {
            $order = Order::create([
                'user_id' => Auth::user()->id,
            ]);
            $order->courses()->attach($cart->courses->pluck('id')->toArray());
            $cart->delete();

            return redirect()->route('home', ['message' => 'Payment Successful']);
        }

    }

    public function oneClick(Request $request)
    {
        if (Auth::user()->hasDefaultPaymentMethod()) {
            $cart = Cart::session()->first();
            $amount = $cart->courses->sum('price');
            // $paymentMethod = $request->payment_method;
            $paymentMethod = Auth::user()->defaultPaymentMethod()->id;
            $payment = Auth::user()->charge($amount, $paymentMethod, [
                'return_url' => route('home', ['message', 'payment successfully']),
            ]);
            // dd($request->payment_method);

            if ($payment->status == 'succeeded') {
                $order = Order::create([
                    'user_id' => Auth::user()->id,
                ]);
                $order->courses()->attach($cart->courses->pluck('id')->toArray());
                $cart->delete();

                return redirect()->route('home', ['message' => 'Payment Successful']);
            }
        }

    }
}
