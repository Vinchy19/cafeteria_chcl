<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Plat;
use App\Models\Vente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class VenteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ventes = Vente::with(['client', 'plat', 'user'])->get();
        $clients = Client::all();
        $plats = Plat::all();

        return view('front.ventes.index', compact('ventes', 'clients', 'plats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::all();
        $plats = Plat::all();
        return view('front.ventes.create', compact('clients', 'plats'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'plat_id' => 'required|exists:plats,id',
            'nbre_plat' => 'required|integer|min:1',
            'date_vente' => 'required|date',
        ]);

        // Vérifier si ce client a déjà acheté un plat aujourd'hui
        $dejaAchete = Vente::where('client_id', $request->client_id)
            ->whereDate('date_vente', $request->date_vente)
            ->exists();

        if ($dejaAchete) {
            return back()->with('error', 'Ce client a déjà acheté un plat à cette date.');
        }

        // Vérifier le stock disponible
        $plat = Plat::find($request->plat_id);
        if ($plat->quantite_plat < $request->nbre_plat) {
            return back()->with('error', 'Stock insuffisant. Quantité disponible: ' . $plat->quantite_plat);
        }

        // Créer la vente
        Vente::create([
            'client_id' => $request->client_id,
            'plat_id' => $request->plat_id,
            'user_id' => Auth::id(),
            'nbre_plat' => $request->nbre_plat,
            'date_vente' => $request->date_vente,
        ]);

        // Mettre à jour le stock
        $plat->decrement('quantite_plat', $request->nbre_plat);

        return redirect()->route('ventes.index')->with('success', 'Vente enregistrée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Vente $vente)
    {
        $vente->load(['client', 'plat', 'user']);
        return view('front.ventes.show', compact('vente'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vente $vente)
    {
        // Vérifier si l'utilisateur peut modifier cette vente
        if (Auth::id() !== $vente->user_id && !Auth::user()->isAdmin()) {
            return back()->with('error', 'Action non autorisée');
        }

        $clients = Client::all();
        $plats = Plat::all();
        return view('front.ventes.edit', compact('vente', 'clients', 'plats'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vente $vente)
    {
        // Vérifier les permissions
        if (Auth::id() !== $vente->user_id && !Auth::user()->isAdmin()) {
            return back()->with('error', 'Action non autorisée');
        }

        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'plat_id' => 'required|exists:plats,id',
            'nbre_plat' => 'required|integer|min:1',
//            'date_vente' => 'required|date',
        ]);

        // Vérifier si un autre client a déjà acheté à cette date (exclure la vente actuelle)
        $dejaAchete = Vente::where('client_id', $request->client_id)
            ->whereDate('date_vente', $request->date_vente)
            ->where('id', '!=', $vente->id)
            ->exists();

        if ($dejaAchete) {
            return back()->with('error', 'Ce client a déjà acheté un plat à cette date.');
        }

        // Gestion du stock
        $ancienPlat = $vente->plat;
        $nouveauPlat = Plat::find($request->plat_id);

        // Restaurer l'ancien stock
        $ancienPlat->increment('quantite_plat', $vente->nbre_plat);

        // Vérifier le nouveau stock
        if ($nouveauPlat->quantite_plat < $request->nbre_plat) {
            // Remettre l'ancien stock si insuffisant
            $ancienPlat->decrement('quantite_plat', $vente->nbre_plat);
            return back()->with('error', 'Stock insuffisant. Quantité disponible: ' . $nouveauPlat->quantite_plat);
        }

        // Mettre à jour la vente
        $vente->update([
            'client_id' => $request->client_id,
            'plat_id' => $request->plat_id,
            'nbre_plat' => $request->nbre_plat,
//            'date_vente' => $request->date_vente,
        ]);

        // Déduire le nouveau stock
        $nouveauPlat->decrement('quantite_plat', $request->nbre_plat);

        return redirect()->route('ventes.index')->with('success', 'Vente modifiée avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vente $vente)
    {
        // Vérifier les permissions
        if (!Auth::user()->isAdmin()) {
            return back()->with('error', 'Action non autorisée. Seul un administrateur peut supprimer une vente.');
        }

        // Restaurer le stock
        $vente->plat->increment('quantite_plat', $vente->nbre_plat);

        $vente->delete();

        return redirect()->route('ventes.index')->with('success', 'Vente supprimée avec succès.');
    }



    public function generatePDF(Request $request)
    {
        $request->validate([
            'date_deb' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_deb',
        ]);

        $date_deb = $request->date_deb;
        $date_fin = $request->date_fin;

        // Récupérer les ventes
        $ventes = Vente::with(['client', 'plat'])
            ->whereBetween('date_vente', [$date_deb, $date_fin])
            ->get();

        // Totaux
        $total_plat = $ventes->sum('nbre_plat');
        $total_montant = $ventes->sum(fn($v) => $v->nbre_plat * $v->plat->prix_plat);

        $pdf = Pdf::loadView('front.ventes.pdf', compact('ventes', 'date_deb', 'date_fin', 'total_plat', 'total_montant'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream("rapport_ventes.pdf");
    }

}
