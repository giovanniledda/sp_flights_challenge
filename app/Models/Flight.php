<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $price
 */
class Flight extends Model
{
    use HasFactory;

    protected $table = 'flights';

    protected function price(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => ((int) $value / 100),
            set: fn (string $value) => ((int) $value * 100),
        );
    }

    protected function to(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->arrival_airport->code,
        );
    }

    protected function from(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->departure_airport->code,
        );
    }

    public function departure_airport(): BelongsTo
    {
        return $this->belongsTo(Airport::class, 'code_departure', 'code');
    }

    public function arrival_airport(): BelongsTo
    {
        return $this->belongsTo(Airport::class, 'code_arrival', 'code');
    }
}
