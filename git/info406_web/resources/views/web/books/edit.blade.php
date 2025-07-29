@extends('template')

@section('title') Ajouter un livre @endsection

@section('content')
    @if($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <x-book_form 
        :action="route('web.books.update', $book->id)"
        :cancelRoute="route('web.books.index')" 
        :book="$book"
        :editors="$editors"
        :authors="$authors"
    />
@endsection
