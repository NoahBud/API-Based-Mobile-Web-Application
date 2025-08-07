@extends('template')

@section('title', 'Mentions légales')

@section('content')
<div class="container mt-5">
    <h1 class="text-center mb-4">Mentions Légales</h1>

    <p><strong>Nom du site :</strong> Book'in - Partage de la lecture</p>
    <p><strong>Objet :</strong> Book'in est une plateforme permettant aux utilisateurs de partager et de gérer des boîtes à livres en toute coopération.</p>

    <h2 class="mt-4">Éditeur du site</h2>
    <p><strong>Nom :</strong> Book'in</p>
    <p><strong>Adresse :</strong> 12 rue Clovis, 51100, Reims</p>
    <p><strong>Email :</strong> contact@bookin.com</p>

    <h2 class="mt-4">Hébergement</h2>
    <p><strong>Hébergeur :</strong> Noah</p>
    <p><strong>Adresse :</strong> 99 rue Lopez, Monaco</p>

    <h2 class="mt-4">Propriété intellectuelle</h2>
    <p>Le contenu du site Book'in (textes, images, logo, etc.) est protégé par le droit d'auteur. Toute reproduction sans autorisation est interdite.</p>

    <h2 class="mt-4">Responsabilité</h2>
    <p>Book'in met en relation les utilisateurs pour partager des livres, mais n'est pas responsable des échanges effectués ni du contenu des boîtes à livres.</p>

    <h2 class="mt-4">Données personnelles</h2>
    <p>Les informations personnelles collectées sont utilisées uniquement pour la gestion du service. Vous pouvez demander leur suppression à tout moment.</p>

    <h2 class="mt-4">Contact</h2>
    <p>Pour toute question, vous pouvez nous contacter à <a href="mailto:contact@bookin.com">contact@bookin.com</a>.</p>

    <a href="{{ route('accueil') }}" class="btn btn-success mt-4">Retour à l'accueil</a>
</div>
@endsection
