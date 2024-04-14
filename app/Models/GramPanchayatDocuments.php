<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GramPanchayatDocuments extends Model
{
    use HasFactory;
    protected $table = 'tbl_gram_panchayat_documents';
    protected $primaryKey = 'id';
}
