@extends('index')

@push('styles')
    <style>
        /* Leaflet map container style */
        #map { height: 600px; width: 100%; border-radius: 8px; border: 1px solid #ddd; }

        .small-header {
            background-image: linear-gradient(to top,
                    rgba(0, 0, 0, 0.832),
                    rgba(0, 0, 0, 0.75)),
                url({{ asset('images/home-2.jpg') }});
        }

        .church-content {
            padding: 4rem 8rem;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .church-content span {
            font-size: 1rem;
            font-weight: bold;
            margin-bottom: 2rem;
            text-align: center;
        }

        .church-content h2 {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 2rem;
            text-align: center;
            width: 50%;
        }

        .church-content .subtite {
            font-size: 1rem;
            font-weight: normal;
            margin-bottom: 2rem;
            text-align: center;
            width: 60%;
        }

        .church-content .more {
            background-color: transparent;
            border: 1px solid #000;
            text-decoration: none;
            color: #000;
            padding: 1.2rem 2rem;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s ease;
            text-transform: uppercase;
            font-size: 14px;
            margin-inline: 0.1rem;
            margin-bottom: 4rem;
        }

        .church-content .more:hover {
            border: 1px solid #ffb700;
            color: #000;
            transition: all 0.3s ease;
        }

        .church-list {
            padding: 4rem 8rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .church-list .line {
            height: 2px;
            display: block;
            background-color: rgb(0, 0, 0);
            width: 60px;
            margin: 0 auto;
            margin-bottom: 1rem;
        }

        .church-list h2 {
            font-size: 1.1rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .church-list .sub {
            font-size: 0.9rem;
            font-weight: normal;
            margin-bottom: 4rem;
            width: 65%;
            text-align: center;
            display: block;
            margin: 0 auto 4rem auto;
        }

        .church-list .card {
            border: 1px solid #e0e0e0;
            border-radius: 1px;
            padding: 1rem 2rem;
            margin-bottom: 2rem;
            transition: all 0.3s ease;
            width: 100%;
            margin: 0 auto;
            text-decoration: none;
            color: #000;
        }

        .church-list .card h3 {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 1.2rem;
            color: #000;
        }

        .church-list .card p {
            font-size: 0.9rem;
            line-height: 1.6;
            color: #555;
        }

        .church-list .card:hover {
            border-color: #ffb700;
        }
    </style>
@endpush

@section('content')
    <section class="small-header">
        <div>
            <div class="line"></div>
            <h1>Nos Églises</h1>
            <p>Découvrez nos différentes implantations et trouvez une communauté près de chez vous.</p>
        </div>
    </section>

    <section class="church-content">
        <span>Nos Implantations</span>
        <h2>Trouvez une Église EMEC Près de Chez Vous</h2>
        <p class="subtite">L'EMEC est une famille en pleine croissance, avec des églises implantées dans plusieurs villes
            et quartiers. Nous vous invitons à découvrir nos différentes implantations et à rejoindre une communauté où vous
            pourrez grandir dans votre foi et servir Dieu.</p>
        <a href="#church-list" class="more">Voir les églises</a>
    </section>

    <section class="church-list" id="church-list">
        <div class="line"></div>
        <h2>Nos Églises Locales</h2>
        <p class="sub">Chaque église locale de l'EMEC est un centre vibrant de culte, d'enseignement et de fraternité.
            Nous nous efforçons de créer des environnements accueillants où chacun peut se sentir chez soi et expérimenter
            l'amour de Dieu.</p>

        <div id="map" class="mb-5"></div>
    </section>
@endsection

@push('scripts')
    {{-- Leaflet CSS --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    {{-- Leaflet JS --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <script>
        // Initialisation de la carte, centrée sur le Cameroun
        var map = L.map('map').setView([5.5, 12.5], 7);

        // Ajout du fond de carte (OpenStreetMap)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Liste des églises (coordonnées et informations à remplacer par les vôtres)
        var churches = [{
            name: "EMEC Siège - Douala",
            coords: [4.0483, 9.7043],
            address: "Bonabéri, Douala, Cameroun"
        }, {
            name: "EMEC Yaoundé",
            coords: [3.8480, 11.5021],
            address: "Centre-ville, Yaoundé, Cameroun"
        }, ];

        // Ajout des marqueurs pour chaque église
        churches.forEach(function(church) {
            L.marker(church.coords).addTo(map)
                .bindPopup('<b>' + church.name + '</b><br>' + church.address);
        });
    </script>
@endpush
