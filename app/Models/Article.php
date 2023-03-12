<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory;


    public static function boot()
    {
        parent::boot();

        self::saving(function ($article) {
            // perform some logic before saving the model
            $article->slug = self::generateSlug($article);
        });
    }

  public function comments() : HasMany
  {
    return $this->hasMany(Comment::class);
  }

    public function tags() : BelongsToMany
     {
        return $this->belongsToMany(Tags::class);
    }
    // protected static function generateUUID(): string
    // {
    //     $uuid = Str::uuid();
    //     if (self::query()->where('uuid', $uuid)->first()) {
    //         self::generateUUID();
    //     }
    //     return $uuid;
    // }

    protected static function generateSlug($model): string
    {
        $slug = Str::slug($model->name . Str::substr(time(), -4));
        if (self::query()->where('slug', $slug)->first()) {
            self::generateSlug($model);
        }
        return $slug;
    }
}
