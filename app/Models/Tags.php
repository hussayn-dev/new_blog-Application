<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tags extends Model
{
    use HasFactory;

    protected $fillables = [
        "uuid", "name",
            ];


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
