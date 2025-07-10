<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'body', 'image'];




    public function favoritedBy()
{
    return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
}
}
