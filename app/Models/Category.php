<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;
    protected $fillables = [
"uuid", "name",
    ];


    public function scopeLike($query, $field, $value = null)
    {
        return $query->where($field, 'LIKE', "%$value%");
    }

    public function tags() : HasMany {
    return $this->hasMany(Tags::class);
    }
}

