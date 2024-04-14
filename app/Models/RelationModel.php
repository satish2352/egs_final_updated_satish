<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelationModel extends Model
{
    use HasFactory;
    protected $table = 'relation';
    protected $primaryKey = 'id';
}
