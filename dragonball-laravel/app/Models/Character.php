<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Character extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'external_id',
        'name',
        'ki',
        'max_ki',
        'race',
        'gender',
        'description',
        'image',
        'affiliation',
        'planet_id',
    ];

    public function planet(): BelongsTo
    {
        return $this->belongsTo(Planet::class);
    }
}
