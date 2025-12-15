@extends('index')

@push('styles')
    <style>
        .small-header {
            background-image: linear-gradient(to top,
                    rgba(0, 0, 0, 0.832),
                    rgba(0, 0, 0, 0.75)),
                url({{ asset('images/home-2.jpg') }});
        }

        .mandate-content {
            padding: 4rem 8rem;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .mandate-content span {
            font-size: 1rem;
            font-weight: bold;
            margin-bottom: 2rem;
            text-align: center;
        }

        .mandate-content h2 {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 2rem;
            text-align: center;
            width: 50%;
        }

        .mandate-content .subtite {
            font-size: 1rem;
            font-weight: normal;
            margin-bottom: 2rem;
            text-align: center;
            width: 60%;
        }

        .mandate-content .more {
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

        .mandate-content .more:hover {
            border: 1px solid #ffb700;
            color: #000;
            transition: all 0.3s ease;
        }

        .mandate-section {
            padding: 4rem 8rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .mandate-section .line {
            height: 2px;
            display: block;
            background-color: rgb(0, 0, 0);
            width: 60px;
            margin: 0 auto;
            margin-bottom: 1rem;
        }

        .mandate-section h2 {
            font-size: 1.1rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .mandate-section .sub {
            font-size: 0.9rem;
            font-weight: normal;
            margin-bottom: 4rem;
            width: 65%;
            text-align: center;
            display: block;
            margin: 0 auto 4rem auto;
        }

        .mandate-section .mandate-item {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 4rem;
        }

        .mandate-section .mandate-item .mandate-item-img {
            width: 40%;
            height: 60vh;
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            display: block;
        }

        .mandate-section .mandate-item .mandate-item-text {
            width: 50%;
            padding: 0 4rem;
            text-align: left;
        }

        .mandate-section .mandate-item .mandate-item-text h3 {
            font-size: 1.4rem;
            font-weight: bold;
            margin-bottom: 1.5rem;
        }

        .mandate-section .mandate-item .mandate-item-text p {
            font-size: 0.9rem;
            line-height: 1.6;
            color: #555;
        }

        .mandate-section .mandate-item:nth-child(even) .mandate-item-img {
            order: 2;
        }

        .mandate-section .mandate-item:nth-child(even) .mandate-item-text {
            order: 1;
            padding: 0 4rem;
            text-align: right;
        }
    </style>
@endpush

@section('content')
    <section class="small-header">
        <div>
            <div class="line"></div>
            <h1>Notre Mandat</h1>
            <p>Découvrez la mission divine qui nous a été confiée et que nous nous efforçons d'accomplir.</p>
        </div>
    </section>

    <section class="mandate-content">
        <span>Notre Mission</span>
        <h2>Le Grand Mandat : Notre Appel Divin</h2>
        <p class="subtite">À l'EMEC, nous sommes profondément enracinés dans le Grand Mandat que Jésus-Christ a confié à
            ses disciples. C'est notre raison d'être, la force motrice derrière toutes nos actions et le fondement de notre
            ministère. Nous nous engageons à proclamer l'Évangile, à faire des disciples et à manifester l'amour de Dieu
            dans le monde.</p>
        <a href="#mandate-section" class="more">En savoir plus</a>
    </section>

    <section class="mandate-section" id="mandate-section">

        <h2>Les Piliers de Notre Mandat</h2>
        <p class="sub">Notre mandat se décline en plusieurs aspects essentiels, chacun étant une facette de notre
            engagement à servir Dieu et l'humanité. Découvrez comment nous mettons en œuvre cet appel divin au quotidien.
        </p>

        <div class="mandate-item">
            <div class="mandate-item-img" style="background-image: url({{ asset('images/mandate-1.jpg') }})"></div>
            <div class="mandate-item-text">

                <h3>La Proclamation de l'Évangile</h3>
                <p>Nous croyons que la Bonne Nouvelle de Jésus-Christ est le message le plus puissant et le plus
                    transformateur pour l'humanité. Notre mandat principal est de proclamer cet Évangile sans
                    compromis, en invitant chacun à une relation personnelle avec Dieu. Nous utilisons divers moyens,
                    des cultes d'adoration aux événements d'évangélisation, pour partager la vérité et l'amour de
                    Christ.</p>
            </div>
        </div>

        <div class="mandate-item">
            <div class="mandate-item-img" style="background-image: url({{ asset('images/mandate-2.jpg') }})"></div>
            <div class="mandate-item-text">

                <h3>La Formation de Disciples</h3>
                <p>Au-delà de la conversion, notre mandat inclut la formation de disciples matures et engagés. Nous
                    offrons des enseignements bibliques solides, des programmes de mentorat et des groupes de maison
                    pour aider chaque croyant à grandir dans sa foi, à comprendre la Parole de Dieu et à développer
                    une relation profonde avec le Saint-Esprit. Notre objectif est d'équiper les disciples pour
                    qu'ils puissent à leur tour impacter leur entourage.</p>
            </div>
        </div>

        <div class="mandate-item">
            <div class="mandate-item-img" style="background-image: url({{ asset('images/mandate-3.jpg') }})"></div>
            <div class="mandate-item-text">

                <h3>Le Service Communautaire et la Compassion</h3>
                <p>Jésus nous a montré l'exemple du service et de la compassion. L'EMEC s'engage activement à
                    manifester l'amour de Dieu par des actions concrètes dans nos communautés. Cela inclut des
                    initiatives d'aide aux démunis, des programmes de soutien social, des visites aux malades et aux
                    prisonniers, et la promotion de la justice sociale. Nous croyons que la foi sans les œuvres est
                    morte.</p>
            </div>
        </div>

        <div class="mandate-item">
            <div class="mandate-item-img" style="background-image: url({{ asset('images/mandate-4.jpg') }})"></div>
            <div class="mandate-item-text">

                <h3>L'Adoration et la Louange</h3>
                <p>L'adoration est au cœur de notre vie d'église. Nous cherchons à offrir à Dieu un culte sincère et
                    passionné, reconnaissant sa grandeur et sa bonté. Nos moments de louange et d'adoration sont des
                    occasions de se connecter profondément avec le Créateur et de recevoir sa présence
                    transformante.</p>
            </div>
        </div>
    </section>
@endsection
