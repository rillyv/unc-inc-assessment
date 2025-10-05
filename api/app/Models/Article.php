<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'title', 'content', 'image_path', 'summary', 'keywords'];

    protected $casts = [
        'keywords' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
