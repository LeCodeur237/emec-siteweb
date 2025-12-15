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

        .team-section .fondateurs {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 4rem;
        }

        .team-section .fondateurs .text-team {
            width: 60%;
            text-align: start;
            padding: 2rem 5rem;
        }

        .team-section .fondateurs .img-team-fondateur {
            width: 40%;
            height: 65vh;
            justify-content: center;
            align-items: center;
            display: flex;
        }

        .team-section .fondateurs .img-team {
            width: 60%;
            height: 100%;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }

        .team-section .fondateurs .text-team .line {
            height: 2px;
            display: block;
            background-color: rgb(0, 0, 0);
            width: 50px;
            margin-bottom: 1rem;
        }

        .team-section .fondateurs .text-team h2 {
            font-size: 1.1rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .team-section .fondateurs .text-team .sub {
            font-size: 0.9rem;
            font-weight: normal;
            margin-bottom: 4rem;
            display: block;
        }

        .team-section .fondateurs .text-team .more {
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

        .team-section .fondateurs .text-team .more:hover {
            border: 1px solid #ffb700;
            color: #000;
            transition: all 0.3s ease;

        }

        .organes {
            padding: 4rem 8rem;
            background-color: #f5f5f5;
        }

        .organes h2 {
            font-size: 1.5rem;
            font-weight: bold;
            border-top: 2px solid #000;
            padding-top: 0.5rem;
            margin-bottom: 2rem;
        }

        .organes ul {
            list-style: none;
            padding: 0;
            /* Added for consistent spacing between collapse items */
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .organes ul li {
            background-color: white;
            border: 1px solid #e0e0e0; /* Added for visual definition */
            padding: 1rem;
            border-radius: 4px;
        }

        .organes ul li a {
            color: #000;
            text-decoration: none;
        }

        .organes ul li a span {
            /* This targets the title span */
            display: block;
            font-size: 1.2rem;
            font-weight: bold;
        }

        .organes ul li .collapse .desc { /* Updated selector for the description span */
            font-size: 0.9rem;
            font-weight: normal;
            color: #666;
            margin-top: 0.5rem; /* Add some spacing above the description */
            padding-top: 0.5rem; /* Add padding for visual separation */
            border-top: 1px solid #eee; /* Add a subtle line above the description */
        }

        .carte-organe {
            width: 100%;
            height: 100%;
            background-color: white;
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1rem;
        }

        .carte-organe .membre {
            width: 20%;
            height: 200px;
        }
        .carte-organe .membre .img-membre {
            width: 100%;
            height: 80%;
        }
        .carte-organe .membre h3 {
            font-size: 0.6rem;
            font-weight: bold;
            margin-top: 1rem;
        }
        .carte-organe .membre p {
            font-size: 0.3rem;
            font-weight: normal;
            margin-top: 0.5rem;
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
        <div class="fondateurs">
            <div class="text-team">
                <div class="line"></div>
                <h2>Les Fondateurs</h2>
                <p class="sub">Le Pasteur Samuel Dalle et son épouse, la Pasteur Grâce Dalle, sont les fondateurs et les
                    piliers spirituels de l'EMEC. Leur vision et leur dévouement ont permis à cette église de devenir un
                    lieu de croissance spirituelle et d'impact communautaire.
                </p>
                <a href="" class="more">Voir les fondateurs</a>
            </div>
            <div class="img-team-fondateur">
                <div class="img-team" style="background-image: url({{ asset('images/home-10.jpg') }})"></div>
            </div>
        </div>

        <div class="fondateurs">
            <div class="img-team-fondateur">
                <div class="img-team" style="background-image: url({{ asset('images/home-10.jpg') }})"></div>
            </div>
            <div class="text-team">
                <div class="line"></div>
                <h2>Conseil Exécutif</h2>
                <p class="sub">Le Conseil Exécutif de l'EMEC est composé de leaders dévoués qui travaillent en étroite
                    collaboration avec les fondateurs pour assurer la bonne marche de l'église. Ils sont responsables de la
                    mise en œuvre de la vision, de la gestion des affaires de l'église et du soutien aux différents
                    ministères.
                </p>
                <a href="" class="more">Voir le conseil</a>
                </p>
            </div>
        </div>
    </section>
    <section class="organes">
        <h2>Autres organes de l'EMEC</h2>
        <div class="row">
            <div class="col-md-12">
                <ul>
                    <li>
                        <a class="d-block" data-bs-toggle="collapse" href="#collapseFemmes" role="button" aria-expanded="false" aria-controls="collapseFemmes">
                            <span>Groupe des Femmes</span>
                        </a>
                        <div class="collapse" id="collapseFemmes">
                            <span class="desc">
                                Le ministère des femmes de l'EMEC, offrant un espace de croissance spirituelle, de soutien
                                mutuel et d'engagement communautaire pour toutes les femmes de l'église.
                            </span>
                        </div>
                    </li>
                    <li>
                        <a class="d-block" data-bs-toggle="collapse" href="#collapseEcodim" role="button" aria-expanded="false" aria-controls="collapseEcodim">
                            <span>ECODIM</span>
                        </a>
                        <div class="collapse" id="collapseEcodim">
                            <span class="desc">
                                Le ministère de l'école du dimanche de l'EMEC, offrant un enseignement biblique adapté aux
                                enfants de tous âges.
                            </span>
                        </div>
                    </li>
                    <li>
                        <a class="d-block" data-bs-toggle="collapse" href="#collapseJeunes" role="button" aria-expanded="false" aria-controls="collapseJeunes">
                            <span>Jeunes Pour Christ</span>
                        </a>
                        <div class="collapse" id="collapseJeunes">
                            <span class="desc">
                                Le ministère des jeunes de l'EMEC, dédié à l'épanouissement spirituel et social des jeunes.
                            </span>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </section>
@endsection
