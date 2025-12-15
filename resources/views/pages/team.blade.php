@extends('index')

@push('styles')
    <style>
        .small-header {
            background-image: linear-gradient(to top,
                    rgba(0, 0, 0, 0.832),
                    rgba(0, 0, 0, 0.75)),
                url({{ asset('images/home-2.jpg') }});
        }

        .team-content {
            padding: 4rem 8rem;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .team-content span {
            font-size: 1rem;
            font-weight: bold;
            margin-bottom: 2rem;
            text-align: center;
        }

        .team-content h2 {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 2rem;
            text-align: center;
            width: 50%;
        }

        .team-content .subtite {
            font-size: 1rem;
            font-weight: normal;
            margin-bottom: 2rem;
            text-align: center;
            width: 60%;
        }

        .team-content .more {
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

        .team-content .more:hover {
            border: 1px solid #ffb700;
            color: #000;
            transition: all 0.3s ease;
        }

        .team-section {
            padding: 4rem 8rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .team-section .line {
            height: 2px;
            display: block;
            background-color: rgb(0, 0, 0);
            width: 60px;
            margin: 0 auto;
            margin-bottom: 1rem;
        }

        .team-section h2 {
            font-size: 1.1rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .team-section .sub {
            font-size: 0.9rem;
            font-weight: normal;
            margin-bottom: 4rem;
            width: 65%;
            text-align: center;
            display: block;
            margin: 0 auto 4rem auto;
        }

        .team-section .team-member {
            margin-bottom: 2rem;
            text-align: center;
        }

        .team-section .team-member .img-container {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            overflow: hidden;
            margin: 0 auto 1.5rem auto;
            background-position: center;
            background-size: cover;
            background-repeat: no-repeat;
            border: 5px solid #ffb700;
        }

        .team-section .team-member h3 {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .team-section .team-member p {
            font-size: 1rem;
            color: #555;
            margin-bottom: 1rem;
        }

        .team-section .team-member .social-links a {
            color: #ffb700;
            margin: 0 0.5rem;
            font-size: 1.5rem;
        }

        .team-section .team-member .social-links a:hover {
            color: #e6a200;
        }
    </style>
@endpush

@section('content')
    <section class="small-header">
        <div>
            <div class="line"></div>
            <h1>Notre Équipe</h1>
            <p>Découvrez les leaders et les serviteurs dévoués qui composent le cœur de l'EMEC.</p>
        </div>
    </section>

    <section class="team-content">
        <span>Nos Leaders</span>
        <h2>Rencontrez les Visages Dévoués de l'EMEC</h2>
        <p class="subtite">Notre équipe pastorale et nos leaders sont des hommes et des femmes de foi, engagés à servir
            Dieu et la communauté avec passion et intégrité. Chacun apporte une richesse de dons et d'expériences pour
            guider, enseigner et inspirer.</p>
        <a href="#team-section" class="more">Voir l'équipe</a>
    </section>

    <section class="team-section" id="team-section">
        <div class="line"></div>
        <h2>Leadership Pastoral</h2>
        <p class="sub">Notre équipe pastorale est dédiée à la croissance spirituelle et au bien-être de chaque membre de
            l'EMEC. Ils sont les bergers qui guident notre troupeau.</p>
        <div class="row">
            <div class="col-md-4">
                <div class="team-member">
                    <div class="img-container" style="background-image: url({{ asset('images/home-11.jpeg') }})"></div>
                    <h3>Apôtre Samuel DALLE</h3>
                    <p>Pasteur Principal & Fondateur</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="team-member">
                    <div class="img-container" style="background-image: url({{ asset('images/pastor-2.jpg') }})"></div>
                    <h3>Pasteur Jean-Pierre</h3>
                    <p>Co-Pasteur</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="team-member">
                    <div class="img-container" style="background-image: url({{ asset('images/pastor-3.jpg') }})"></div>
                    <h3>Pasteur Marie Claire</h3>
                    <p>Responsable des femmes</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="line"></div>
        <h2>Conseil d'Administration</h2>
        <p class="sub">Le Conseil d'Administration assure la bonne gouvernance et la gestion stratégique de l'EMEC,
            veillant à ce que nos opérations soient alignées sur notre mission et nos valeurs.</p>
        <div class="row">
            <div class="col-md-4">
                <div class="team-member">
                    <div class="img-container" style="background-image: url({{ asset('images/board-1.jpg') }})"></div>
                    <h3>M. Alain Dupont</h3>
                    <p>Président du Conseil</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="team-member">
                    <div class="img-container" style="background-image: url({{ asset('images/board-2.jpg') }})"></div>
                    <h3>Mme. Sophie Dubois</h3>
                    <p>Secrétaire Générale</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="team-member">
                    <div class="img-container" style="background-image: url({{ asset('images/board-3.jpg') }})"></div>
                    <h3>M. Marc Lefevre</h3>
                    <p>Trésorier</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
