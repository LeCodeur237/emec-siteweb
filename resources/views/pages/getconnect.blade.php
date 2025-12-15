@extends('index')

@push('styles')
    <style>
        .small-header {
            background-image: linear-gradient(to top,
                    rgba(0, 0, 0, 0.832),
                    rgba(0, 0, 0, 0.75)),
                url({{ asset('images/home-2.jpg') }});
        }

        .get-connected-content {
            padding: 4rem 8rem;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .get-connected-content span {
            font-size: 1rem;
            font-weight: bold;
            margin-bottom: 2rem;
            text-align: center;
        }

        .get-connected-content h2 {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 2rem;
            text-align: center;
            width: 50%;
        }

        .get-connected-content .subtite {
            font-size: 1rem;
            font-weight: normal;
            margin-bottom: 2rem;
            text-align: center;
            width: 60%;
        }

        .get-connected-content .more {
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

        .get-connected-content .more:hover {
            border: 1px solid #ffb700;
            color: #000;
            transition: all 0.3s ease;
        }


        .form {
            padding: 4rem 8rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .img-form-connected {
            width: 50%;
            min-height: 70vh;
            /* Ajout d'une hauteur minimale pour que l'image soit visible */
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }

        .form-connected {
            width: 50%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: start;
        }

        .form-connected .form-container {
            width: 90%;
            max-width: 500px;
            padding: 4rem 3rem;
            background-color: rgba(255, 255, 255, 0.9);
            color: #333;
        }

        .form-connected form .form-group {
            margin-bottom: 1rem;
        }

        .form-connected form .form-group label {
            color: #333;
            display: block;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .form-connected form .form-group input,
        .form-connected form .form-group textarea {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            background-color: #f8f9fa;
            color: #333;
        }

        .form-connected form .form-group textarea {
            height: 100px;
        }

        .form-connected form button {
            background-color: #ffb700;
            color: #fff;
            padding: 0.8rem 2rem;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s ease;
            text-transform: uppercase;
            font-size: 14px;
            border: 2px solid transparent;
        }

        .form-connected form button:hover {
            background-color: #fff;
            color: #ffb700;
            border-color: #ffb700;
        }

        .actions {
            padding: 6rem 8rem;
            text-align: center;
        }

        .actions .line {
            height: 2px;
            display: block;
            background-color: rgb(0, 0, 0);
            width: 60px;
            margin: 0 auto;
            margin-bottom: 1rem;
        }

        .actions h2 {
            font-size: 1.1rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .actions .sub {
            font-size: 0.9rem;
            font-weight: normal;
            margin-bottom: 4rem;
            width: 65%;
            text-align: center;
            display: block;
            margin: 0 auto 4rem auto;

        }

        .actions .card {
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

        .actions .card h3 {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 1.2rem;
            color: #000;
        }

        .actions .card p {
            font-size: 0.9rem;
            line-height: 1.6;
            color: #555;
        }
    </style>
@endpush

@section('content')
    <section class="small-header">
        <div>
            <div class="line"></div>
            <h1>Connectez-vous à nous</h1>
            <p>Découvrez les différentes façons de vous engager avec l'EMEC et de grandir dans votre foi.</p>
        </div>
    </section>

    <section class="get-connected-content">
        <span>Rejoingnez nous</span>
        <h2>Nous sommes heureux de vous savoir ici.</h2>
        <p class="subtite">Rejoignez notre famille spirituelle et engagez-vous dans la vie de l'église. En tant que membre,
            vous
            aurez l'opportunité de servir, de grandir et de vous connecter avec d'autres croyants.</p>
        <a href="#form-connected" class="more">En savoir plus</a>
    </section>
    <section class="form">
        <div class="form-connected" id="form-connected">
            <div class="form-container">
                <p class="mb-4">Remplissez ce formulaire pour commencer votre parcours avec nous.</p>
                <form action="" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <label for="name">Nom complet</label>
                                <input type="text" name="name" id="name" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="country">Pays</label>
                                <input type="text" name="country" id="country" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="city">Ville de résidence</label>
                                <input type="text" name="city" id="city" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="neighborhood">Quartier</label>
                                <input type="text" name="neighborhood" id="neighborhood" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="phone">Numéro de téléphone</label>
                                <input type="tel" name="phone" id="phone" class="form-control">
                            </div>
                        </div>
                    </div>
                    <button type="submit">Envoyer</button>
                </form>
            </div>
        </div>
        <div class="img-form-connected" style="background-image: url({{ asset('images/connect-1.jpg') }})"></div>
    </section>
    <section class="actions">
        <div class="line"></div>
        <h2>Soutenez et soyez soutenu</h2>
        <p class="sub">Nous croyons en la puissance de la communauté et de l'engagement mutuel. Que vous cherchiez à vous
            connecter avec
            d'autres membres, à demander un soutien spirituel ou à contribuer à notre mission, nous avons des moyens pour
            vous.
        </p>
        <div class="row">
            <div class="col-md-6">
                <a href="tel:+237699765435" class="card">
                        <h3>Demande de Prière</h3>
                        <p>Confiez-nous vos sujets de prière. Notre équipe d'intercesseurs sont là pour vous
                            soutenir dans les moments de joie comme dans les épreuves.</p>
                    </a>
                </div>

                <div class="col-md-6">
                    <a href="/donate" class="card">
                        <h3>Dons et Offrandes</h3>
                        <p>Soutenez la mission de l'EMEC par vos dons et offrandes. Votre générosité nous aide à poursuivre
                            notre œuvre et à impacter des vies.</p>
                    </a>
                </div>
            </div>
        </section>
@endsection
