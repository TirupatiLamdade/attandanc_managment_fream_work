<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subject_attendances', function (Blueprint $table) {
            $table->id();

            $table->foreignId('subject_folder_id')
                ->constrained('subject_folders')
                ->cascadeOnDelete();

            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();

            $table->date('date');

            $table->enum('status', [
                'present',
                'absent'
            ]);

            $table->foreignId('marked_by')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->timestamp('marked_at');

            $table->timestamps();

            // Same subject + same student + same date
            // only one attendance record
            $table->unique([
                'subject_folder_id',
                'student_id',
                'date'
            ]);

            $table->index([
                'subject_folder_id',
                'date'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subject_attendances');
    }
};