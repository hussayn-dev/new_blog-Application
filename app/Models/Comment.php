<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Comment extends Model
{
    use HasFactory;

    protected $fillables = [
        "uuid", "message", "article_id", "user_id",
            ];

    public static function boot()
    {
        parent::boot();

        self::saving(function ($comment) {
            // perform some logic before saving the model
            $comment->uuid = self::generateUUID();
        });
    }
    
    public function article () : BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    protected static function generateUUID(): string
    {
        $uuid = Str::uuid();
        if (self::query()->where('uuid', $uuid)->first()) {
            self::generateUUID();
        }
        return $uuid;
    }
}
