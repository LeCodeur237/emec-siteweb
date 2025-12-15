@extends('index')

@push('styles')
    <style>
        .small-header {
            background-image: linear-gradient(to top,
                    rgba(0, 0, 0, 0.832),
                    rgba(0, 0, 0, 0.75)),
                url({{ asset('images/home-2.jpg') }});
        }

        .about-content {
            padding: 4rem 8rem;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .about-content span {
            font-size: 1rem;
            font-weight: bold;
            margin-bottom: 2rem;
            text-align: center;
        }

        .about-content h2 {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 2rem;
            text-align: center;
            width: 50%;
        }

        .about-content .subtite {
            font-size: 1rem;
            font-weight: normal;
            margin-bottom: 2rem;
            text-align: center;
            width: 60%;
        }

        .about-content .more {
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

        .about-content .more:hover {
            border: 1px solid #ffb700;
            color: #000;
            transition: all 0.3s ease;
        }

        .about-section {
            padding: 4rem 8rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .about-section .line {
            height: 2px;
            display: block;
            background-color: rgb(0, 0, 0);
            width: 60px;
            margin: 0 auto;
            margin-bottom: 1rem;
        }

        .about-section h2 {
            font-size: 1.1rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .about-section .sub {
            font-size: 0.9rem;
            font-weight: normal;
            margin-bottom: 4rem;
            width: 65%;
            text-align: center;
            display: block;
            margin: 0 auto 4rem auto;
        }

        .about-section .col-md-4 {
            padding: 0.2rem;
        }

        .about-section .card {
            border: 1px solid #e0e0e0;
            border-radius: 1px;
            padding: 1rem 2rem;
            margin-bottom: 4 rem;
            transition: all 0.3s ease;
            width: 100%;
            margin: 0 auto;
            text-decoration: none;
            color: #000;
            height: 35vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .about-section .card h3 {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 1.2rem;
            color: #000;
        }

        .about-section .card p {
            font-size: 0.9rem;
            line-height: 1.6;
            color: #555;
        }

        .about-section .card:hover {
            border-color: #ffb700;
        }

        .vision-mission {
            padding: 4rem 8rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background-attachment: fixed;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .vision-mission .item {
            padding: 5rem 4rem;
            text-align: center;
            background-color: white;
            margin: 0;
            height: 60vh;
        }

        .vision-mission .bg-black {
            background-color: #000;
            color: #fff !important;
        }

        .vision-mission .text-white {
            color: #fff !important;
        }

        .vision-mission .item h3 {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 1rem;
            color: #000;
        }

        .vision-mission .item p {
            font-size: 1rem;
            line-height: 1.6;
            color: #000;
        }

        .visionnaire-section {
            padding: 4rem 8rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .visionnaire-section .img-visionnaire {
            width: 100%;
            padding-top: 125%;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            border-radius: 12px;
            display: block;
        }

        .visionnaire-section .desc-vision {
            display: flex;
            flex-direction: column;
            justify-content: center;
            height: 100%;
            padding-left: 4rem;
        }

        .visionnaire-section .desc-vision .subtitle {
            font-size: 1rem;
            font-weight: bold;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 0.5rem;
        }

        .visionnaire-section .desc-vision h2 {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .visionnaire-section .desc-vision .title-visionnaire {
            font-size: 15px;
            font-weight: normal;
            color: #495057;
            margin-bottom: 2rem;
        }

        .visionnaire-section .vision-text {
            margin-top: 2rem;
            border-left: 3px solid #ffb700;
            padding-left: 1.5rem;
        }

        .visionnaire-section .vision-text p {
            font-size: 1rem;
            line-height: 1.6;
            text-align: justify;
            color: #343a40;
            margin-bottom: 1rem;
        }
    </style>
@endpush

@section('content')
    <section class="small-header">
        <div>
            <div class="line"></div>
            <h1>À propos de nous</h1>
            <p>Découvrez notre histoire, notre mission et les valeurs qui nous animent.</p>
        </div>
    </section>

    <section class="about-content">
        <span>Notre Histoire</span>
        <h2>L'EMEC : Une Histoire de Foi, de Croissance et d'Impact</h2>
        <p class="subtite">L'Église Messianique Évangélique du Cameroun (EMEC) a été fondée sur des principes solides de
            foi et de dévotion. Depuis ses humbles débuts, elle s'est développée pour devenir une communauté dynamique,
            dédiée à la propagation de l'Évangile et au service de ses membres et de la société.</p>
        <a href="#visionnaire-section" class="more">En savoir plus</a>
    </section>

    <section class="about-section">
        <div class="line"></div>
        <h2>Nos Valeurs Fondamentales</h2>
        <p class="sub">À l'EMEC, nos actions sont guidées par des valeurs chrétiennes profondes qui façonnent notre
            identité et notre engagement envers Dieu et envers les autres. Ces valeurs sont le pilier de notre communauté
            et de notre ministère.</p>
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <h3>La Foi en Jésus-Christ</h3>
                    <p>Nous croyons en Jésus-Christ comme notre Seigneur et Sauveur, et notre foi est le fondement de tout
                        ce que nous faisons.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <h3>La Parole de Dieu</h3>
                    <p>La Bible est notre guide infaillible, la source de toute vérité et l'autorité finale pour notre foi
                        et notre vie.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <h3>L'Amour Fraternel</h3>
                    <p>Nous nous engageons à vivre dans l'amour, l'unité et le respect mutuel, reflétant ainsi l'amour de
                        Christ pour son Église.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <h3>Le Service et la Compassion</h3>
                    <p>Nous sommes appelés à servir Dieu et notre prochain avec compassion, en répondant aux besoins
                        spirituels et physiques de notre communauté.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <h3>L'Évangélisation et la Mission</h3>
                    <p>Nous avons à cœur de partager l'Évangile et de faire des disciples de toutes les nations, en
                        accomplissant le grand mandat de Christ.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <h3>La Sainteté et l'Intégrité</h3>
                    <p>Nous aspirons à une vie de sainteté et d'intégrité, cherchant à honorer Dieu dans toutes nos actions
                        et nos pensées.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="vision-mission"
        style="background-image: linear-gradient(to right,
                    rgba(0, 0, 0, 0.644),
                    rgba(0, 0, 0, 0.527)), url({{ asset('images/home-10.jpg') }})">
        <div class="row">
            <div class="col-md-6">
                <div class="item">
                    <h3>Notre Vision</h3>
                    <p>Devenir une église dynamique et influente, transformant des vies et des communautés par la puissance
                        de l'Évangile, et rayonnant l'amour de Christ dans le monde entier.</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="item bg-black">
                    <h3 class="text-white">Notre Mission</h3>
                    <p class="text-white">Proclamer la bonne nouvelle de Jésus-Christ, faire des disciples de toutes les
                        nations, les baptiser et les enseigner à observer tout ce qu'il a
                        prescrit, tout en manifestant l'amour de Dieu par des œuvres de compassion, de justice et de
                        paix.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="visionnaire-section" id="visionnaire-section">
        <div class="row align-items-center">
            <div class="col-md-5">
                <div class="img-visionnaire" style="background-image: url({{ asset('images/home-11.jpeg') }})"></div>
            </div>
            <div class="col-md-7">
                <div class="desc-vision">
                    <span class="subtitle">Le visionnaire</span>
                    <h2>Apôtre Samuel DALLE</h2>
                    <p class="title-visionnaire">Fondateur de la communauté EMEC</p>

                    <div class="vision-text">
                        <p>L'Apôtre Samuel Dalle est le berger principal et le visionnaire derrière l'Église Messianique
                            Évangélique du Cameroun (EMEC). Animé par une passion inébranlable pour l'Évangile et un cœur
                            dévoué au service de Dieu, il a fondé l'EMEC avec une vision claire : établir un lieu où la
                            Parole de Dieu est prêchée avec puissance, où les vies sont transformées et où la communauté
                            grandit dans la foi et l'amour.</p>
                        <p>Sous sa direction inspirée, l'EMEC est devenue un phare d'espoir et de transformation, touchant
                            d'innombrables vies à travers le Cameroun et au-delà. Son enseignement profond et pratique de la
                            Bible, combiné à son leadership charismatique, a permis à l'église de prospérer et d'étendre son
                            influence spirituelle.</p>
                        <p>L'Apôtre Samuel Dalle est également reconnu pour son engagement envers la justice sociale et la
                            compassion. Il incarne les valeurs de l'EMEC en œuvrant activement pour le bien-être des
                            communautés, en soutenant les initiatives éducatives et en apportant de laide aux plus démunis.
                            Sa vision est de voir chaque membre de l'EMEC devenir un disciple mature de Christ, équipé pour
                            impacter positivement son environnement et glorifier Dieu.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
