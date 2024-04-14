<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reasons extends Model
{
    use HasFactory;
    protected $table = 'tbl_reason';
    protected $primaryKey = 'id';
}
