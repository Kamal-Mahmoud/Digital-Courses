<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Courses') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                {{-- {{ __("Home Page!") }} --}}
                @if (request('message'))
                    <div class="transform transition-all duration-500 ease-in-out border-l-4  
                        {{ request('message') == 'payment successful' ? 'bg-green-700 text-lg' : 'bg-red-700 text-lg' }}"
                        role="alert">
                        {{ request('message') }}</div>
                @endif

                @if (count($courses) > 0)
                    <!-- Grid container outside the loop -->
                    <div class="grid grid-cols-1 gap-4 p-6 sm:grid-cols-2 md:grid-cols-4">
                        @foreach ($courses as $course)
                            <!-- Individual course card -->
                            <div class="p-6 text-gray-900 border border-gray-200 rounded-lg">
                                <a class="text-black no-underline " href="{{ route('courses.show', $course) }}">
                                    <h5 class="font-semibold">{{ $course->name }}</h5>
                                </a>

                                <p class="mt-2 text-sm text-gray-600">{{ $course->description }}</p>
                                <p class="mt-2 text-lg font-bold ">{{ $course->price() }}</p>
                                @if ($cart && $cart->courses->contains($course))
                                    <a href="{{ route('removeFromCart', $course) }}"
                                        class="inline-block px-2 py-2 mt-4 text-white bg-red-700 rounded hover:bg-red-600">
                                        Remove From Cart
                                    </a>
                                @else
                                    <a href="{{ route('addToCart', $course) }}"
                                        class="inline-block px-2 py-2 mt-4 text-white bg-blue-500 rounded hover:bg-blue-600">
                                        Add To Cart
                                    </a>
                                @endif

                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="p-6 text-gray-900">No courses available.</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
