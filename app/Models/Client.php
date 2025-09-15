<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    //
    protected $fillable = [
        'nom_client', 'type_client', 'phone_client','created_by'
    ];

    // Relation : un client peut faire plusieurs ventes
    public function ventes()
    {
        return $this->hasMany(Vente::class);
    }
}
