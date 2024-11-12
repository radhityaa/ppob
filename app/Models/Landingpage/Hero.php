<?php

namespace App\Models\Landingpage;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hero extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'button_text', 'button_url', 'small_text', 'image_hero_dashboard', 'image_hero_element', 'image_hero_dashboard_dark', 'image_hero_element_dark'];
}
