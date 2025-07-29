<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Box;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\BoxRequest;

class BoxApiController extends Controller
{
    public function index()
    {
        return Box::all();
    }

    /**
     * Store a newly created resource in storage.
     */
     public function store(BoxRequest $request)
    {
        $champs = $request->validated();
        $coordinates = $this->getCoordinatesFromAddress($champs['address']);

        if ($coordinates['latitude'] === null || $coordinates['longitude'] === null) {
            return response()->json([
                "error" => "Adresse invalide ou introuvable."
            ], 400);
        }

        $box = Box::create([
            'name' => $champs['name'],
            'etat' => $champs['etat'],
            'address' => $champs['address'],
            'latitude' => $coordinates['latitude'],
            'longitude' => $coordinates['longitude'],
        ]);
        
        return response()->json(["box" => $box], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Box $box)
    {
        return ["box" => $box];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BoxRequest $request, Box $box)
    {
        $champs = $request->validated();
        $box->update($champs);

        return ["box" => $box];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Box $box)
    {
        $box->copies()->delete();
        $box->delete();
        return ["message" => "La box a été supprimée."];
    }

    public function getCopies(Box $box)
    {
        $copies = $box->copies;
        if (!$copies){
            return(["message" => "Cette boîte n'a aucun exemplaire, ajoutez en ;)"]);
        }
        return["copies" => $copies];
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
