<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = ['folder_id', 'student_id', 'date', 'status', 'marked_by', 'marked_at'];

    protected $casts = [
        'date' => 'date',
        'marked_at' => 'datetime',
    ];

    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function markedBy()
    {
        return $this->belongsTo(User::class, 'marked_by');
    }
}