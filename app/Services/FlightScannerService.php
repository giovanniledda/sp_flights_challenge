<?php

namespace App\Services;

use App\Models\Flight;
use function array_filter;
use function collect;
use function min;

class FlightScannerService
{
    private ?int $minPriceAllowed = null;

    public function getDirectFlightsByAirportCodes(string $departureCode, string $arrivalCode): mixed
    {
        return $this->getDirectFlightsByAirportCodesQuery($departureCode, $arrivalCode)->get();
    }

    public function countDirectFlightsByAirportCodes(string $departureCode, string $arrivalCode): int
    {
        return $this->getDirectFlightsByAirportCodesQuery($departureCode, $arrivalCode)->count();
    }

    public function getDirectFlightsByAirportCodesStructured(string $departureCode, string $arrivalCode): mixed
    {
        $stopovers = [];

        foreach ($this->getDirectFlightsByAirportCodes(departureCode: $departureCode, arrivalCode: $arrivalCode) as $flight) {
            $stopovers[] = [
                'price' => $flight->price,
                'stopover_codes' => null,
            ];
        }

        return $stopovers;
    }

    public function getMinPriceForDirectFlightsByAirportCodes(string $departureCode, string $arrivalCode): ?int
    {
        return ($this->getDirectFlightsByAirportCodesQuery(departureCode: $departureCode, arrivalCode: $arrivalCode)->min('price') / 100) ?: null;
    }

    public function getSingleStopoverFlightsByAirportCodesStructured(string $departureCode, string $arrivalCode): mixed
    {
        $stopovers = $this->getSingleStopoverFlightsByAirportCodes($departureCode, $arrivalCode);

        return collect($stopovers)->sortBy('price');
    }

    public function getMinPriceForSingleStopoverFlightsByAirportCodes(string $departureCode, string $arrivalCode): ?int
    {
        $stopovers = $this->getSingleStopoverFlightsByAirportCodes($departureCode, $arrivalCode);

        return collect($stopovers)->min('price');
    }

    public function getDoubleStopoverFlightsByAirportCodesStructured(string $departureCode, string $arrivalCode): mixed
    {
        $stopovers = $this->getDoubleStopoverFlightsByAirportCodes($departureCode, $arrivalCode);

        return collect($stopovers)->sortBy('price');
    }

    public function getMinPriceForDoubleStopoverFlightsByAirportCodes(string $departureCode, string $arrivalCode): ?int
    {
        $stopovers = $this->getDoubleStopoverFlightsByAirportCodes($departureCode, $arrivalCode);

        return collect($stopovers)->min('price');
    }

    public function getAllFlightsByDepartureAirportCode(string $departureCode, ?array $arrivalCodesToExclude = []): mixed
    {
        return $this->getAllFlightsByDepartureAirportCodeQuery($departureCode, $arrivalCodesToExclude)->get();
    }

    public function countAllFlightsByDepartureAirportCode(string $departureCode, ?array $arrivalCodesToExclude = []): int
    {
        return $this->getAllFlightsByDepartureAirportCodeQuery($departureCode, $arrivalCodesToExclude)->count();
    }

    public function getAllFlightsByArrivalAirportCode(string $arrivalCode, ?array $departureCodesToExclude = []): mixed
    {
        return $this->getAllFlightsByArrivalAirportCodeQuery($arrivalCode, $departureCodesToExclude)->get();
    }

    public function countAllFlightsByArrivalAirportCode(string $arrivalCode, ?array $departureCodesToExclude = []): int
    {
        return $this->getAllFlightsByArrivalAirportCodeQuery($arrivalCode, $departureCodesToExclude)->count();
    }

    public function getAllFlightsByDepartureAirportCodeQuery(string $departureCode, ?array $arrivalCodesToExclude = []): mixed
    {
        return Flight::where('code_departure', $departureCode)
            ->when(! empty($arrivalCodesToExclude), function ($query) use ($arrivalCodesToExclude) {
                $query->whereNotIn('code_arrival', $arrivalCodesToExclude);
            })
            ->orderBy('price');
    }

    public function getAllFlightsByArrivalAirportCodeQuery(string $arrivalCode, ?array $departureCodesToExclude = []): mixed
    {
        return Flight::where('code_arrival', $arrivalCode)
            ->when(function ($query) use ($departureCodesToExclude) {
                $query->whereNotIn('code_departure', $departureCodesToExclude);
            })
            ->orderBy('price');
    }

    public function getDirectFlightsByAirportCodesQuery(string $departureCode, string $arrivalCode): mixed
    {
        return Flight::where([
            'code_departure' => $departureCode,
            'code_arrival' => $arrivalCode,
        ])->orderBy('price');
    }

    protected function getSingleStopoverFlightsByAirportCodes(string $departureCode, string $arrivalCode): array
    {

        $stopovers = [];

        /** @var Flight $firstFlight */
        foreach ($this->getAllFlightsByDepartureAirportCode(departureCode: $departureCode, arrivalCodesToExclude: [$arrivalCode]) as $firstFlight) {
            if ($this->countDirectFlightsByAirportCodes(departureCode: $firstFlight->arrival_airport->code, arrivalCode: $arrivalCode)) {
                /** @var Flight $secondFlight */
                foreach ($this->getDirectFlightsByAirportCodes(departureCode: $firstFlight->arrival_airport->code, arrivalCode: $arrivalCode) as $secondFlight) {

                    if ($this->getMinPriceAllowed() && (($secondFlight->price + $firstFlight->price) > $this->getMinPriceAllowed())) {
                        return $stopovers;
                    }

                    $stopovers[] = [
                        'price' => $secondFlight->price + $firstFlight->price,
                        'stopover_codes' => $firstFlight->arrival_airport->code,
                    ];

                }
            }
        }

        return $stopovers;
    }

    protected function getDoubleStopoverFlightsByAirportCodes(string $departureCode, string $arrivalCode): array
    {
        $stopovers = [];

        /** @var Flight $firstFlight */
        foreach ($this->getAllFlightsByDepartureAirportCode(departureCode: $departureCode, arrivalCodesToExclude: [$arrivalCode]) as $firstFlight) {
            /** @var Flight $secondFlight */
            foreach ($this->getAllFlightsByDepartureAirportCode(departureCode: $firstFlight->arrival_airport->code, arrivalCodesToExclude: [$arrivalCode, $departureCode]) as $secondFlight) {
                if ($this->countDirectFlightsByAirportCodes(departureCode: $secondFlight->arrival_airport->code, arrivalCode: $arrivalCode)) {
                    /** @var Flight $thirdFlight */
                    foreach ($this->getDirectFlightsByAirportCodes(departureCode: $secondFlight->arrival_airport->code, arrivalCode: $arrivalCode) as $thirdFlight) {

                        if ($this->getMinPriceAllowed() && (($thirdFlight->price + $secondFlight->price + $firstFlight->price) > $this->getMinPriceAllowed())) {
                            return $stopovers;
                        }

                        $stopovers[] = [
                            'price' => $thirdFlight->price + $secondFlight->price + $firstFlight->price,
                            'stopover_codes' => [
                                $firstFlight->arrival_airport->code,
                                $secondFlight->arrival_airport->code,
                            ],
                        ];
                    }
                }
            }
        }

        return $stopovers;
    }

    public function generalMinPriceOptimizedSearch(string $departureCode, string $arrivalCode): ?int
    {
        $minDirect = $this->getMinPriceForDirectFlightsByAirportCodes($departureCode, $arrivalCode);

        // this has no effect on (doesn't stop the) algorithm if result is null
        $this->setMinPriceAllowed($minDirect);

        $minSingleStopover = $this->getMinPriceForSingleStopoverFlightsByAirportCodes(departureCode: $departureCode, arrivalCode: $arrivalCode);

        // this has no effect on (doesn't stop the) algorithm if result is null
        $this->setMinPriceAllowed(min(array_filter([$minSingleStopover, $minDirect]) ?: [$minDirect]));

        $minDoubleStopover = $this->getMinPriceForDoubleStopoverFlightsByAirportCodes(departureCode: $departureCode, arrivalCode: $arrivalCode);

        return min(array_filter([$minDirect, $minSingleStopover, $minDoubleStopover]) ?: [$minDirect]);
    }

    public function aggregateDataForApi(string $departureCode, string $arrivalCode)
    {
        return [
            'data' => [
                'stopovers' => [
                    0 => $this->getDirectFlightsByAirportCodesStructured($departureCode, $arrivalCode),
                    1 => $this->getSingleStopoverFlightsByAirportCodesStructured($departureCode, $arrivalCode),
                    2 => $this->getDoubleStopoverFlightsByAirportCodesStructured($departureCode, $arrivalCode),
                ],
            ],
        ];
    }

    public function getMinPriceAllowed(): ?int
    {
        return $this->minPriceAllowed;
    }

    public function setMinPriceAllowed(?int $minPriceAllowed = null): void
    {
        $this->minPriceAllowed = $minPriceAllowed;
    }
}
