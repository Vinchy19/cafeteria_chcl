<?php

namespace App\Http\Controllers\Home;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\User;
use App\Models\Plat;
use App\Models\Vente;
use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
{
    public function index()
    {
        $aujourdhui = Carbon::today();

        //users
        $nbre_users = User::count();
        //clients
        $nbre_clients = Client::count();

        $platVenduUser = Vente::where('user_id', Auth::id())
            ->whereDate('date_vente', $aujourdhui)
            ->sum('nbre_plat');

        $platVenduTotal = Vente::whereDate('date_vente', $aujourdhui)
            ->count();

        $revenuUser =Vente::where('user_id', Auth::id())
            ->whereDate('date_vente', $aujourdhui)
            ->join('plats', 'ventes.plat_id', '=', 'plats.id')
            ->selectRaw('SUM(ventes.nbre_plat * plats.prix_plat) as total')
            ->value('total');

        $revenuTotal = Vente::whereDate('date_vente', $aujourdhui)
            ->join('plats', 'ventes.plat_id', '=', 'plats.id')
            ->selectRaw('SUM(ventes.nbre_plat * plats.prix_plat) as total')
            ->value('total');

        $clientsChart = [
            'etudiant'    => Client::where('type_client', 'etudiant')->count(),
            'invite' => Client::where('type_client', 'invite')->count(),
            'personnel_administratif'=> Client::where('type_client', 'personnel admin')->count(),
            'professeur'         => Client::where('type_client', 'professeur')->count(),
        ];

        $usersChart = [
            'admin'    => User::where('role', 'admin')->count(),
            'utilisateur' => User::where('role', 'user')->count()   ];

        return view('dashboard',
            compact('nbre_users','nbre_clients',
                'platVenduUser','platVenduTotal','revenuUser','revenuTotal', 'clientsChart', 'usersChart')
        );
    }
}
