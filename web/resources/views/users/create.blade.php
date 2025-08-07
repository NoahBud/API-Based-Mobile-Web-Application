@extends('template')

@section('title') Créer un utilisateur @endsection

@section('content')
    @if($errors->any())
        <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
        </ul>
    @endif

    <x-user_form :roles="$roles" :action="route('users.store')" :cancelRoute="route('users.index')"/>
@endsection
