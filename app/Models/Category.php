<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillables = [
"uuid", "name",
    ];

    public static function boot()
    {
        parent::boot();

        self::saving(function ($category) {
            // perform some logic before saving the model

            $category->uuid = self::generateUUID();
        });
    }

    protected static function generateUUID(): string
    {
        $uuid = Str::uuid();
        if (self::query()->where('uuid', $uuid)->first()) {
            self::generateUUID();
        }
        return $uuid;
    }

    public function scopeLike($query, $field, $value = null)
    {
        return $query->where($field, 'LIKE', "%$value%");
    }

    public function tags() : HasMany {
    return $this->hasMany(Tags::class);
    }

    public function articles () : HasManyThrough {
        return $this->hasManyThrough(Article::class, Tags::class, "category_id", "tags_id");
    }
}

