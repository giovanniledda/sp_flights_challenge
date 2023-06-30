<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flights', function (Blueprint $table) {
            $table->id();

            $table->string('code_departure', 4);
            $table->string('code_arrival', 4);
            $table->integer('price');

            // Relations
            $table->foreign('code_departure')->references('code')->on('airports');
            $table->foreign('code_arrival')->references('code')->on('airports');

            //            $table->index(['code_departure', 'code_arrival']);
            $table->unique(['code_departure', 'code_arrival', 'price']);

            $table->timestamps();
        });
    }
};
