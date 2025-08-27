<?php

namespace App\Http\Controllers;

use App\Models\Vente;
use Illuminate\Http\Request;

class VenteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ventes = Vente::with(['client', 'plat'])->get();
        return view('front.ventes.index', compact('ventes'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $clients = Client::all();
        $plats = Plat::all();
        return view('front.ventes.create', compact('clients', 'plats'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'plat_id' => 'required|exists:plats,id',
            'nbre_plat' => 'required|integer|min:1',
        ]);

        // Vérifier si ce client a déjà acheté un plat aujourd’hui
        $dejaAchete = Vente::where('client_id', $request->client_id)
            ->whereDate('date_vente', now()->toDateString())
            ->exists();

        if ($dejaAchete) {
            return back()->with('error', 'Ce client a déjà acheté un plat aujourd’hui ');
        }

        Vente::create([
            'client_id' => $request->client_id,
            'plat_id' => $request->plat_id,
            'user_id' => auth()->id(),
            'nbre_plat' => $request->nbre_plat,
            'date_vente' => now()->toDateString(),
        ]);

        return redirect()->route('front.ventes.index')->with('success', 'Vente enregistrée');
    }

    /**
     * Display the specified resource.
     */
    public function show(Vente $vente)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vente $vente)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vente $vente)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vente $vente)
    {
        //
        // Seulement l’admin peut supprimer une vente
        if (!auth()->user()->isAdmin()) {
            return back()->with('error', 'Action non autorisée');
        }

        $vente->delete();
        return redirect()->route('front.ventes.index')->with('success', 'Vente supprimée');
    }
}
