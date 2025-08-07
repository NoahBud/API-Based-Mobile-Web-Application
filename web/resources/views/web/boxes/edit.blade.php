@extends('template')

@section('title') Modifier la boîte @endsection

@section('content')
    @if($errors->any())
        <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
        </ul>
    @endif

    <x-box_form :action="route('web.boxes.update', $box->id)" :cancelRoute="route('web.boxes.index')" :box="$box" />
@endsection
