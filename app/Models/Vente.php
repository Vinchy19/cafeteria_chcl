<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vente extends Model
{


    protected $fillable = [
        'client_id', 'plat_id', 'user_id','nbre_plat','date_vente'
    ];

    // Une vente appartient à un client
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function plat()
    {
        return $this->belongsTo(Plat::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function isAdmin()
    {
        return $this->role === 'admin';
    }

}
