<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Link extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'long_url',
        'short_url',
    ];

    public function tags(): HasMany
    {
        return $this->hasMany(Tag::class);
    }

    public function views(): HasMany
    {
        return $this->hasMany(View::class);
    }

    public function saveTags(array $names): void
    {
        $this->tags()->delete();
        $tags = array_map(fn(string $name) => new Tag(['name' => $name]), $names);
        $this->tags()->saveMany($tags);
    }
}
