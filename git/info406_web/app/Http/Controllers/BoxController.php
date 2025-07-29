<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\BoxRequest; 
use Illuminate\Support\Facades\Http;
use App\Models\Box;
use Illuminate\Support\Facades\Gate;

class BoxController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {
        if (Gate::denies('view', Box::class)) {
            return redirect()->route('web.boxes.index')->with('error', "Vous n'avez pas le droit de voir ces boîtes.");
        }
        $search = $request->input('search');
        $etat = $request->input('etat');

        $query = Box::query(); // requête que l'on complète selon recherche OU tri

        if (!empty($search)) { // si l'user utilise la recherche -> recherche par nom ou adresse
            $query->where(function ($q) use ($search) { //$q = sous-requête pour plusieurs where (sinon ça pourrait ignorer des résultats)
                $q->where('name', 'like', "%$search%") 
                ->orWhere('address', 'like', "%$search%"); //orWhere => pas dans name donc on regarde l'adresse
            });
        }

        if (!empty($etat) && $etat !== 'all') {
            $query->where('etat', $etat);
        }

        if ($request->filled('sort_inventory')) {
            $query->orderBy('last_inventory', $request->sort_inventory); 
        }

        $boxes = $query->paginate(6); // on affiche que les boxes correspondant à la requête formé

        // Retour de la vue avec les boîtes filtrées
        return view('web.boxes.list', ['boxes' => $boxes]);
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Gate::denies('create', Box::class)) {
            return redirect()->route('web.boxes.index')->with('error', "Vous n'avez pas le droit de créer une boîte.");
        }

        return view('web.boxes.create');
    }


    public function store(BoxRequest $request)
    {
        $validated = $request->validated();
        $coordinates = $this->getCoordinatesFromAddress($validated['address']);

        if ($coordinates['latitude'] === null || $coordinates['longitude'] === null) {
            return back()->withErrors(['address' => 'Adresse invalide ou introuvable.'])->withInput();
        }

        $box = Box::create([
            'name' => $validated['name'],
            'etat' => $validated['etat'],
            'address' => $validated['address'],
            'latitude' => $coordinates['latitude'],
            'longitude' => $coordinates['longitude'],
        ]);

        return redirect()->route('web.boxes.index')->with('success', 'Boîte créée avec succès !');
    }

    /**
     * Display the specified resource.
     */
    public function show(Box $box)
    {
        if (Gate::denies('view', $box)) {
            return redirect()->route('web.boxes.index')->with('error', "Vous n'avez pas le droit de voir ces boîtes.");
        }
        $box->load('copies');
        return view('web.boxes.show', ['box' => $box]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Box $box)
    {
        if (Gate::denies('update', $box)) {
            return redirect()->route('web.boxes.index')->with('error', "Vous n'avez pas le droit de modifier cette boîte.");
        }

        return view('web.boxes.edit', ['box' => $box]);
    }

    // pas besoin de modifications pour une boîte mais si jamais on rajoute la fonctionnalité
    public function update(BoxRequest $request, Box $box)
    {
        $champs = $request->validated();
        
        if (isset($champs['address']) && $champs['address'] !== $box->address) {
            $coordinates = $this->getCoordinatesFromAddress($champs['address']);

            if ($coordinates['latitude'] === null || $coordinates['longitude'] === null) {
                return redirect()->route('web.boxes.index')->with('error', "erreur dans la conversion de l'adresse.");
            }

            $champs = array_merge($champs, $coordinates); // lier les 2 tableaux contenant tout les champs de la boite
        }

        $box->update($champs);

        return redirect()->route('web.boxes.index')->with('success', 'Boîte mise à jour !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Box $box)
    {
        if (Gate::denies('delete', $box)) {
            return redirect()->route('web.boxes.index')->with('error', "Vous n'avez pas le droit de supprimer cette boîte.");
        }

        // on supprime d'abord les exemplaires de livre appartenant à cette boîte (qui normalement ne devrait plus en contenir)
        $box->copies()->delete();
        $box->delete();

        return redirect()->route('web.boxes.index')->with('success', 'Boîte supprimée !');
    }

    public function inventory(Box $box)
    {
        if (Gate::denies('view', $box)) {
            return redirect()->route('web.boxes.index')->with('error', "Vous n'avez pas le droit de voir ces boîtes.");
        }
        return view('web.boxes.inventory', ['box' => $box]);
    }

    public function saveInventory(Request $request, Box $box)
    {
        $checkedCopies = $request->input('copies', []);  //récupère les exemplaires

        foreach ($box->copies as $copy) {
            $copy->disponibilite = in_array($copy->id, $checkedCopies); 
            $copy->save();
        }

        $box->last_inventory = now(); // MAJ dernier inventaire
        $box->save();

        return redirect()->route('web.boxes.show', $box->id)->with('success', 'Inventaire mis à jour !');
    }

    private function getCoordinatesFromAddress(string $address): array
    {
        usleep(100000); // Pause de 0,1 seconde pour éviter un blocage
        $apiKey = "pk.4ba97276e8b32169a8099431022dc1af";
        $url = "https://us1.locationiq.com/v1/search.php?key={$apiKey}&q=" . urlencode($address) . "&format=json";

        $response = Http::get($url);

        if ($response->successful() && !empty($response->json())) {
            $data = $response->json()[0];
            return [
                'latitude' => $data['lat'],
                'longitude' => $data['lon']
            ];
        }

        return ['latitude' => null, 'longitude' => null];
    }
}
