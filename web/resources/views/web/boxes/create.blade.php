@extends('template')

@section('title') Créer une boîte @endsection

@section('content')
    @if($errors->any())
        <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
        </ul>
    @endif

    <x-box_form :action="route('web.boxes.store')" :cancelRoute="route('web.boxes.index')"/>
@endsection
