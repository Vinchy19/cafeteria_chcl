<?php

namespace App\Http\Controllers;

use App\Models\Plat;
use Illuminate\Http\Request;

class PlatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $plats = Plat::all();
        return view('front.plats.index', compact('plats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('front.plats.create');
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $request->validate([
            'nom_plat' => 'required|string|max:255',
            'cuisson_plat' => 'required|in:cru,cuit,grille',
            'prix_plat' => 'required|numeric|min:0',
            'quantite_plat' => 'required|integer|min:0',
        ]);

        Plat::create($request->all());

        return redirect()->route('plats.index')->with('success', 'Plat ajouté avec succès');
    }

    /**
     * Display the specified resource.
     */
    public function show(Plat $plat)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Plat $plat)
    {
        return view('front.plats.edit',compact('plat'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Plat $plat)
    {
        //
        $request->validate([
            'nom_plat' => 'required|string|max:255',
            'cuisson_plat' => 'required|in:cru,cuit,grille',
            'prix_plat' => 'required|numeric|min:0',
            'quantite_plat' => 'required|integer|min:0',
        ]);

        $plat->update($request->all());

        return redirect()->route('plats.index')->with('success', 'Plat modifié avec succès ️');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Plat $plat)
    {
        //
        $plat->delete();
        return redirect()->route('plats.index')->with('success', 'Plat supprimé');
    }
}
