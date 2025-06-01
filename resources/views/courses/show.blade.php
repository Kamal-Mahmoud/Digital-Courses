<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __($course->name) }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                {{-- {{ __("Home Page!") }} --}}

                <!-- Individual course card -->
                <div class="p-6 text-gray-900 border border-gray-200 rounded-lg">
                    <a href="#">
                        <h5 class="font-semibold">{{ $course->name }}</h5>
                    </a>

                    <p class="mt-2 text-sm text-gray-600">{{ $course->description }}</p>
                    <p class="mt-2 text-lg font-bold text-blue-600">{{ $course->price }}</p>
                    <a href="#"
                        class="inline-block px-2 py-2 mt-4 text-white bg-blue-500 rounded hover:bg-blue-600">
                        Add To Cart
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
