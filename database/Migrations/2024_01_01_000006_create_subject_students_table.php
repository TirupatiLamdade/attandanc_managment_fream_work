<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subject_students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_folder_id')->constrained('subject_folders')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['subject_folder_id', 'student_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subject_students');
    }
};