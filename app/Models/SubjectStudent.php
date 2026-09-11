<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'folder_id',
        'name',
        'roll_number',
        'branch',
        'phone',
        'serno',
    ];

    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(SubjectFolder::class, 'subject_students', 'student_id', 'subject_folder_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}