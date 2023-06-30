<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Airport extends Model
{
    use HasFactory;

    protected $table = 'airports';

    public static function getCodes(): array
    {
        return self::orderBy('code')->pluck('code')->toArray();
    }
}
