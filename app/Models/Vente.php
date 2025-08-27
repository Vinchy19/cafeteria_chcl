<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vente extends Model
{


    protected $fillable = [
        'client_id', 'plat_id', 'user_id','nbre_plat'
    ];

    // Une vente appartient à un client
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    // Une vente concerne un plat
    public function plat()
    {
        return $this->belongsTo(Plat::class);
    }
}
