@extends('index')

@push('styles')
    <style>
        .header-home {
            display: flex;
            justify-content: space-around;
            align-items: stretch;
            height: 95vh;
            background-repeat: no-repeat;
            background-size: cover;
            background-position: top;
            /* Ajouté pour un meilleur centrage de l'image */
            padding: 10rem 5.5rem;
            background-attachment: fixed;
            color: #fff;
        }

        .left-img {
            display: flex;
            flex-direction: column;
            justify-content: stretch;
            align-items: flex-start;
            text-align: start;
            flex: 0 0 50%;
        }

        .left-img h1 {
            font-size: 3rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .buttons {
            display: flex;
            align-items: center;
            width: 75%;
            margin-block: 2rem;
        }

        .buttons .call-to-action {
            color: #000;
            padding: 1.2rem 2rem;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s ease;
            text-transform: uppercase;
            font-size: 14px;
            margin-inline: 0.1rem;
        }

        .buttons .call-to-action:first-child {
            background-color: #fffb00;
            color: #000;
        }

        .buttons .call-to-action:last-child {
            background-color: #ffffff;
            color: #000;
        }

        .announce {
            flex: 0 0 50%;
            display: flex;
            justify-content: end;
            align-items: center
        }

        .block-announce {
            display: flex;
            flex-direction: column;
            width: 65%;
        }

        .block-announce .title {
            font-size: 1rem;
            font-weight: bold;
            margin-bottom: 1.5rem;
            color: #fffb00;
            padding-bottom: 0.2rem;
            border-bottom: 2px solid #fffb00;
            width: 40%;
        }

        .block-announce .carte {
            width: 100%;
            padding: 1rem 2rem;
            color: white;
            background-color: transparent;
            border-top-left-radius: 1rem;
            border-bottom-right-radius: 1rem;
            border-color: white;
            border-style: solid;
            border-width: 1px;
        }

        .block-announce .sign {
            font-size: 0.8rem;
            text-align: end;
            margin-top: 1rem;
            font-weight: bold;
            display: block;
        }

        .about-us {
            padding: 10rem 8rem;
        }

        .about-us .text-about {
            display: block;
        }

        .about-us .text-about .title {
            font-size: 1rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .about-us .text-about p {
            font-size: 1rem;
        }

        .about-us .img-presentation {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
        }

        .about-us .conten {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .about-us .img-presentation .img-about {
            width: 75%;
            height: 120vh;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            display: block;
            border-radius: 10px;
        }

        .getconnected {
            padding: 4rem 8rem;
            margin-top: 1rem;
            color: #fff;
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
        }

        .getconnected .ss {
            display: flex;
            flex-direction: column;
            align-items: start;
            justify-content: center;
        }

        .getconnected .text-connect {
            width: 90%;
        }

        .getconnected .text-connect h2 {
            font-size: 25px;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .getconnected .text-connect p {
            font-size: 14px;
            font-weight: normal;
            margin-bottom: 2rem;
        }

        .getconnected .text-connect .call-to-action {
            padding: 1rem 1.5rem;
            text-transform: uppercase;
            background: #fffb00;
            text-decoration: none;
            font-weight: 500;
            color: #000;
        }

        .imgs-church {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-around;
        }

        .imgs-church .img-church {
            width: 30%;
            height: 50vh;
            border-radius: 10px;
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
        }

        .mandate {
            padding: 6rem 8rem;
        }

        .mandate .postion {
            top: -80px;
            position: relative;
        }

        .mandate .postion2 {
            top: -120px;
            position: relative;
        }

        .mandate h2 {
            font-size: 1rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .mandate p {
            width: 40%;
            font-size: 15px;
            font-weight: normal;
            margin-bottom: 2rem;
        }

        .mandate .call-to-action {
            padding: 0.8rem 1.5rem;
            text-transform: uppercase;
            background: transparent;
            border: 2px solid #000;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            color: #000;
        }

        .mandate .mandate-right {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: end;
            margin-top: 4rem;
        }

        .mandate .mandate-left {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: start;
            margin-top: 4rem;
        }

        .mandate .mandate-right .mandate-item {
            width: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 65vh;
        }

        .mandate .mandate-left .mandate-item {
            width: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 65vh;
        }

        .mandate .mandate-right .mandate-item .mandate-item-img {
            width: 50%;
            height: 100%;
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            display: block;
        }

        .mandate .mandate-left .mandate-item .mandate-item-img {
            width: 50%;
            height: 100%;
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            display: block;
        }

        .mandate .mandate-right .mandate-item .mandate-item-text {
            width: 50%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
            padding: 1rem;
        }

        .mandate .mandate-left .mandate-item .mandate-item-text {
            width: 50%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
            padding: 1rem;
        }

        .mandate .mandate-right .mandate-item .mandate-item-text h3 {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 2rem;
        }

        .mandate .mandate-left .mandate-item .mandate-item-text h3 {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 2rem;
        }

        .mandate .mandate-right .mandate-item .mandate-item-text p {
            font-size: 15px;
            font-weight: normal;
            margin-bottom: 2rem;
            width: 100%;
        }

        .mandate .mandate-left .mandate-item .mandate-item-text p {
            font-size: 15px;
            font-weight: normal;
            margin-bottom: 2rem;
            width: 100%;
        }

        .church-day {
            padding: 6rem 8rem;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            color: #fff;
        }

        .church-day .d-flex {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 5rem;
        }

        .church-day .call-to-action {
            padding: 1rem 1.5rem;
            text-transform: uppercase;
            background: #fffb00;
            text-decoration: none;
            font-weight: 500;
            color: #000;
        }

        .church-day h2 {
            font-size: 1rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .church-day p {
            width: 60%;
            font-size: 15px;
            font-weight: normal;
        }

        .church-day .carte {
            width: 100%;
            padding: 3rem 2rem;
            border-radius: 10px;
            background-color: rgba(2, 31, 90, 0.356);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .church-day .carte .divider {
            width: 100%;
            height: 1px;
            background-color: #fff;
            margin-top: 0.5rem;
        }


        .church-day .carte:hover {
            background-color: rgba(2, 31, 90, 0.86);
            transition: all 0.3s ease;
        }

        .church-day .carte .img-carte {
            width: 80px;
            height: 80px;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            border-radius: 50%;
            display: block;
            margin-bottom: 2rem;
        }

        .church-day .carte h3 {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .church-day .carte p {
            font-size: 15px;
            font-weight: normal;
            margin-bottom: 2rem;
            width: 100%;
            text-align: center;
        }

        .visionnaire {
            padding: 6rem 8rem;
            background-color: #f8f9fa;
            /* Ajout d'un fond léger */
        }

        .visionnaire .img-visionnaire {
            width: 100%;
            padding-top: 125%;
            /* Ratio d'aspect pour une image portrait */
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            border-radius: 12px;
            display: block;
        }

        .visionnaire .desc-vision {
            display: flex;
            flex-direction: column;
            justify-content: center;
            height: 100%;
            padding-left: 4rem;
        }

        .visionnaire .desc-vision .subtitle {
            font-size: 1rem;
            font-weight: bold;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 0.5rem;
        }

        .visionnaire .desc-vision h2 {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .visionnaire .desc-vision .title-visionnaire {
            font-size: 15px;
            font-weight: normal;
            color: #495057;
            margin-bottom: 2rem;
        }

        .visionnaire .vision-text {
            margin-top: 2rem;
            border-left: 3px solid #fffb00;
            padding-left: 1.5rem;
        }

        .visionnaire .vision-text p {
            font-size: 1rem;
            line-height: 1.6;
            text-align: justify;
            color: #343a40;
            margin-bottom: 1rem;
        }
    </style>
@endpush
@section('content')
    <section class="header-home"
        style="background-image: linear-gradient(to top, rgba(0, 0, 0, 0.832), rgba(0, 0, 0, 0.75)), url({{ asset('images/home-1.jpg') }})">
        <div class="left-img">
            <h1>Eglise Méssiannique Evangélique du Cameroun.</h1>
            <p>Un lieu de transformation , de restauration et d'épanouissement. Un lieu où les vies sont bâtis, les miracles
                sont opérés, les délivrances se vivent. Tout sous la Parole de Dieu et la Conduite du Saint Esprit.
            </p>
            <div class="buttons">
                <a href="#aboutus" class="call-to-action scroll-to">En Savoir plus</a>
                <a href="/get-connected" class="call-to-action">Je veux suivre Christ</a>
            </div>
        </div>
        <div class="announce">
            <div class="block-announce">
                <span class="title">Annonce</span>
                <div class="carte">
                    <p>Le PCE annonce une grande Campagne d'évangélisation dans la ville de Bertoua du 12 au 18 Janvier
                        2026.</p>
                </div>
                <span class="sign">Le Sécrétariat</span>
            </div>
    </section>

    <section class="about-us" id="aboutus">
        <div class="row">
            <div class="col-5 conten">
                <div class="text-about">
                    <h2 class="title">Qui sommes-nous ?</h2>
                    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Nobis cumque fugit libero excepturi debitis
                        natus porro expedita quia blanditiis amet odio hic, vero omnis itaque a illo repellendus quos
                        aliquid.</p>
                    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Nobis cumque fugit libero excepturi debitis
                        natus porro expedita quia blanditiis amet odio hic, vero omnis itaque a illo repellendus quos
                        aliquid.</p>
                    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Nobis cumque fugit libero excepturi debitis
                        natus porro expedita quia blanditiis amet odio hic, vero omnis itaque a illo repellendus quos
                        aliquid.</p>
                    <blockquote>
                        Mais vous, vous êtes une race choisie, un sacerdoce royal, une nation sainte,
                        un peuple acquis, afin que vous annonciez les vertus de celui qui vous a appelés des
                        ténèbres à son admirable lumière.
                        <span class="author"><i>1 Pierre 2:9</i></span>
                    </blockquote>
                </div>
            </div>
            <div class="col-7">
                <div class="img-presentation">
                    <div class="img-about" style="background-image: url({{ asset('images/home-7.jpg') }})"></div>
                </div>
            </div>
        </div>
    </section>

    <section class="getconnected"
        style="background-image: linear-gradient(to top, rgba(0, 0, 0, 0.832), rgba(0, 0, 0, 0.75)), url({{ asset('images/home-2.jpg') }})">
        <div class="row">
            <div class="col-6 ss">
                <div class="text-connect">
                    <h2>Come and Let's Live Our Faith Together!</h2>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsum officiis quae veniam porro aliquid?
                        Doloribus, ut distinctio itaque laudantium aliquid laborum. Alias asperiores consequatur ex
                        incidunt,
                        voluptatem consequuntur eligendi magni!</p>
                    <a href="" class="call-to-action">Nous Rejoindre</a>
                </div>
            </div>
            <div class="col-6">
                <div class="imgs-church">
                    <div class="img-church" style="background-image: url({{ asset('images/home-4.jpg') }})"></div>
                    <div class="img-church mt-5" style="background-image: url({{ asset('images/home-6.jpg') }})"></div>
                    <div class="img-church" style="background-image: url({{ asset('images/home-5.jpg') }})"></div>
                </div>
            </div>
        </div>
    </section>

    <section class="mandate">
        <h2>The Mandate</h2>
        <p>Notre mandat est d’annoncer l’Évangile, faire des disciples, les baptiser, les enseigner, et manifester l’amour
            de Dieu par la compassion, la justice et la paix afin de glorifier Christ.
        </p>
        <a href="" class="call-to-action">En savoir plus</a>
        <div class="mandate-right">
            <div class="mandate-item">
                <div class="mandate-item-text">
                    <h3>Annoncer l'Évangile de Jésus-Christ</h3>
                    <p>Nous sommes appelés à proclamer la bonne nouvelle du salut par Jésus-Christ à toutes les nations, en
                        invitant chacun à la repentance et à la foi.</p>
                </div>
                <div class="mandate-item-img" style="background-image: url({{ asset('images/home-8.jpg') }})"></div>
            </div>
        </div>
        <div class="mandate-left postion">
            <div class="mandate-item">
                <div class="mandate-item-text">
                    <h3>Les baptiser au nom du Père, du Fils et du Saint-Esprit</h3>
                    <p>Le baptême est un acte d'obéissance et un témoignage public de notre foi en Jésus-Christ, symbolisant
                        notre identification avec sa mort, son ensevelissement et sa résurrection.</p>
                </div>
                <div class="mandate-item-img" style="background-image: url({{ asset('images/home-9.jpg') }})"></div>
            </div>
        </div>
    </section>

    <section class="church-day"
        style="background-image: linear-gradient(to top, rgba(0, 3, 43, 0.949), rgba(0, 5, 32, 0.897)), url({{ asset('images/home-2.jpg') }})">
        <div class="d-flex">
            <div class="titre">
                <h2>Nos programmes</h2>
                <p>
                    Découvrez un programme riche en enseignements bibliques, en prières ferventes et en partages fraternels.
                </p>
            </div>
            <a class="call-to-action">Rejoindre une eglise</a>
        </div>
        <div class="row">
            <div class="col-3">
                <div class="carte">
                    <div class="img-carte" style="background-image: url({{ asset('images/home-1.jpg') }})"></div>
                    <h3>Dimanche</h3>
                    <p>Culte de Célébration de 9h00 à 12h30</p>
                    <div class="divider"></div>
                </div>
            </div>
            <div class="col-3">
                <div class="carte">
                    <div class="img-carte" style="background-image: url({{ asset('images/home-2.jpg') }})"></div>
                    <h3>Lundi</h3>
                    <p>Ecole de Sion de 17h30 à 20h00</p>
                    <div class="divider"></div>
                </div>
            </div>
            <div class="col-3">
                <div class="carte">
                    <div class="img-carte" style="background-image: url({{ asset('images/home-3.jpg') }})"></div>
                    <h3>Mercredi</h3>
                    <p>Jour d'enseignements de 17h30 à 20h00</p>
                    <div class="divider"></div>
                </div>
            </div>
            <div class="col-3">
                <div class="carte">
                    <div class="img-carte" style="background-image: url({{ asset('images/home-4.jpg') }})"></div>
                    <h3>Vendredi</h3>
                    <p>Jour de prières de 17h30 à 20h00</p>
                    <div class="divider"></div>
                </div>
            </div>
        </div>
    </section>

    <section class="visionnaire" id="visionnaire">
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
                        <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Nisi officiis fugit repellendus dolore
                            laudantium itaque et, labore at iusto adipisci eum ab maiores nostrum eos deserunt quasi
                            corrupti quas ea.</p>
                        <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Nisi officiis fugit repellendus dolore
                            laudantium itaque et, labore at iusto adipisci eum ab maiores nostrum eos deserunt quasi
                            corrupti quas ea.</p>
                        <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Nisi officiis fugit repellendus dolore
                            laudantium itaque et, labore at iusto adipisci eum ab maiores nostrum eos deserunt quasi
                            corrupti quas ea.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
