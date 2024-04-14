<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblArea extends Model
{
    use HasFactory;
    protected $table = 'tbl_area';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'location_type', 'parent_id'];
}