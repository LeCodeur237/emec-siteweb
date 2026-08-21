@push('styles')
    <style>
        footer {
            background-repeat: no-repeat;
            background-size: cover;
            background-position: top;
            /* Ajouté pour un meilleur centrage de l'image */
            color: #fff;
        }

        footer span {
            font-size: 1rem;
            font-weight: bold;
            margin-bottom: 2rem;
            display: block;
        }

        .top-border {
            border-top: 1px solid #fff;
            width: 10%;
            padding-top: 0.6rem;
        }

        footer .gived {
            padding: 5rem 5.5rem 3rem 5.5rem;
        }

        footer .gived h2 {
            font-size: 2rem;
            width: 60%;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        footer .gived p {
            font-size: 1rem;
            width: 60%;
            font-size: 15px;
            margin-bottom: 2.4rem;
        }

        footer .gived .call-to-action {
            background-color: var(--color-sky);
            color: var(--color-black);
            padding: 1.2rem 2rem;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s ease;
            text-transform: uppercase;
            font-size: 14px;
            margin-inline: 0.1rem;
        }

        footer .divider {
            width: 100%;
            height: 10vh;
        }

        footer .gived .col-6 {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            justify-content: center;
        }

        @media (max-width: 992px) {
            footer .gived {
                padding: 3rem 1.5rem;
            }
            footer .gived h2, footer .gived p {
                width: 100%;
            }
            footer .gived .col-12-mobile {
                width: 100%;
                padding-left: 0 !important;
                margin-bottom: 2rem;
            }
        }
    </style>
<footer
    style="background-image: linear-gradient(to top, rgba(0, 0, 0, 0.243), rgba(0, 0, 0, 0.75)),  url({{ asset('images/home-3.jpg') }})">
    <div class="row gived">
        <div class="col-lg-6 col-12 col-12-mobile">
            <span class="top-border">Don</span>
            <h2>Votre générosité, notre mission.</h2>
            <p>Votre soutien permet de porter l'Évangile, servir la communauté et créer un impact durable.</p>
            <a href="/donate" class="call-to-action">Faire un don</a>
        </div>
        <div class="col-lg-6 col-12 col-12-mobile" style="padding-left: 3rem">
            <span>Heures & adresse</span>
            <h2>Tous les jours · 08:00–18:00</h2>
            <p>Entrée OPEP, Minboman — Yaoundé, Cameroun</p>
            <div class="divider"></div>
            <span class="top-border">Contact</span>
            <h2>(+237) 699 76 54 35</h2>
            <p>https://egliseemec.org</p>
            <div class="social-links d-flex">
                <a href="https://www.facebook.com/emec.eglise" target="_blank" class="text-white me-3"><i
                        class="fa fa-facebook-square fa-2x"></i></a>
                <a href="https://www.youtube.com/channel/UC_your_youtube_channel_id" target="_blank"
                    class="text-white me-3"><i class="fa fa-youtube-square fa-2x"></i></a>
                <a href="https://www.tiktok.com/@emec.eglise" target="_blank" class="text-white"><i
                        class="fa fa-tiktok fa-2x"></i></a>
            </div>

        </div>
    </div>
    <div class="col-12" style="background-color: white; color: #000; padding: 2rem; text-align: center;">
        <p class="mb-0">Copyright &copy; {{ date('Y') }} EMEC. All Rights Reserved.</p>
    </div>
</footer>
