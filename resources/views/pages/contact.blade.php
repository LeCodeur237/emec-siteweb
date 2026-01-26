@extends('index')

@push('styles')
    <style>
        .small-header {
            background-image: linear-gradient(to top,
                    rgba(0, 0, 0, 0.832),
                    rgba(0, 0, 0, 0.75)),
                url({{ asset('images/home-2.jpg') }});
        }

        .contact-section {
            padding: 5rem 0;
            background-color: #fff;
        }

        .contact-card {
            padding: 2.5rem;
            text-align: center;
            border-radius: 10px;
            background: #f8f9fa;
            height: 100%;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid #eee;
        }

        .contact-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border-color: #ffb700;
        }

        .contact-icon {
            font-size: 2.5rem;
            color: #ffb700;
            margin-bottom: 1.5rem;
        }

        .contact-card h3 {
            font-size: 1.25rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .contact-card p {
            color: #6c757d;
            margin-bottom: 0;
        }

        .contact-card a {
            color: #6c757d;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .contact-card a:hover {
            color: #ffb700;
        }

        .form-section {
            padding: 5rem 0;
            background-color: #f8f9fa;
        }

        .section-title {
            text-align: center;
            margin-bottom: 3rem;
        }

        .section-title h2 {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .section-title .line {
            height: 3px;
            width: 60px;
            background-color: #ffb700;
            margin: 0 auto;
        }

        .contact-form {
            background: #fff;
            padding: 3rem;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        }

        .form-control {
            padding: 0.8rem 1rem;
            border-radius: 5px;
            border: 1px solid #ced4da;
        }

        .form-control:focus {
            border-color: #ffb700;
            box-shadow: 0 0 0 0.2rem rgba(255, 183, 0, 0.25);
        }

        .btn-primary {
            background-color: #ffb700;
            border-color: #ffb700;
            color: #000;
            font-weight: bold;
            padding: 0.8rem 2rem;
            text-transform: uppercase;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #e0a800;
            border-color: #d39e00;
            color: #000;
        }
    </style>
@endpush

@section('content')
    <section class="small-header">
        <div>
            <div class="line"></div>
            <h1>Contactez-nous</h1>
            <p>Nous sommes là pour répondre à vos questions et vous accompagner.</p>
        </div>
    </section>

    <section class="contact-section">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="contact-card">
                        <div class="contact-icon">
                            <i class="fa fa-map-marker"></i>
                        </div>
                        <h3>Notre Adresse</h3>
                        <p>Bonabéri, Douala<br>Cameroun</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="contact-card">
                        <div class="contact-icon">
                            <i class="fa fa-phone"></i>
                        </div>
                        <h3>Téléphone</h3>
                        <p>
                            <a href="tel:+237699765435">+237 699 76 54 35</a>
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="contact-card">
                        <div class="contact-icon">
                            <i class="fa fa-envelope"></i>
                        </div>
                        <h3>Email</h3>
                        <p>
                            <a href="mailto:contact@emec-cameroun.com">contact@emec-cameroun.com</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="form-section">
        <div class="container">
            <div class="section-title">
                <h2>Envoyez-nous un message</h2>
                <div class="line"></div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="contact-form">
                        <form action="" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Nom complet</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="subject" class="form-label">Sujet</label>
                                <input type="text" class="form-control" id="subject" name="subject" required>
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Message</label>
                                <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Envoyer le message</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
