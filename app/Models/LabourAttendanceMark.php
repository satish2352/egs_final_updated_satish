<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabourAttendanceMark extends Model
{
    use HasFactory;
    protected $table = 'tbl_mark_attendance';
    protected $primaryKey = 'id';
}
