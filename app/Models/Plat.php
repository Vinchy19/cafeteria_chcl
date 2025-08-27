<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plat extends Model
{
    //
    protected $fillable = [
        'nom_plat', 'cuisson_plat', 'prix_plat', 'quantite_plat'
    ];

    // Relation : un plat peut être vendu plusieurs fois
    public function ventes()
    {
        return $this->hasMany(Vente::class);
    }
}
