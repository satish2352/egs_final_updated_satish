<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabourAttendanceMark extends Model
{
    use HasFactory;
    protected $table = 'tbl_mark_attendance';
    protected $primaryKey = 'id';

    
    public function setAttendanceDayAttribute($value)
{
    $this->attributes['attendance_day'] = $value;
    $this->attributes['day_count'] = ($value === 'half_day') ? 0.5 : 1;
}
    

}

