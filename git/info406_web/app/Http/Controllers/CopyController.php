<?php

namespace App\Http\Controllers;

use App\Http\Requests\CopyRequest;
use Illuminate\Http\Request;
use App\Models\Copy;

class CopyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Copy::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CopyRequest $request)
    {
        $champs = $request->validated();
        $copy = Copy::create($champs);

        return ["copy" => $copy]; // retourne l'exemplaire créer en JSON 
    }
    

    /**
     * Display the specified resource.
     */
    public function show(Copy $copy)
    {
        return ["copy" => $copy];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CopyRequest $request, Copy $copy)
    {
        $champs = $request->validated();
        $copy->update($champs);

        return ["copy" => $copy];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Copy $copy)
    {
        $copy->delete();

        return redirect()->back()->with('success', "L'exemplaire a été supprimé avec succès.");
    }


    public function getAuthors(Copy $copy){
        $authors = $copy->book->authors;
        if(!$authors){
            return(["message" => "Auteur anonyme"]);
        }
        return ["authors" => $authors];
    }

    public function setDisponibility(Copy $copy)
    {
        $copy->update(['disponibilite' => !$copy->disponibilite]);
        return ["message" => "Disponibilité mise à jour", 
                 "disponibilite" => $copy->disponibilite,
                "copy_id" => $copy->id];
    }

}
