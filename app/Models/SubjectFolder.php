<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubjectFolder extends Model
{
    protected $fillable = [
        'name',
        'created_by'
    ];

    public function creator()
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    public function students()
    {
        return $this->belongsToMany(
            Student::class,
            'subject_students'
        );
    }

    public function attendances()
    {
        return $this->hasMany(
            SubjectAttendance::class,
            'subject_folder_id'
        );
    }
}