<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('characters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('external_id')->nullable()->unique();
            $table->string('name');
            $table->string('ki')->nullable();
            $table->string('max_ki')->nullable();
            $table->string('race')->nullable();
            $table->string('gender')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('affiliation')->nullable();
            $table->foreignId('planet_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('characters');
    }
};
