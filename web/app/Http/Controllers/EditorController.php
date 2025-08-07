<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\EditorRequest;
use App\Models\Editor;


class EditorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Editor::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EditorRequest $request)
    {
        $champs = $request->validated();
        $editor= Editor::create($champs);

        return ["editor" => $editor]; // retourne l'éditeur créer en JSON 
    }

    /**
     * Display the specified resource.
     */
    public function show(Editor $editor)
    {
        return ["editor" => $editor];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
