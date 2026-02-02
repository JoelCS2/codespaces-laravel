<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Series extends Model
{
    use HasFactory;
    protected $table = 'series';
    protected $fillable = ['title', 'genre', 'release_year'];

    public function series()
    {
        return $this->belongsTo(Series::class);
    }

}
