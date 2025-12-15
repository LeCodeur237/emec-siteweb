@extends('index')

@push('styles')
    <style>
        .small-header {
            background-image: linear-gradient(to top,
                    rgba(0, 0, 0, 0.832),
                    rgba(0, 0, 0, 0.75)),
                url({{ asset('images/home-2.jpg') }});
        }

        .event-content {
            padding: 4rem 8rem;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .event-content span {
            font-size: 1rem;
            font-weight: bold;
            margin-bottom: 2rem;
            text-align: center;
        }

        .event-content h2 {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 2rem;
            text-align: center;
            width: 50%;
        }

        .event-content .subtite {
            font-size: 1rem;
            font-weight: normal;
            margin-bottom: 2rem;
            text-align: center;
            width: 60%;
        }

        .event-content .more {
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

        .event-content .more:hover {
            border: 1px solid #ffb700;
            color: #000;
            transition: all 0.3s ease;
        }

        .event-section {
            padding: 4rem 8rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .event-section .line {
            height: 2px;
            display: block;
            background-color: rgb(0, 0, 0);
            width: 60px;
            margin: 0 auto;
            margin-bottom: 1rem;
        }

        .event-section h2 {
            font-size: 1.1rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .event-section .sub {
            font-size: 0.9rem;
            font-weight: normal;
            margin-bottom: 4rem;
            width: 65%;
            text-align: center;
            display: block;
            margin: 0 auto 4rem auto;
        }

        .event-section .event-card {
            display: flex;
            flex-direction: column;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 2rem;
            text-align: left;
            transition: all 0.3s ease;
            width: 100%;
        }

        .event-section .event-card:hover {
            border-color: #ffb700;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .event-section .event-card .event-image {
            width: 100%;
            height: 250px;
            background-size: cover;
            background-position: center;
        }

        .event-section .event-card .event-info {
            padding: 1.5rem;
        }

        .event-section .event-card .event-info h3 {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .event-section .event-card .event-info p {
            font-size: 0.8rem;
            color: #555;
            margin-bottom: 1rem;
        }

        .event-section .event-card .event-info .event-date-location {
            display: flex;
            justify-content: space-between;
            font-size: 0.8rem;
            color: #777;
        }

        .event-section .event-card .event-info .event-date-location span {
            font-weight: normal;
            margin-bottom: 0;
        }
    </style>
@endpush

@section('content')
    <section class="small-header">
        <div>
            <div class="line"></div>
            <h1>Nos Événements</h1>
            <p>Découvrez les événements à venir et passés de l'EMEC.</p>
        </div>
    </section>

    <section class="event-content">
        <span>Calendrier des Événements</span>
        <h2>Participez à Nos Rassemblements et Célébrations</h2>
        <p class="subtite">L'EMEC est une communauté vibrante où la foi est célébrée et partagée à travers une multitude
            d'événements. Que ce soient des cultes spéciaux, des conférences, des séminaires, des concerts ou des
            activités communautaires, il y a toujours une occasion de se connecter, d'apprendre et de grandir ensemble.
        </p>
        <a href="#event-section" class="more">Voir les événements</a>
    </section>

    <section class="event-section" id="event-section">
        <div class="line"></div>
        <h2>Événements à Venir</h2>
        <p class="sub">Ne manquez aucun de nos prochains événements. Consultez notre calendrier pour les dates, les lieux
            et les détails. Nous serions ravis de vous compter parmi nous !</p>

        <div class="row">
            <div class="col-md-4">
                <div class="event-card">
                    <div class="event-image" style="background-image: url({{ asset('images/event-1.jpg') }});"></div>
                    <div class="event-info">
                        <h3>Culte Spécial de Pâques</h3>
                        <p>Venez célébrer la résurrection de notre Seigneur Jésus-Christ lors de notre culte spécial de
                            Pâques. Un moment de louange, d'adoration et de partage de la Parole.</p>
                        <div class="event-date-location">
                            <span>Date: 31 Mars 2024</span>
                            <span>Lieu: EMEC Siège, Douala</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="event-card">
                    <div class="event-image" style="background-image: url({{ asset('images/event-2.jpg') }});"></div>
                    <div class="event-info">
                        <h3>Conférence Annuelle des Femmes</h3>
                        <p>Une conférence inspirante dédiée aux femmes, avec des oratrices de renom, des ateliers
                            pratiques et des moments de prière intenses.</p>
                        <div class="event-date-location">
                            <span>Date: 12-14 Avril 2024</span>
                            <span>Lieu: EMEC Yaoundé</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="event-card">
                    <div class="event-image" style="background-image: url({{ asset('images/event-3.jpg') }});"></div>
                    <div class="event-info">
                        <h3>Séminaire sur le Leadership Chrétien</h3>
                        <p>Développez vos compétences en leadership et approfondissez votre compréhension des principes
                            bibliques pour un leadership efficace.</p>
                        <div class="event-date-location">
                            <span>Date: 19-21 Mai 2024</span>
                            <span>Lieu: EMEC Bafoussam</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const maxLength = 80;
            // Sélectionne tous les paragraphes de description dans les cartes d'événement
            const descriptions = document.querySelectorAll('.event-section .event-card .event-info p');

            descriptions.forEach(p => {
                if (p.textContent.length > maxLength) {
                    p.textContent = p.textContent.substring(0, maxLength).trim() + '...';
                }
            });
        });
    </script>
@endpush
