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
        :action="route('web.books.store')" 
        :cancelRoute="route('web.books.index')" 
        :editors="$editors"
        :authors="$authors"
    />
@endsection
