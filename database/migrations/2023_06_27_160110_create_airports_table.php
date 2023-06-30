<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('airports', function (Blueprint $table) {
            $table->id();

            $table->string('name', 140);
            $table->string('code', 4)->unique();
            $table->decimal('lat', 10, 8);
            $table->decimal('lng', 11, 8);

            $table->timestamps();
        });
    }
};
