<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandingSlider extends Model
{
    use HasFactory;
    protected $table = 'landing_slider';
    protected $primaryKey = 'id';
    protected $fillable = ['english_title','english_image'];
}