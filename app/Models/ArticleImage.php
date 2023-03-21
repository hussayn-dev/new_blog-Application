<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArticleImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'article_id',
        'filename',
        'uuid'
    ];


    public function Product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
