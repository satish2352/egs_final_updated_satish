<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentReasons extends Model
{
    use HasFactory;
    protected $table = 'tbl_doc_reason';
    protected $primaryKey = 'id';
}
