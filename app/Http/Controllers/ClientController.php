<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $clients = Client::all();
        return view('front.clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('front.clients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        {
            $request->validate([
                'nom_client' => 'required|string|max:255',
                'type_client' => 'required|in:etudiant,professeur,personnel admin,invite',
                'phone_client' => 'nullable|string|max:15',
                'created_by' => 'string'
            ]);

            Client::create($request->all());

            return redirect()->route('clients.index')->with('success', 'Client ajouté ');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        //
        return view('front.clients.edit', compact('client'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
        //
        $request->validate([
            'nom_client' => 'required|string|max:255',
            'type_client' => 'required|in:etudiant,professeur,personnel admin,invite',
            'phone_client' => 'nullable|string|max:15',
        ]);

        $client->update($request->all());

        return redirect()->route('clients.index')->with('success', 'Client modifié');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        //
        $client->delete();
        return redirect()->route('clients.index')->with('success', 'Client supprimé');
    }
}
