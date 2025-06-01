<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Cart') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($cart && count($cart->courses) > 0)
                        @foreach ($cart->courses as $course)
                            <div class="flex justify-between">
                                <div class="space-y-3">
                                    <div class="flex ">
                                        <h5 class="font-bold text-blue-700 ">{{ $course->name }}</h5>
                                        <span class="mx-4">{{ $course->price() }}</span>
                                    </div>

                                </div>
                                <div>
                                    <a class="px-4 text-red-700 no-underline border border-red-700 hover:bg-red-600 hover:text-black "
                                        href="{{ route('removeFromCart', $course) }}"> Remove</a>
                                </div>

                            </div>
                        @endforeach
                    @else
                        <div class="alert-info">Cart Empty</div>
                    @endif
                </div>


                <div class="flex items-center justify-between pt-6 mt-6 border-t">
                    <div class="flex items-center px-5">
                        <h5 class="font-bold">Total:
                            {{-- <small>{{  $cart->total() !== null ? $cart->total() : 0  }}</small> --}}
                            @if ($cart && $cart->total() !== null)
                                <small>{{ $cart->total() }}</small>
                            @else
                                <small>0</small>
                            @endif

                        </h5>
                    </div>
                    <div>
                        <a class="p-2 px-3 mb-2 text-lg font-bold text-white bg-green-500 border rounded hover:bg-blue-600 me-3"
                            href="{{ route("direct.setupIntent") }}">Checkout</a>
                        @auth
                            @if (Auth::user()->hasDefaultPaymentMethod())
                                <a class="p-2 px-3 mb-2 text-lg font-bold text-white bg-green-500 border rounded hover:bg-blue-600 me-3"
                                    href="{{ route('direct.paymentMethod.oneClick') }}">One Click Checkout</a>
                            @endif
                        @endauth

                        <a class="p-2 px-3 mb-2 text-lg font-bold text-white bg-green-500 border rounded hover:bg-blue-600 me-3"
                            href="{{ route('enableCoupons') }}">enable coupons</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
