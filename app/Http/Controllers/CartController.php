<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        // replace with view copmoser : $cart = Cart::with("courses")->where('session_id', session()->getId())->first();
        // $cart = Cart::with("courses")->where('session_id', session()->getId())->first();
        return view('cart.index', get_defined_vars());
    }

    public function addToCart(Course $course)
    {
        $cart = Cart::firstOrCreate([
            'session_id' => session()->getId(),
            'user_id' => Auth::user() ? Auth::user()->id : null,
        ]);
        $cart->courses()->syncWithoutDetaching($course);

        return back();
    }

    public function removeFromCart(Course $course)
    {
        $cart = Cart::session()->first();
        abort_unless($cart, 404);
        $cart->courses()->detach($course);

        return back();
    }
}
