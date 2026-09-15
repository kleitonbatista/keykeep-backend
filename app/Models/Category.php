<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    // Desativa a busca por created_at/updated_at pois não existem na sua migration
    public $timestamps = false;

    protected $fillable = [
        'name',
        'slug',
        'icon_identifier',
    ];

    public function credentials(): HasMany
    {
        return $this->hasMany(Credential::class);
    }
}
