@extends('index')

@push('styles')
    <style>
        .small-header {
            background-image: linear-gradient(to top,
                    rgba(0, 0, 0, 0.832),
                    rgba(0, 0, 0, 0.75)),
                url({{ asset('images/home-2.jpg') }});
        }

        .media-content {
            padding: 4rem 8rem;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .media-content span {
            font-size: 1rem;
            font-weight: bold;
            margin-bottom: 2rem;
            text-align: center;
        }

        .media-content h2 {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 2rem;
            text-align: center;
            width: 50%;
        }

        .media-content .subtite {
            font-size: 1rem;
            font-weight: normal;
            margin-bottom: 2rem;
            text-align: center;
            width: 60%;
        }

        .media-content .more {
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

        .media-content .more:hover {
            border: 1px solid #ffb700;
            color: #000;
            transition: all 0.3s ease;
        }

        .media-section {
            padding: 4rem 8rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .media-section .line {
            height: 2px;
            display: block;
            background-color: rgb(0, 0, 0);
            width: 60px;
            margin: 0 auto;
            margin-bottom: 1rem;
        }

        .media-section h2 {
            font-size: 1.1rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .media-section .sub {
            font-size: 0.9rem;
            font-weight: normal;
            margin-bottom: 4rem;
            width: 65%;
            text-align: center;
            display: block;
            margin: 0 auto 4rem auto;
        }

        .media-section .media-card {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 2rem;
            text-align: left;
            transition: all 0.3s ease;
            width: 100%;
            display: flex;
            flex-direction: column;
        }

        .media-section .media-card:hover {
            border-color: #ffb700;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .media-section .media-card .media-image {
            width: 100%;
            height: 200px;
            background-size: cover;
            background-position: center;
        }

        .media-section .media-card .media-info {
            padding: 1.5rem;
        }

        .media-section .media-card .media-info h3 {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .media-section .media-card .media-info p {
            font-size: 0.8rem;
            color: #555;
            margin-bottom: 1rem;
        }

        .media-section .media-card .media-info .media-date {
            font-size: 0.8rem;
            color: #777;
        }
    </style>
@endpush

@section('content')
    <section class="small-header">
        <div>
            <div class="line"></div>
            <h1>Centre des Médias</h1>
            <p>Explorez notre galerie de photos et vidéos, écoutez nos sermons et découvrez nos publications.</p>
        </div>
    </section>

    <section class="media-content">
        <span>Notre Contenu Multimédia</span>
        <h2>Plongez dans l'Univers de l'EMEC</h2>
        <p class="subtite">Bienvenue dans notre Centre des Médias, un espace dédié à l'exploration de notre contenu
            multimédia. Ici, vous trouverez une riche collection de sermons inspirants, de galeries de photos captivantes
            et de vidéos édifiantes qui témoignent de la vie et de l'œuvre de l'EMEC. Que vous cherchiez à revivre un
            moment spécial, à approfondir votre foi ou simplement à découvrir notre communauté, notre centre des médias
            est votre porte d'entrée.</p>
        <a href="#media-section" class="more">Voir les médias</a>
    </section>

    <section class="media-section" id="media-section">
        <div class="line"></div>
        <h2>Nos Derniers Médias</h2>
        <p class="sub">Découvrez nos dernières publications, sermons et événements en images et en vidéos. Restez connecté
            avec la vie de l'église et laissez-vous inspirer par la Parole de Dieu.</p>

        <div class="row">
            <div class="col-md-4">
                <div class="media-card">
                    <div class="media-image" style="background-image: url({{ asset('images/media-1.jpg') }});"></div>
                    <div class="media-info">
                        <h3>Sermon: La Puissance de la Foi</h3>
                        <p>Un message inspirant sur la manière dont la foi peut transformer nos vies et déplacer des
                            montagnes. Écoutez le Pasteur Samuel Dalle partager des vérités profondes.</p>
                        <span class="media-date">Publié le 25 Mars 2024</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="media-card">
                    <div class="media-image" style="background-image: url({{ asset('images/media-2.jpg') }});"></div>
                    <div class="media-info">
                        <h3>Galerie Photo: Culte de Pâques 2024</h3>
                        <p>Revivez les moments forts de notre culte spécial de Pâques 2024. Des photos captivantes de
                            louange, d'adoration et de communion fraternelle.</p>
                        <span class="media-date">Publié le 1er Avril 2024</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="media-card">
                    <div class="media-image" style="background-image: url({{ asset('images/media-3.jpg') }});"></div>
                    <div class="media-info">
                        <h3>Vidéo: Témoignages de Transformation</h3>
                        <p>Découvrez des témoignages poignants de membres de l'EMEC dont la vie a été transformée par
                            la grâce de Dieu. Une source d'encouragement et d'espoir.</p>
                        <span class="media-date">Publié le 15 Février 2024</span>
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
            const descriptions = document.querySelectorAll('.media-section .media-card .media-info p');

            descriptions.forEach(p => {
                if (p.textContent.length > maxLength) {
                    p.textContent = p.textContent.substring(0, maxLength).trim() + '...';
                }
            });
        });
    </script>
@endpush
