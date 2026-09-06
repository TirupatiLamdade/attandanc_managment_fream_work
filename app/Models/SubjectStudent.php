<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class SubjectStudent extends Pivot
{
    protected $table = 'subject_students';
}