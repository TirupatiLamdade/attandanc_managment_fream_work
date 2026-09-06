<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('folder_id')->constrained('folders')->onDelete('cascade');
            $table->string('name');
            $table->string('branch');
            $table->string('roll_number');
            $table->string('phone');
            $table->string('serno');
            $table->timestamps();

            $table->unique(['folder_id', 'roll_number']);
            $table->unique(['folder_id', 'phone']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};