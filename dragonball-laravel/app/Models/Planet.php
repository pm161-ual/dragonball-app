<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Planet extends Model
{
    /** @use HasFactory<\Database\Factories\PlanetFactory> */
    use HasFactory, SoftDeletes; // SoftDeletes: cuando se borre un planeta no se elimina completamente sino que viaja a la papelera.

    /**
     * Aqui no estan ni id ni created_at ni updated_at porque Laravel los genera automaticamente.
     */
    protected $fillable = [ 
        'external_id',
        'name',
        'is_destroyed',
        'description',
        'image',
    ];

    /**
     * Devuelve is_destroyed como booleano en vez de 0 o 1
     */

    protected function casts(): array{
        return [
            'is_destroyed' => 'boolean',
        ];
    }

    /**
     * Un planeta puede tener muchos personajes
     */

    public function characters(): HasMany
    {
        return $this->hasMany(Character::class);
    }
}

