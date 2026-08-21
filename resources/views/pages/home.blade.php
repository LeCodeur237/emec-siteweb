@extends('index')

@push('styles')
    <style>
        .hero .hero-bg {
            background-image: url({{ asset('images/home-2.jpg') }});
        }

        .ticker {
            background: var(--color-sky);
            padding: 1rem 10vw;
            display: flex;
            align-items: center;
            gap: 1rem;
            color: var(--color-black);
        }

        .ticker-label {
            font-size: 0.75rem;
            letter-spacing: 0.3em;
            text-transform: uppercase;
            background: rgba(255, 255, 255, 0.75);
            padding: 0.4rem 0.9rem;
            border-radius: 999px;
            font-weight: 700;
        }

        .ticker-text {
            font-size: 0.95rem;
            line-height: 1.5;
            margin: 0;
        }

        .about {
            padding: 100px 10vw;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 5rem;
            align-items: center;
        }

        .about-image {
            position: relative;
        }

        .about-image img {
            width: 100%;
            height: 520px;
            object-fit: cover;
            border-radius: 12px;
            display: block;
        }

        .about-image-accent {
            position: absolute;
            bottom: -22px;
            right: -22px;
            width: 200px;
            height: 200px;
            border: 1px solid var(--color-sky);
            border-radius: 12px;
            opacity: 0.45;
            z-index: -1;
        }

        .about-badge {
            position: absolute;
            top: 32px;
            left: -32px;
            background: var(--color-black);
            color: var(--color-sky);
            font-family: 'Playfair Display', serif;
            font-size: 13px;
            font-style: italic;
            padding: 14px 20px;
            max-width: 180px;
        }

        .about-text p {
            font-size: 1rem;
            line-height: 1.85;
            color: var(--text-muted);
            margin-bottom: 1.5rem;
        }

        .about-verse {
            margin: 2.5rem 0;
            padding: 1.5rem 1.75rem;
            border-left: 3px solid var(--color-sky);
            background: rgba(69, 189, 253, 0.08);
        }

        .about-verse blockquote {
            font-family: 'Libre Baskerville', serif;
            font-style: italic;
            font-size: 1.05rem;
            line-height: 1.7;
            color: var(--text-dark);
            margin: 0;
        }

        .about-verse cite {
            display: block;
            margin-top: 0.8rem;
            font-size: 0.75rem;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: var(--color-sky);
        }

        .about-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-top: 2.5rem;
        }

        .stat-item {
            background: #fff;
            padding: 1.5rem;
            border: 1px solid rgba(69, 189, 253, 0.15);
            border-radius: 12px;
            text-align: center;
        }

        .stat-number {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            color: var(--color-sky-dark);
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 0.75rem;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-top: 0.5rem;
        }

        .programs {
            background: var(--color-black);
            color: #fff;
            padding: 100px 10vw;
        }

        .programs-grid {
            margin-top: 3.5rem;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            background: rgba(255, 255, 255, 0.05);
        }

        .program-card {
            position: relative;
            padding: 2.5rem 2rem;
            background: var(--color-black);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            overflow: hidden;
            transition: background 0.3s ease, transform 0.3s ease;
        }

        .program-card:hover {
            background: rgba(255, 255, 255, 0.06);
            transform: translateY(-3px);
        }

        .program-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--color-sky);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.35s ease;
        }

        .program-card:hover::before {
            transform: scaleX(1);
        }

        .program-day {
            font-size: 0.75rem;
            letter-spacing: 0.3em;
            text-transform: uppercase;
            color: var(--color-sky);
            margin-bottom: 1rem;
        }

        .program-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.25rem;
            line-height: 1.3;
            color: #fff;
            margin-bottom: 0.8rem;
        }

        .program-time {
            font-size: 0.95rem;
            color: rgba(255, 255, 255, 0.7);
        }

        .program-number {
            position: absolute;
            bottom: 1.35rem;
            right: 1.2rem;
            font-family: 'Playfair Display', serif;
            font-size: 4rem;
            color: rgba(69, 189, 253, 0.1);
            line-height: 1;
        }

        .mandate {
            background: var(--color-offwhite);
            padding: 100px 10vw;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 5rem;
            align-items: start;
        }

        .mandate-text p {
            font-size: 1rem;
            line-height: 1.85;
            color: var(--text-muted);
            margin-bottom: 1.5rem;
        }

        .mandate-list {
            list-style: none;
            margin-top: 2rem;
            display: grid;
            gap: 1rem;
            padding: 0;
        }

        .mandate-list li {
            display: flex;
            gap: 1rem;
            padding: 1.3rem 1rem;
            border: 1px solid rgba(69, 189, 253, 0.15);
            border-radius: 12px;
            background: #fff;
            align-items: flex-start;
        }

        .mandate-icon {
            width: 42px;
            height: 42px;
            background: var(--color-sky);
            color: var(--color-black);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            font-size: 1rem;
        }

        .mandate-list h4 {
            font-family: 'Playfair Display', serif;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }

        .mandate-list p {
            font-size: 0.95rem;
            color: var(--text-muted);
            margin: 0;
            line-height: 1.7;
        }

        .pastor {
            background: var(--color-offwhite);
            padding: 100px 10vw;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 5rem;
            align-items: center;
        }

        .pastor-image {
            position: relative;
        }

        .pastor-image img {
            width: 100%;
            height: 560px;
            object-fit: cover;
            border-radius: 12px;
            display: block;
            filter: grayscale(15%);
        }

        .pastor-tag {
            position: absolute;
            bottom: 24px;
            left: 24px;
            background: var(--color-sky);
            color: var(--color-black);
            padding: 0.9rem 1.2rem;
            border-radius: 10px;
        }

        .pastor-tag strong {
            display: block;
            font-family: 'Playfair Display', serif;
            font-size: 1rem;
        }

        .pastor-tag span {
            font-size: 0.75rem;
            letter-spacing: 0.18em;
            text-transform: uppercase;
        }

        .pastor-text p {
            font-size: 1rem;
            line-height: 1.85;
            color: var(--text-muted);
            margin-bottom: 1.2rem;
        }

        .pastor-quote {
            margin: 2rem 0 0;
            padding: 1.5rem 1.6rem;
            border-top: 2px solid var(--color-sky);
            border-bottom: 1px solid rgba(69, 189, 253, 0.25);
        }

        .pastor-quote p {
            font-family: 'Libre Baskerville', serif;
            font-size: 1.05rem;
            line-height: 1.75;
            color: var(--text-dark);
            margin: 0;
        }

        .events {
            background: var(--color-black);
            color: #fff;
            padding: 100px 10vw;
        }

        .events-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 2rem;
            margin-bottom: 3.5rem;
        }

        .events-link {
            font-size: 0.75rem;
            letter-spacing: 0.3em;
            text-transform: uppercase;
            color: var(--color-sky);
            text-decoration: none;
            border-bottom: 1px solid rgba(69, 189, 253, 0.4);
            padding-bottom: 0.25rem;
        }

        .events-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
        }

        .event-card {
            background: #111;
            border: 1px solid rgba(69, 189, 253, 0.12);
            padding: 2rem;
            border-radius: 12px;
            transition: border-color 0.3s ease, transform 0.3s ease;
        }

        .event-card:hover {
            border-color: rgba(69, 189, 253, 0.35);
            transform: translateY(-3px);
        }

        .event-date {
            font-family: 'Playfair Display', serif;
            font-size: 3rem;
            color: var(--color-sky);
            line-height: 1;
            margin-bottom: 0.5rem;
        }

        .event-month {
            font-size: 0.75rem;
            letter-spacing: 0.3em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.55);
            margin-bottom: 1.25rem;
        }

        .event-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.1rem;
            color: #fff;
            margin-bottom: 0.8rem;
            line-height: 1.3;
        }

        .event-location {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.65);
        }

        .give {
            background: var(--color-sky);
            text-align: center;
            padding: 100px 10vw;
        }

        .give p {
            font-size: 1rem;
            color: rgba(0, 0, 0, 0.75);
            max-width: 560px;
            margin: 20px auto 40px;
            line-height: 1.8;
        }

        .give-methods {
            display: flex;
            justify-content: center;
            gap: 1rem;
            flex-wrap: wrap;
            margin-bottom: 40px;
        }

        .give-method {
            background: rgba(0, 0, 0, 0.08);
            padding: 0.9rem 1.4rem;
            border-radius: 999px;
            font-size: 0.8rem;
            letter-spacing: 0.15em;
            color: var(--color-black);
        }

        .btn-give {
            background: var(--color-black);
            color: var(--color-sky);
            min-width: 180px;
        }

        @media (max-width: 900px) {
            .about,
            .mandate,
            .pastor {
                grid-template-columns: 1fr;
            }
            .programs-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .events-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 540px) {
            .hero h1 {
                font-size: 2.25rem;
            }
            .hero-buttons {
                flex-direction: column;
                align-items: stretch;
            }
            .about-image-accent {
                display: none;
            }
            .about-image img {
                height: 420px;
            }
            .about-stats {
                grid-template-columns: 1fr;
            }
            .programs-grid {
                grid-template-columns: 1fr;
            }
            .mandate-list {
                display: grid;
            }
            .events-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')
    <section class="hero">
        <div class="hero-bg"></div>
        <div class="hero-gradient"></div>
        <div class="hero-content">
            <div class="hero-eyebrow"><span>Église Messianique Évangélique du Cameroun</span></div>
            <h1>Un lieu de <em>transformation,</em><br>de restauration<br>et d'espérance.</h1>
            <p class="hero-sub">Là où les vies sont bâties, les miracles se manifestent et les délivrances se vivent — sous la Parole de Dieu et la conduite du Saint-Esprit.</p>
            <p class="hero-verse">« Mais vous, vous êtes une race choisie, un sacerdoce royal, une nation sainte. » — 1 Pierre 2:9</p>
            <div class="hero-buttons">
                <a href="/about-us" class="btn-primary">Découvrir l'EMEC</a>
                <a href="/get-connected" class="btn-outline">Rejoindre la famille</a>
            </div>
        </div>
    </section>

    <div class="ticker">
        <span class="ticker-label">Annonce</span>
        <p class="ticker-text">Le PCE annonce une grande campagne d'évangélisation à Bertoua — Janvier 2026. Rejoignez-nous !</p>
    </div>

    <section class="about" id="about">
        <div class="about-image reveal">
            <img src="{{ asset('images/home-2.jpg') }}" alt="Communauté EMEC en adoration" />
            <div class="about-image-accent"></div>
            <div class="about-badge">Fondée sur la Parole de Dieu</div>
        </div>
        <div class="about-text reveal">
            <p class="section-eyebrow">Qui sommes-nous</p>
            <h2 class="section-title">Une communauté de <em>foi vivante</em> au cœur du Cameroun</h2>
            <p>L'Église Messianique Évangélique du Cameroun (EMEC) est une communauté dynamique et multigénérationnelle, fondée sur des principes solides de foi, de dévotion et d'amour fraternel. Depuis ses humbles débuts, elle s'est développée pour rayonner bien au-delà des frontières du Cameroun.</p>
            <p>Nous croyons que chaque personne a une destinée divine. Notre mission est de vous aider à la découvrir, à travers l'enseignement de la Parole, la louange et le service envers les autres.</p>
            <div class="about-verse">
                <blockquote>« Mais vous, vous êtes une race choisie, un sacerdoce royal, une nation sainte, un peuple acquis, afin que vous annonciez les vertus de celui qui vous a appelés des ténèbres à son admirable lumière. »</blockquote>
                <cite>1 Pierre 2:9</cite>
            </div>
            <div class="about-stats">
                <div class="stat-item"><div class="stat-number">10+</div><div class="stat-label">Années de ministère</div></div>
                <div class="stat-item"><div class="stat-number">3+</div><div class="stat-label">Villes atteintes</div></div>
                <div class="stat-item"><div class="stat-number">∞</div><div class="stat-label">Vies transformées</div></div>
            </div>
        </div>
    </section>

    <section class="programs" id="programs">
        <div class="reveal">
            <p class="section-eyebrow">Nos Programmes</p>
            <h2 class="section-title">Venez vivre votre foi<br>avec nous chaque semaine</h2>
        </div>
        <div class="programs-grid">
            <div class="program-card reveal">
                <p class="program-day">Dimanche</p>
                <h3 class="program-title">Culte de Célébration</h3>
                <p class="program-time">9h00 — 12h30</p>
                <div class="program-number">01</div>
            </div>
            <div class="program-card reveal">
                <p class="program-day">Lundi</p>
                <h3 class="program-title">École de Sion</h3>
                <p class="program-time">17h30 — 20h00</p>
                <div class="program-number">02</div>
            </div>
            <div class="program-card reveal">
                <p class="program-day">Mercredi</p>
                <h3 class="program-title">Jour d'Enseignements</h3>
                <p class="program-time">17h30 — 20h00</p>
                <div class="program-number">03</div>
            </div>
            <div class="program-card reveal">
                <p class="program-day">Vendredi</p>
                <h3 class="program-title">Jour de Prières</h3>
                <p class="program-time">17h30 — 20h00</p>
                <div class="program-number">04</div>
            </div>
        </div>
    </section>

    <section class="mandate" id="mandate">
        <div>
            <p class="section-eyebrow">Notre Mandat</p>
            <h2 class="section-title">Appelés à <em>annoncer,</em> former et servir</h2>
            <p>Notre mandat est d'annoncer l'Évangile, faire des disciples de toutes les nations, les baptiser, les enseigner, et manifester l'amour de Dieu par la compassion, la justice et la paix — afin de glorifier Christ en toutes choses.</p>
            <a href="/mandate" class="btn-primary">En savoir plus</a>
        </div>
        <ul class="mandate-list reveal">
            <li>
                <div class="mandate-icon">✝</div>
                <div>
                    <h4>Annoncer l'Évangile</h4>
                    <p>Proclamer la bonne nouvelle du salut par Jésus-Christ et inviter chacun à la repentance et à la foi.</p>
                </div>
            </li>
            <li>
                <div class="mandate-icon">💧</div>
                <div>
                    <h4>Le Baptême</h4>
                    <p>Un acte d'obéissance symbolisant l'identification à la mort, l'ensevelissement et la résurrection de Jésus.</p>
                </div>
            </li>
            <li>
                <div class="mandate-icon">📖</div>
                <div>
                    <h4>L'Enseignement</h4>
                    <p>Former des disciples matures par des enseignements bibliques solides et un accompagnement spirituel.</p>
                </div>
            </li>
            <li>
                <div class="mandate-icon">🤝</div>
                <div>
                    <h4>Service et Compassion</h4>
                    <p>Manifester l'amour de Dieu par des actions concrètes au service des plus vulnérables.</p>
                </div>
            </li>
        </ul>
    </section>

    <section class="pastor" id="pastor">
        <div class="pastor-image reveal">
            <img src="{{ asset('images/home-11.jpeg') }}" alt="Apôtre Samuel Dalle" />
            <div class="pastor-tag">
                <strong>Apôtre Samuel Dalle</strong>
                <span>Fondateur & Berger Principal</span>
            </div>
        </div>
        <div class="pastor-text reveal">
            <p class="section-eyebrow">Le Visionnaire</p>
            <h2 class="section-title">Un homme de foi,<br>une vision <em>apostolique</em></h2>
            <p>L'Apôtre Samuel Dalle guide la communauté avec une vision de renouveau spirituel, d'enseignement solide et d'engagement social. Il encourage la transformation personnelle par la prière et la formation biblique.</p>
            <p>Sous sa direction, l'EMEC développe des actions communautaires visant à soutenir les plus vulnérables et à renforcer la fraternité entre les membres.</p>
            <div class="pastor-quote">
                <p>« Ma vision est de voir chaque membre de l'EMEC devenir un disciple mature de Christ, équipé pour impacter positivement son environnement. »</p>
            </div>
        </div>
    </section>

    <section class="events" id="events">
        <div class="events-header reveal">
            <div>
                <p class="section-eyebrow">Agenda</p>
                <h2 class="section-title" style="color:#fff;">Prochains <em>événements</em></h2>
            </div>
            <a href="/events" class="events-link">Voir tout le calendrier →</a>
        </div>
        <div class="events-grid">
            <div class="event-card reveal">
                <div class="event-date">12</div>
                <div class="event-month">Janvier 2026</div>
                <h3 class="event-title">Campagne d'Évangélisation — Bertoua</h3>
                <p class="event-location">📍 Bertoua, Cameroun · 12 au 18 Janvier</p>
            </div>
            <div class="event-card reveal">
                <div class="event-date">31</div>
                <div class="event-month">Mars 2026</div>
                <h3 class="event-title">Culte Spécial de Pâques</h3>
                <p class="event-location">📍 EMEC Siège, Yaoundé</p>
            </div>
            <div class="event-card reveal">
                <div class="event-date">12</div>
                <div class="event-month">Avril 2026</div>
                <h3 class="event-title">Conférence Annuelle des Femmes</h3>
                <p class="event-location">📍 EMEC Yaoundé · 12–14 Avril</p>
            </div>
        </div>
    </section>

    <section class="give" id="give">
        <p class="section-eyebrow">Votre Générosité</p>
        <h2 class="section-title">Votre offrande. Notre mission.</h2>
        <p>Votre soutien finance la proclamation de l'Évangile, le service à la communauté et un impact durable pour la gloire de Dieu.</p>
        <div class="give-methods">
            <span class="give-method">📱 MTN Mobile Money</span>
            <span class="give-method">📱 Orange Money</span>
            <span class="give-method">🏦 Virement bancaire</span>
        </div>
        <a href="/donate" class="btn-give">Faire un don</a>
    </section>
@endsection
