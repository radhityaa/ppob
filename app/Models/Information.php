<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Information extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'category_information_id', 'title', 'type', 'description', 'slug'];
    protected $with = ['user', 'categoryInformation'];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categoryInformation()
    {
        return $this->belongsTo(CategoryInformation::class);
    }
}
