<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubjectAttendance extends Model
{
    protected $fillable = [
        'subject_folder_id',
        'student_id',
        'date',
        'status',
        'marked_by',
        'marked_at',
    ];

    protected $casts = [
        'date' => 'date',
        'marked_at' => 'datetime',
    ];

    public function subject()
    {
        return $this->belongsTo(
            SubjectFolder::class,
            'subject_folder_id'
        );
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function markedBy()
    {
        return $this->belongsTo(
            User::class,
            'marked_by'
        );
    }
}