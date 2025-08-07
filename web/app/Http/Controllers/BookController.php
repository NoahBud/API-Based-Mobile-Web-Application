<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Editor;
use App\Models\Author;
use App\Models\Box;
use App\Models\Copy;
use Illuminate\Http\Request;
use App\Http\Requests\BookRequest;
use Illuminate\Support\Facades\Gate;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (Gate::denies('view', Book::class)) {
            return redirect()->route('web.books.index')->with('error', "Vous n'avez pas le droit de voir un livre.");
        }
        $search = $request->input('search');

        $books = Book::when($search, function ($query, $search) {
            $query->where('name', 'LIKE', "%{$search}%")
                ->orWhereHas('authors', function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%");
                })
                ->orWhereHas('editor', function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%");
                });
        })->with(['authors', 'editor'])->paginate(10);
                return view('web.books.index', ['books' => $books]);

    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Gate::denies('create', Book::class)) {
            return redirect()->route('web.books.index')->with('error', "Vous n'avez pas le droit d'ajouter un livre.");
        }

        return view('web.books.create', [
            'editors' => Editor::all(),
            'authors' => Author::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BookRequest $request)
    {
        $champs = $request->validated();
        $book = Book::create($champs);

        $authorsID = $request->authors ?? []; // Peut être vide

        if ($request->filled('new_author')) {
            $newAuthor = Author::firstOrCreate(
                ['name' => $request->new_author], 
            );
            $authorsID[] = $newAuthor->id; 
        }

        if (!empty($authorsID)) {
            $book->authors()->attach($authorsID);
        }

        return redirect()->route('web.books.show', $book->id)->with('success', 'Livre ajouté avec succès.');
    }



    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        if (Gate::denies('view', Book::class)) {
            return redirect()->route('web.books.index')->with('error', "Vous n'avez pas le droit de voir un livre.");
        }
        $boxes = Box::all();

        return view('web.books.show', [
            'book' => $book,
            'boxes' => $boxes, 
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        if (Gate::denies('update', Book::class)) {
            return redirect()->route('web.books.index')->with('error', "Vous n'avez pas le droit de modifier un livre.");
        }
        $editors = Editor::all();
        $authors = Author::all();
        return view('web.books.edit', [
            'book' => $book,
            'editors' => $editors,
            'authors' => $authors
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BookRequest $request, Book $book)
{
    $champs = $request->validated();
    unset($champs['ISBN']); // On empêche la modification de l'ISBN
    $book->update($champs);

    $authorsID = $request->authors ?? [];

    if (in_array('none', $authorsID)) {
        $authorsID = []; // Aucun auteur sélectionné
    }

    if ($request->filled('new_author') && $request->filled('new_author_age')) {
        $newAuthor = Author::firstOrCreate(
            ['name' => $request->new_author], 
            ['age' => $request->new_author_age]
        );
        $authorsID[] = $newAuthor->id;
    }

    $book->authors()->sync($authorsID);

    return redirect()->route('web.books.show', $book->id)->with('success', 'Livre mis à jour avec succès.');
}




    public function createCopy(Book $book, Request $request)
    {
        if (Gate::denies('create', Book::class)) {
            return redirect()->route('web.books.index')->with('error', "Vous n'avez pas le droit d'ajouter un exemplaire.");
        }

        // Récupérer le plus grand numéro d'exemplaire actuel
        $numeroExemplaire = Copy::max('numero_exemplaire');
        while (Copy::where('numero_exemplaire', $numeroExemplaire)->exists()) {
            $numeroExemplaire++; // incrémenter jusqu'à trouver un emplacement
        }

        $copy = new Copy([
            'numero_exemplaire' => $numeroExemplaire,
            'etat' => 'Neuf',
            'disponibilite' => true,
            'book_id' => $book->id,
            'box_id' => $request->box_id,
        ]);

        $copy->save(); 

        session()->flash('success', 'Exemplaire ajouté avec succès à la boîte.');

        return redirect()->route('web.books.show', $book->id)->with('success', 'Exemplaire ajouté avec succès à la boîte.');
    }
}
