@extends('layouts.base')

@section('content')

    <div class="w-2/5 mx-auto p-6 lg:p-8 bg-gray-900 text-white">
        <div class="my-4">
            Flights from <span class="font-bold text-white">{{ $departureCode }}</span> to <span class="font-bold text-white">{{ $arrivalCode }}</span>
            <br />
            Best price: <span class="text-2xl font-semibold text-pink-500">{{ $bestPrice }}</span>
        </div>


        <a href="{{ route('flights.index') }}" class="inline-block px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
            New search
        </a>

        @foreach($flightsData['data']['stopovers'] as $stopover => $flights)
            <div class="mt-4">
                <span class="text-lg font-semibold text-white">#{{ $stopover }} stopovers</span>
            </div>

            <hr class="my-2 border-gray-400">

            @forelse($flights as $flight)

                <div class="flex items-center mt-2 @if($flight['price'] === $bestPrice) p-2 rounded-2xl border-2 border-green-300 space-y-1 text-2xl font-semibold text-orange-500 @endif">
                    <div class="w-1/3">
                        price: <span class="font-semibold text-yellow-400">{{ $flight['price'] }}</span>
                    </div>
                    <div class="w-2/3">
                        @if(!is_null($flight['stopover_codes']))
                            via: {{ is_array($flight['stopover_codes']) ? implode(', ', $flight['stopover_codes']) : $flight['stopover_codes'] }},
                        @else
                            Direct flight.
                        @endif
                    </div>
                </div>
            @empty
                <div class="mt-2">
                    No flights!
                </div>
            @endforelse
        @endforeach
    </div>

@endsection
