<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Direct Checkout - Payment Method') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('direct.paymentMethod.post') }}" method="POST" id="form">
                        @csrf
                        <input type="hidden" name="payment_method" id="payment_method">
                        <label class="block">Card Holder Name :</label>
                        <input id="card-holder-name" type="text">

                        <!-- Stripe Elements Placeholder -->
                        <label class="block my-3">Card Holder Number :</label>
                        <div id="card-element">

                        </div>

                        <button class="px-4 py-2 mt-4 font-bold text-white bg-blue-500 rounded" id="card-button"
                            type="button">
                            Update Payment Method
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // initialize stripe
        const stripe = Stripe(
            @json(env('STRIPE_KEY'))
        );

        const elements = stripe.elements();
        const cardElement = elements.create('card');

        cardElement.mount('#card-element');


        // handle payment process "integration"

        const cardHolderName = document.getElementById('card-holder-name');
        const cardButton = document.getElementById('card-button');

        cardButton.addEventListener('click', async (e) => {
            const {
                paymentMethod,
                error
            } = await stripe.createPaymentMethod(
                'card', cardElement, {
                    billing_details: {
                        name: cardHolderName.value
                    }
                }
            );

            if (error) {
                // Display "error.message" to the user...
                alert("error");
                console.log(error);

            } else {
                // The card has been verified successfully...
                alert("success");
                console.log(paymentMethod);
                document.getElementById("payment_method").value = paymentMethod.id;
                document.getElementById("form").submit();
            }
        });
    </script>
</x-app-layout>
