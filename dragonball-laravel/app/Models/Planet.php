<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Planet extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'external_id',
        'name',
        'is_destroyed',
        'description',
        'image',
    ];

    protected function casts(): array{
        return [
            'is_destroyed' => 'boolean',
        ];
    }

    public function characters(): HasMany
    {
        return $this->hasMany(Character::class);
    }
}
