<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Tags extends Model
{
    use HasFactory;

    protected $fillables = [
        "uuid", "name",
            ];

    protected static function generateUUID(): string
    {
        $uuid = Str::uuid();
        if (self::query()->where('uuid', $uuid)->first()) {
            self::generateUUID();
        }
        return $uuid;
    }

    public static function boot()
    {
        parent::boot();

        self::saving(function ($tag) {
            // perform some logic before saving the model
            $tag->uuid = self::generateUUID();
        });
    }
    public function scopeLike($query, $field, $value = null)
    {
        return $query->where($field, 'LIKE', "%$value%");
    }

    public function articles() : BelongsToMany {
        return $this->belongsToMany(Article::class);
    }

    public function category () : BelongsTo {
        return $this->belongsTo(Category::class);
    }

}
