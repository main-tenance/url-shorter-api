<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class View extends Model
{
    use HasFactory;

    protected $fillable = [
        'link_id',
        'user_agent',
        'user_ip',
    ];

    public function link(): BelongsTo
    {
        return $this->belongsTo(Link::class);
    }

}
