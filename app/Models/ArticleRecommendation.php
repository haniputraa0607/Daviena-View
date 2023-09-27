<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ArticleRecommendation extends Model
{
    use HasFactory;

    protected $table = 'article_recommendations';
    protected $fillable = [
        'article_top',
        'article_recommendation'
    ];
}
