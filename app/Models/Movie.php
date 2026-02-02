<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;
    protected $table = 'movies';
    protected $fillable = ['title', 'duration', 'release_year'];

    public function movies()
    {
        return $this->hasMany(Movie::class);
    }
}
