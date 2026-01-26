@extends('index')

@push('styles')
    <style>
        .small-header {
            background-image: linear-gradient(to top,
                    rgba(0, 0, 0, 0.832),
                    rgba(0, 0, 0, 0.75)),
                url({{ asset('images/home-2.jpg') }});
        }

        .project-content {
            padding: 4rem 8rem;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .project-content span {
            font-size: 1rem;
            font-weight: bold;
            margin-bottom: 2rem;
            text-align: center;
        }

        .project-content h2 {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 2rem;
            text-align: center;
            width: 50%;
        }

        .project-content .subtite {
            font-size: 1rem;
            font-weight: normal;
            margin-bottom: 2rem;
            text-align: center;
            width: 60%;
        }

        .project-content .more {
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

        .project-content .more:hover {
            border: 1px solid #ffb700;
            color: #000;
            transition: all 0.3s ease;
        }

        .project-section {
            padding: 4rem 8rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            background-color: #f8f9fa;
        }

        .project-section .line {
            height: 2px;
            display: block;
            background-color: rgb(0, 0, 0);
            width: 60px;
            margin: 0 auto;
            margin-bottom: 1rem;
        }

        .project-section h2 {
            font-size: 1.1rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .project-section .sub {
            font-size: 0.9rem;
            font-weight: normal;
            margin-bottom: 4rem;
            width: 65%;
            text-align: center;
            display: block;
            margin: 0 auto 4rem auto;
        }

        .project-card {
            background: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 2rem;
            text-align: left;
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .project-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .project-card .project-image {
            width: 100%;
            height: 220px;
            background-size: cover;
            background-position: center;
        }

        .project-card .project-info {
            padding: 1.5rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .project-card .project-info h3 {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .project-card .project-info p {
            font-size: 0.9rem;
            color: #555;
            margin-bottom: 1.5rem;
            flex-grow: 1;
        }

        .project-card .project-info .project-link {
            font-size: 0.9rem;
            font-weight: bold;
            color: #ffb700;
            text-decoration: none;
            align-self: flex-start;
        }

        .project-card .project-info .project-link:hover {
            text-decoration: underline;
        }

        @media (max-width: 992px) {
            .project-content, .project-section {
                padding: 4rem 1.5rem;
            }

            .project-content h2,
            .project-content .subtite,
            .project-section .sub {
                width: 100%;
            }
        }
    </style>
@endpush

@section('content')
    <section class="small-header">
        <div>
            <div class="line"></div>
            <h1>Nos Projets</h1>
            <p>Découvrez comment nous mettons notre foi en action à travers des projets concrets.</p>
        </div>
    </section>

    <section class="project-content">
        <span>Notre Engagement</span>
        <h2>Construire, Servir, Transformer</h2>
        <p class="subtite">À l'EMEC, nous croyons que la foi doit se traduire par des actions concrètes qui glorifient Dieu et servent notre prochain. Nos projets sont le reflet de cet engagement. Ils visent à répondre aux besoins spirituels, sociaux et matériels de nos communautés, en apportant espoir, soutien et transformation.</p>
        <a href="#project-section" class="more">Découvrir les projets</a>
    </section>

    <section class="project-section" id="project-section">
        <div class="line"></div>
        <h2>Projets en Cours et à Venir</h2>
        <p class="sub">Explorez les initiatives que nous menons actuellement et celles que nous projetons pour l'avenir. Chaque projet est une opportunité de faire une différence et de manifester l'amour de Christ.</p>

        <div class="row">
            <div class="col-lg-4 col-md-6 col-12 mb-4">
                <div class="project-card">
                    <div class="project-image" style="background-image: url({{ asset('images/project-1.jpg') }});"></div>
                    <div class="project-info">
                        <h3>Construction du Temple de Yaoundé</h3>
                        <p>Un projet ambitieux pour bâtir un nouveau lieu de culte moderne et accueillant pour notre communauté grandissante à Yaoundé. Ce temple sera un centre de prière, d'enseignement et de communion.</p>
                        <a href="#" class="project-link">En savoir plus &rarr;</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-12 mb-4">
                <div class="project-card">
                    <div class="project-image" style="background-image: url({{ asset('images/project-2.jpg') }});"></div>
                    <div class="project-info">
                        <h3>Programme de Soutien aux Orphelins</h3>
                        <p>Nous apportons un soutien matériel, éducatif et spirituel aux orphelins et enfants vulnérables. Notre objectif est de leur offrir un avenir meilleur et de leur montrer l'amour inconditionnel de Dieu.</p>
                        <a href="#" class="project-link">En savoir plus &rarr;</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-12 mb-4">
                <div class="project-card">
                    <div class="project-image" style="background-image: url({{ asset('images/project-3.jpg') }});"></div>
                    <div class="project-info">
                        <h3>Centre de Formation Professionnelle</h3>
                        <p>Ce centre vise à équiper les jeunes et les adultes avec des compétences pratiques (couture, informatique, etc.) pour favoriser leur autonomie et leur insertion professionnelle.</p>
                        <a href="#" class="project-link">En savoir plus &rarr;</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-12 mb-4">
                <div class="project-card">
                    <div class="project-image" style="background-image: url({{ asset('images/project-4.jpg') }});"></div>
                    <div class="project-info">
                        <h3>Campagnes d'Évangélisation et Médicales</h3>
                        <p>Nous organisons régulièrement des campagnes dans les zones rurales pour partager l'Évangile et offrir des consultations médicales gratuites et des médicaments aux populations démunies.</p>
                        <a href="#" class="project-link">En savoir plus &rarr;</a>
                    </div>
                </div>
            </div>
             <div class="col-lg-4 col-md-6 col-12 mb-4">
                <div class="project-card">
                    <div class="project-image" style="background-image: url({{ asset('images/project-5.jpg') }});"></div>
                    <div class="project-info">
                        <h3>Projet Agricole Communautaire</h3>
                        <p>Lancement d'une ferme communautaire pour lutter contre l'insécurité alimentaire, promouvoir des techniques agricoles durables et générer des revenus pour les familles locales.</p>
                        <a href="#" class="project-link">En savoir plus &rarr;</a>
                    </div>
                </div>
            </div>
             <div class="col-lg-4 col-md-6 col-12 mb-4">
                <div class="project-card">
                    <div class="project-image" style="background-image: url({{ asset('images/project-6.jpg') }});"></div>
                    <div class="project-info">
                        <h3>Accès à l'Eau Potable</h3>
                        <p>Construction de puits et de forages dans les villages qui manquent d'accès à une source d'eau potable saine, améliorant ainsi la santé et la qualité de vie des habitants.</p>
                        <a href="#" class="project-link">En savoir plus &rarr;</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const maxLength = 120;
        const descriptions = document.querySelectorAll('.project-section .project-card .project-info p');

        descriptions.forEach(p => {
            if (p.textContent.length > maxLength) {
                p.textContent = p.textContent.substring(0, maxLength).trim() + '...';
            }
        });
    });
</script>
@endpush
