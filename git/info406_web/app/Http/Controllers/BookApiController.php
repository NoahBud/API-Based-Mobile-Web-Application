<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Book;
use App\Models\Box;
use App\Models\Copy;
use App\Models\Author;
use App\Models\Editor;

class BookApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Book::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BookRequest $request)
    {
        try {
            $existingBook = Book::where('ISBN', $request->ISBN)->first();
            if ($existingBook) {
                return response()->json([
                    "book_id" => $existingBook->id,
                    "author_id" => $existingBook->authors()->pluck('authors.id'),
                    "message" => "Ce livre existe déjà."
                ], 200);
            }

            $validated = $request->validated();
            $book = Book::create($validated);

            $authors = [];

            foreach ($request->authors as $author) {
                if (is_string($author)) { // récupération de List<String> d'android 
                    $existingAuthor = Author::where('name', $author)->first();
                    if ($existingAuthor) {
                        $authors[] = $existingAuthor->id;
                    } else {
                        $newAuthor = Author::create(['name' => $author]);
                        $authors[] = $newAuthor->id;
                    }
                } 
            }

            if (!empty($authors)) {
                $book->authors()->syncWithoutDetaching($authors);
            }
            
            $authorIDs = $book->authors()->pluck('authors.id');

            return response()->json([
                "book_id" => $book->id,
                "author_id" => $authorIDs,
                "message" => "Le livre et ses auteurs ont été ajoutés avec succès."
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                "error" => "Une erreur s'est produite.",
                "message" => $e->getMessage()
            ], 400);
        }
    }



    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        return ["book" => $book];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BookRequest $request, Book $book)
    {
        $champs = $request->validated();
        $book->update($champs);

        return ["book" => $book];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        $book->copies()->delete();
        $book->delete();
        return ["message" => "Le livre a été supprimé."];
    }

    public function getCopies(Book $book)
    {
        $copies = $book->copies;
        if (!$copies){
            return(["message" => "Ce livre n'a aucun exemplaire, contactez l'éditeur ;)"]);
        }
        return["copies" => $copies];
    }

    public function getByISBN($isbn)
    {
        $book = Book::where('ISBN', $isbn)->first();

        if (!$book) {
            return (["message" => "Aucun livre trouvé avec cet ISBN.",
                    "isbn" => $isbn
        ]);
        }
        else{
            return response()->json([
                "book_id" => $book,
                "authors" => $book->authors()->get(['id', 'name']),
                "message" => "Ce livre existe déjà."
            ], 200); 
        }

        return ["book" => $book];
    }


    public function getAuthors(Book $book){
        $authors = $book->authors;
        if(!$authors){
            return(["message" => "Auteur anonyme"]);
        }
        return ["authors" => $authors];
    }

    public function createCopy(Book $book, Request $request)
    {
        // vérif que la boite existe bien
        if (!Box::find($request->box_id)) {
            return response()->json(['error' => "La boîte spécifiée n'existe pas."], 400);
        }

        $numeroExemplaire = Copy::max('numero_exemplaire');
        while (Copy::where('numero_exemplaire', $numeroExemplaire)->exists()) {
            $numeroExemplaire++; // incrémenter jusqu'à trouver un emplacement libre
        }

        $copy = new Copy([
            'numero_exemplaire' => $numeroExemplaire,
            'etat' => 'Neuf',
            'disponibilite' => true,
            'book_id' => $book->id,
            'box_id' => $request->box_id,
        ]);

        $copy->save();

        return response()->json([
            'message' => 'Exemplaire ajouté avec succès.',
            'copy' => $copy
        ], 201);
    }



//     public function fetchByISBN(string $isbn)
// {
//     // déjà en BD ?
//     $book = Book::where('ISBN', $isbn)->with(['editor', 'authors'])->first();
//     if ($book) {
//         return response()->json([
//             'book' => $book,
//             'authors' => $book->authors->pluck('name')
//         ]);
//     }

//     //API
//     $response = Http::get("https://www.googleapis.com/books/v1/volumes?q=isbn:{$isbn}");
//     $data = $response->json();

//     if (empty($data['items'])) {
//         return response()->json(['error' => 'Livre introuvable avec cet ISBN.'], 404);
//     }

//     //vérif isbn
//     $bookData = $data['items'][0]['volumeInfo'];
//     $isbnGoogle = collect($bookData['industryIdentifiers'] ?? [])
//         ->firstWhere('type', 'ISBN_13')['identifier'] ?? null; 
//     if (!$isbnGoogle) {
//         return response()->json(['error' => 'ISBN 13 non trouvé dans la réponse de Google.'], 404);
//     }

//     // révérification après ajustement de l'ISBN
//     $book = Book::where('ISBN', $isbnGoogle)->with(['editor', 'authors'])->first();
//     if ($book) {
//         return response()->json([
//             'book' => $book,
//             "authors" => $book->authors()->get(['id', 'name'])
//         ]);
//     }

//     // Gestion de l'éditeur (1 par défaut)
//     $editorId = 1;
//     if (!empty($bookData['publisher'])) {
//         $editor = Editor::firstOrCreate(['name' => $bookData['publisher']]);
//         $editorId = $editor->id;
//     }

//     $book = Book::create([
//         'ISBN' => $isbnGoogle,
//         'name' => $bookData['title'] ?? 'Titre inconnu',
//         'genre' => $bookData['categories'][0] ?? 'Non classé',
//         'description' => $bookData['description'] ?? 'Aucune description disponible',
//         'editor_id' => $editorId, 
//     ]);

//     // Gestion des auteurs
//     $authorsID = [];

//     if (!empty($bookData['authors'])) {
//         foreach ($bookData['authors'] as $authorName) {
//             $author = Author::firstOrCreate(['name' => $authorName]);
//             $authorsID[] = $author->id;
//         }
//     }

//     if (!empty($authorsID)) {
//         $book->authors()->syncWithoutDetaching($authorsID);
//     }

//     return response()->json([
//         'book' => $book->load(['editor', 'authors']),
//         'authors' => $book->authors->pluck('name')
//     ]);
// }

}


