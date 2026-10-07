<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Character extends Model
{
    /** @use HasFactory<\Database\Factories\CharacterFactory> */
    use HasFactory, SoftDeletes; // SoftDeletes: cuando se borre un personaje no se elimina completamente sino que viaja a la papelera.

    // Campos que se pueden rellenar con create() y update()
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

    /**
     * Un personaje pertenece a un planeta
     */
    public function planet(): BelongsTo
    {
        return $this->belongsTo(Planet::class);
    }
}
