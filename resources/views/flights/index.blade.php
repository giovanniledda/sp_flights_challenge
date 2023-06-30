@extends('layouts.base')

@section('content')

    <div class="max-w-7xl mx-auto p-6 lg:p-8">
        <div class="mt-16">
            <div class="">
                <div class="scale-100 p-6 bg-white dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none flex motion-safe:hover:scale-[1.01] transition-all duration-250 focus:outline focus:outline-2 focus:outline-red-500">
                    <div class="flex flex-col items-center">

                        <form method="GET" action="{{ route('flights.search') }}">

                            @csrf <!-- Add CSRF protection -->

                            <label for="depCode" class="block font-bold mb-2 text-gray-500">Departure Code:</label>
                            <select id="depCode" name="depCode" required class="w-full p-2 rounded border border-gray-300 bg-gray-700 text-gray-300 mb-4">
                                <option value="">Select Departure Code</option>
                                @foreach ($airportCodes as $code)
                                    <option @if(old('depCode') === $code) selected @endif value="{{ $code }}">{{ $code }}</option>
                                @endforeach
                            </select>
                            @error('depCode')
                            <div class="text-red-500 text-xs">{{ $message }}</div>
                            @enderror

                            <label for="arrCode" class="block font-bold mb-2 text-gray-500">Arrival Code:</label>
                            <select id="arrCode" name="arrCode" required class="w-full p-2 rounded border border-gray-300 bg-gray-700 text-gray-300 mb-4">
                                <option value="">Select Arrival Code</option>
                                @foreach ($airportCodes as $code)
                                    <option @if(old('arrCode') === $code) selected @endif value="{{ $code }}">{{ $code }}</option>
                                @endforeach
                            </select>
                            @error('arrCode')
                            <div class="text-red-500 text-xs">{{ $message }}</div>
                            @enderror

                            <button type="submit" style="background-color: green" class="bg-green-500 hover:bg-green-200 focus:outline-none text-white py-2 px-4 rounded mt-4">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-center mt-16 px-0 sm:items-center sm:justify-between">

            <div class="ml-4 text-center text-sm text-gray-500 dark:text-gray-400 sm:text-right sm:ml-0">
                Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
            </div>
        </div>
    </div>

@endsection
