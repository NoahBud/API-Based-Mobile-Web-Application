@extends('template')

@section('title') Modifier l'utilisateur @endsection

@section('content')
    @if($errors->any())
        <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
        </ul>
    @endif

    <x-user_form :roles="$roles" :action="route('users.update', $user->id)" :cancelRoute="route('users.index')" :user="$user" />
@endsection
