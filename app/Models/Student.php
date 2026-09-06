<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'folder_id',
        'name',
        'branch',
        'roll_number',
        'phone',
        'serno'
    ];

    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function subjectFolders()
    {
        return $this->belongsToMany(
            SubjectFolder::class,
            'subject_students'
        );
    }

    public function subjectAttendances()
    {
        return $this->hasMany(
            SubjectAttendance::class,
            'student_id'
        );
    }
}