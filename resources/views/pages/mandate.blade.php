@extends('index')

@push('styles')
    <style>
        .hero .hero-bg {
            background-image: url({{ asset('images/home-2.jpg') }});
        }

        .hero-overlay {
            background: radial-gradient(ellipse 80% 60% at 60% 30%, rgba(69, 189, 253, 0.08) 0%, transparent 70%), linear-gradient(to top, rgba(0, 0, 0, 1) 0%, rgba(0, 0, 0, 0.5) 45%, rgba(0, 0, 0, 0.15) 100%);
        }

        .hero-kicker {
            display: inline-flex;
            align-items: center;
            gap: 0.7rem;
            margin-bottom: 1.5rem;
            opacity: 0;
            animation: fadeUp 0.8s 0.35s forwards;
        }

        .hero-kicker::before {
            content: '';
            width: 32px;
            height: 1px;
            background: var(--color-sky);
        }

        .hero-kicker span {
            font-size: 0.75rem;
            letter-spacing: 0.35em;
            text-transform: uppercase;
            color: var(--color-sky);
        }

        .hero-scripture {
            margin-top: 2rem;
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            max-width: 620px;
            opacity: 0;
            animation: fadeUp 0.9s 0.75s forwards;
        }

        .hero-scripture-bar {
            width: 3px;
            min-height: 60px;
            background: var(--color-sky);
        }

        .hero-scripture-text {
            font-family: 'Libre Baskerville', serif;
            font-style: italic;
            font-size: 1rem;
            line-height: 1.75;
            color: rgba(255, 255, 255, 0.75);
            margin: 0;
        }

        .hero-scripture-ref {
            margin-top: 0.75rem;
            font-size: 0.75rem;
            letter-spacing: 0.3em;
            text-transform: uppercase;
            color: var(--color-sky);
        }

        .hero-scroll {
            position: absolute;
            bottom: 28px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.9rem;
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.75rem;
            letter-spacing: 0.3em;
            text-transform: uppercase;
        }

        .scroll-line {
            width: 2px;
            height: 54px;
            background: var(--color-sky);
            border-radius: 999px;
            animation: floatScroll 1.8s ease-in-out infinite;
        }

        @keyframes floatScroll {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(10px);
            }
        }

        .intro {
            background: var(--color-black);
            padding: 5rem 10vw;
        }

        .intro-inner {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 5rem;
            align-items: center;
        }

        .intro-label {
            font-size: 0.75rem;
            letter-spacing: 0.3em;
            text-transform: uppercase;
            color: var(--color-sky);
            line-height: 1.6;
        }

        .intro-label strong {
            font-family: 'Playfair Display', serif;
            font-size: 3rem;
            font-weight: 900;
            color: #fff;
            letter-spacing: -0.05em;
            line-height: 1.05;
        }

        .intro-text p {
            font-size: 1rem;
            line-height: 1.85;
            color: rgba(255, 255, 255, 0.72);
            margin-bottom: 1.25rem;
        }

        .piliers {
            padding: 100px 10vw;
            background: var(--color-offwhite);
        }

        .piliers-header {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 5rem;
            align-items: end;
            margin-bottom: 4rem;
        }

        .piliers-header-right p {
            font-size: 1rem;
            line-height: 1.85;
            color: var(--text-muted);
            margin: 0;
        }

        .pilier {
            display: grid;
            grid-template-columns: 80px 1fr;
            background: #fff;
            border: 1px solid rgba(69, 189, 253, 0.15);
            overflow: hidden;
            transition: border-color 0.3s ease;
            margin-bottom: 1rem;
        }

        .pilier:hover {
            border-color: rgba(69, 189, 253, 0.4);
        }

        .pilier-num {
            background: var(--color-black);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            font-weight: 900;
            color: rgba(69, 189, 253, 0.3);
            writing-mode: vertical-rl;
            padding: 2rem 0;
            letter-spacing: 0.2em;
        }

        .pilier-body {
            padding: 2rem 2.2rem;
        }

        .pilier-icon {
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .pilier-body h3 {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            margin-bottom: 1rem;
            color: var(--text-dark);
        }

        .pilier-body p {
            font-size: 1rem;
            line-height: 1.85;
            color: var(--text-muted);
            margin: 0;
        }

        .pilier-tag {
            display: inline-block;
            margin-top: 1.5rem;
            font-size: 0.75rem;
            letter-spacing: 0.3em;
            text-transform: uppercase;
            color: var(--color-sky);
            border-bottom: 1px solid rgba(69, 189, 253, 0.3);
            padding-bottom: 0.25rem;
        }

        .verse-break {
            background: var(--color-black);
            padding: 5rem 10vw;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .verse-break::before {
            content: '❝';
            position: absolute;
            top: -40px;
            left: 10vw;
            font-size: 10rem;
            color: rgba(69, 189, 253, 0.04);
            font-family: 'Libre Baskerville', serif;
        }

        .verse-break blockquote {
            font-family: 'Playfair Display', serif;
            font-style: italic;
            font-size: clamp(20px, 2.8vw, 34px);
            line-height: 1.5;
            color: #fff;
            max-width: 820px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .verse-break cite {
            display: block;
            margin-top: 2rem;
            font-size: 0.75rem;
            letter-spacing: 0.3em;
            text-transform: uppercase;
            color: var(--color-sky);
            font-style: normal;
            font-family: 'DM Sans', sans-serif;
        }

        .verse-break-line {
            width: 48px;
            height: 1px;
            background: var(--color-sky);
            margin: 0 auto 2.5rem;
            opacity: 0.5;
        }

        .engagement {
            padding: 100px 10vw;
            background: #f7fbff;
        }

        .engagement-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-top: 3.5rem;
            background: rgba(69, 189, 253, 0.1);
            padding: 0.5rem;
        }

        .engagement-item {
            background: var(--color-offwhite);
            padding: 3rem 2rem;
            position: relative;
            overflow: hidden;
        }

        .engagement-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: transparent;
            transition: background 0.3s ease;
        }

        .engagement-item:hover::before {
            background: var(--color-sky);
        }

        .eng-number {
            font-family: 'Playfair Display', serif;
            font-size: 5rem;
            font-weight: 900;
            color: rgba(69, 189, 253, 0.12);
            line-height: 1;
            margin-bottom: -24px;
        }

        .eng-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 1rem;
        }

        .eng-desc {
            font-size: 0.95rem;
            line-height: 1.75;
            color: var(--text-muted);
            margin: 0;
        }

        .final-cta {
            background: var(--color-black);
            padding: 5rem 10vw;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 5rem;
            align-items: center;
        }

        .final-cta-text h2 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(30px, 3.5vw, 50px);
            font-weight: 700;
            color: #fff;
            line-height: 1.18;
            margin: 0;
        }

        .final-cta-text p {
            font-size: 1rem;
            line-height: 1.8;
            color: rgba(255, 255, 255, 0.65);
            margin-top: 1.5rem;
        }

        .cta-cards {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .cta-card {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.5rem 1.6rem;
            border: 1px solid rgba(69, 189, 253, 0.2);
            border-radius: 12px;
            color: #fff;
            text-decoration: none;
            transition: border-color 0.25s ease, background 0.25s ease;
        }

        .cta-card:hover {
            border-color: rgba(69, 189, 253, 0.6);
            background: rgba(69, 189, 253, 0.05);
        }

        .cta-card-icon {
            width: 44px;
            height: 44px;
            background: rgba(69, 189, 253, 0.12);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            border-radius: 10px;
            flex-shrink: 0;
        }

        .cta-card h4 {
            font-size: 1rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 0.3rem;
        }

        .cta-card p {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.6);
            margin: 0;
        }

        .cta-card .arrow {
            margin-left: auto;
            color: rgba(69, 189, 253, 0.45);
            font-size: 1.3rem;
            transition: color 0.2s ease, transform 0.2s ease;
        }

        .cta-card:hover .arrow {
            color: var(--color-sky);
            transform: translateX(3px);
        }

        @media (max-width: 960px) {
            .intro-inner,
            .final-cta {
                grid-template-columns: 1fr;
            }
            .engagement-grid,
            .piliers-header,
            .founders,
            .eglises-grid {
                grid-template-columns: 1fr;
            }
            nav .nav-links {
                display: none;
            }
        }

        @media (max-width: 600px) {
            .pilier {
                grid-template-columns: 1fr;
            }
            .pilier-num {
                writing-mode: horizontal-tb;
                padding: 1.25rem 1rem;
                font-size: 1.5rem;
            }
            .verse-break {
                padding: 3rem 6vw;
            }
            .footer-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')
    <section class="hero">
        <div class="hero-bg"></div>
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <nav class="breadcrumb" aria-label="Fil d'ariane">
                <a href="/">Accueil</a>
                <span>›</span>
                <span class="current">Notre Mandat</span>
            </nav>
            <div class="hero-kicker"><span>Le Grand Mandat</span></div>
            <h1>Notre appel.<br>Notre <em>raison<br>d'exister.</em></h1>
            <div class="hero-scripture">
                <div class="hero-scripture-bar"></div>
                <div>
                    <p class="hero-scripture-text">« Allez, faites de toutes les nations des disciples, les baptisant au nom du Père, du Fils et du Saint-Esprit, et enseignez-leur à observer tout ce que je vous ai prescrit. »</p>
                    <p class="hero-scripture-ref">Matthieu 28 : 19–20</p>
                </div>
            </div>
        </div>
        <div class="hero-scroll">
            <div class="scroll-line"></div>
            <span>Découvrir</span>
        </div>
    </section>

    <div class="intro reveal">
        <div class="intro-inner">
            <div class="intro-label">
                Notre<br/>
                <strong>Grand<br/><em>Mandat</em></strong>
            </div>
            <div class="intro-text">
                <p>À l'EMEC, nous sommes profondément enracinés dans le Grand Mandat que Jésus-Christ a confié à ses disciples. <strong>C'est notre raison d'être</strong> — la force motrice derrière toutes nos actions, le fondement de notre ministère et la boussole de chacune de nos décisions.</p>
                <p>Nous nous engageons à proclamer l'Évangile sans compromis, à former des disciples matures et à manifester l'amour de Dieu de manière concrète dans nos communautés — au Cameroun et jusqu'aux extrémités de la terre.</p>
            </div>
        </div>
    </div>

    <section class="piliers" id="piliers">
        <div class="piliers-header reveal">
            <div>
                <p class="section-eyebrow">Les Piliers</p>
                <h2 class="section-title">Quatre engagements.<br><em>Une seule mission.</em></h2>
            </div>
            <div class="piliers-header-right">
                <p>Notre mandat se décline en quatre aspects essentiels, chacun étant une facette de notre appel divin. Ensemble, ils forment le cœur de tout ce que nous faisons à l'EMEC.</p>
            </div>
        </div>

        <div class="pilier reveal">
            <div class="pilier-num">01</div>
            <div class="pilier-body">
                <div class="pilier-icon">📣</div>
                <h3>La Proclamation de l'Évangile</h3>
                <p>Nous proclamons la Bonne Nouvelle de Jésus-Christ sans compromis, invitant chacun à une relation personnelle avec Dieu par des campagnes d'évangélisation et des moments de partage.</p>
                <span class="pilier-tag">Matthieu 28:19 — Faites des disciples</span>
            </div>
        </div>

        <div class="pilier reveal">
            <div class="pilier-num">02</div>
            <div class="pilier-body">
                <div class="pilier-icon">🌱</div>
                <h3>La Formation de Disciples</h3>
                <p>Nous formons des disciples matures par des enseignements bibliques, des groupes de maison et un accompagnement spirituel afin qu'ils puissent impacter leur entourage.</p>
                <span class="pilier-tag">Matthieu 28:20 — Enseignez-leur à observer</span>
            </div>
        </div>

        <div class="pilier reveal">
            <div class="pilier-num">03</div>
            <div class="pilier-body">
                <div class="pilier-icon">🤝</div>
                <h3>Service et Compassion</h3>
                <p>L'EMEC manifeste l'amour de Dieu par des actions concrètes : aide aux démunis, programmes sociaux et soutien aux familles en difficulté.</p>
                <span class="pilier-tag">Jacques 2:17 — La foi sans les œuvres est morte</span>
            </div>
        </div>

        <div class="pilier reveal">
            <div class="pilier-num">04</div>
            <div class="pilier-body">
                <div class="pilier-icon">🙏</div>
                <h3>L'Adoration et la Louange</h3>
                <p>Nos moments de culte sont conçus pour vivre une adoration sincère et profonde, unissant cœur et esprit devant Dieu.</p>
                <span class="pilier-tag">Jean 4:23 — En esprit et en vérité</span>
            </div>
        </div>
    </section>

    <section class="verse-break reveal">
        <div class="verse-break-line"></div>
        <blockquote>« Notre mandat est d'annoncer l'Évangile, faire des disciples de toutes les nations, les baptiser, les enseigner — et manifester l'amour de Dieu par la compassion, la justice et la paix, <em>afin de glorifier Christ.</em> »</blockquote>
        <cite>La vision de l'EMEC</cite>
    </section>

    <section class="engagement reveal">
        <div>
            <p class="section-eyebrow">Notre Engagement Concret</p>
            <h2 class="section-title">Des paroles aux <em>actes.</em></h2>
        </div>
        <div class="engagement-grid">
            <div class="engagement-item reveal"><div class="eng-number">01</div><h3 class="eng-title">Évangélisation Active</h3><p class="eng-desc">Des campagnes régulières et des sorties de terrain pour rejoindre ceux qui ne viendraient pas d'eux-mêmes.</p></div>
            <div class="engagement-item reveal"><div class="eng-number">02</div><h3 class="eng-title">Implantation d'Églises</h3><p class="eng-desc">Planter des communautés de foi dans chaque ville atteinte, des lieux vivants où les nouveaux disciples peuvent grandir.</p></div>
            <div class="engagement-item reveal"><div class="eng-number">03</div><h3 class="eng-title">Formation et Équipement</h3><p class="eng-desc">École de Sion, séminaires de leadership et enseignements hebdomadaires pour former des disciples mûrs.</p></div>
            <div class="engagement-item reveal"><div class="eng-number">04</div><h3 class="eng-title">Action Sociale</h3><p class="eng-desc">Soutien aux familles vulnérables, initiatives éducatives et actions humanitaires — l'amour rendu visible.</p></div>
            <div class="engagement-item reveal"><div class="eng-number">05</div><h3 class="eng-title">Ministère des Prières</h3><p class="eng-desc">Veillées et réunions de prière intercédant pour les nations, les familles, les malades et les autorités.</p></div>
            <div class="engagement-item reveal"><div class="eng-number">06</div><h3 class="eng-title">Mission Transfrontalière</h3><p class="eng-desc">Le mandat dépasse le Cameroun — une influence apostolique qui touchera les nations voisines et le monde.</p></div>
        </div>
    </section>

    <section class="final-cta reveal">
        <div class="final-cta-text">
            <h2>Ce mandat<br/>nous appartient<br/><em>tous.</em></h2>
            <p>Vous n'êtes pas un simple spectateur. Le Grand Mandat est un appel adressé à chaque membre du Corps de Christ. Rejoignez-nous, participez, donnez, priez — ensemble nous changerons le monde.</p>
        </div>
        <div class="cta-cards">
            <a href="/get-connected" class="cta-card"><div class="cta-card-icon">✝</div><div><h4>Je veux suivre Christ</h4><p>Faire le premier pas dans la foi</p></div><span class="arrow">→</span></a>
            <a href="/events" class="cta-card"><div class="cta-card-icon">📅</div><div><h4>Participer aux événements</h4><p>Campagnes, conférences, cultes spéciaux</p></div><span class="arrow">→</span></a>
            <a href="/donate" class="cta-card"><div class="cta-card-icon">🤲</div><div><h4>Soutenir la mission</h4><p>Votre générosité amplifie l'impact</p></div><span class="arrow">→</span></a>
            <a href="/our-projects" class="cta-card"><div class="cta-card-icon">🌍</div><div><h4>Découvrir nos projets</h4><p>Voir le mandat à l'œuvre</p></div><span class="arrow">→</span></a>
        </div>
    </section>
@endsection
