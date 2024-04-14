<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncidentType extends Model
{
    use HasFactory;
    protected $table = 'incident_type';
    protected $primaryKey = 'id';
    protected $fillable = ['english_title', 'marathi_title'];

}
