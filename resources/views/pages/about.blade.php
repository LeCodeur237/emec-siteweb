@extends('index')

@push('styles')
    <style>
        .hero .hero-bg {
            background-image: url({{ asset('images/home-2.jpg') }});
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
            opacity: 0;
            animation: fadeUp 0.7s 0.2s forwards;
        }

        .breadcrumb a {
            font-size: 0.75rem;
            letter-spacing: 0.3em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.75);
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .breadcrumb a:hover {
            color: var(--color-sky);
        }

        .breadcrumb span,
        .breadcrumb .current {
            font-size: 0.75rem;
            letter-spacing: 0.3em;
            text-transform: uppercase;
            color: var(--color-sky);
        }

        .subnav {
            position: sticky;
            top: 72px;
            z-index: 150;
            background: rgba(0, 0, 0, 0.95);
            border-bottom: 1px solid rgba(69, 189, 253, 0.2);
        }

        .subnav-inner {
            display: flex;
            gap: 0;
            overflow-x: auto;
            scrollbar-width: none;
            padding: 0 10vw;
        }

        .subnav-inner::-webkit-scrollbar {
            display: none;
        }

        .subnav-btn {
            flex-shrink: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 1rem 1.25rem;
            font-size: 0.75rem;
            font-weight: 500;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.65);
            background: none;
            border: none;
            border-bottom: 2px solid transparent;
            cursor: pointer;
            transition: color 0.25s ease, border-color 0.25s ease;
            white-space: nowrap;
        }

        .subnav-btn.active {
            color: #fff;
            border-bottom-color: var(--color-sky);
        }

        .histoire-grid,
        .vision-mission,
        .founders,
        .conseil-grid,
        .ministeres-grid,
        .eglises-grid {
            display: grid;
            gap: 2rem;
        }

        .histoire-grid,
        .vision-mission,
        .eglises-grid {
            grid-template-columns: 1fr 1fr;
            align-items: start;
        }

        .histoire-text p,
        .conseil-desc,
        .eglises-header p,
        .piliers-header-right p {
            font-size: 1rem;
            line-height: 1.85;
            color: var(--text-muted);
            margin-bottom: 1.5rem;
        }

        .verse-block {
            margin: 2rem 0;
            padding: 1.5rem 1.75rem;
            border-left: 3px solid var(--color-sky);
            background: rgba(69, 189, 253, 0.07);
        }

        .verse-block blockquote {
            font-family: 'Libre Baskerville', serif;
            font-style: italic;
            font-size: 1.05rem;
            line-height: 1.7;
            color: var(--text-dark);
            margin: 0;
        }

        .verse-block cite {
            display: block;
            margin-top: 0.8rem;
            font-size: 0.75rem;
            letter-spacing: 0.25em;
            text-transform: uppercase;
            color: var(--color-sky);
        }

        .valeur-item,
        .conseil-card,
        .ministere-card,
        .eglise-card,
        .implantation-cta,
        .bridge {
            border-radius: 12px;
        }

        .valeur-item {
            display: flex;
            gap: 1rem;
            padding: 1.5rem 0;
            border-bottom: 1px solid rgba(69, 189, 253, 0.15);
        }

        .valeur-num {
            font-family: 'Playfair Display', serif;
            font-size: 1rem;
            color: var(--color-sky);
            min-width: 24px;
            margin-top: 0.4rem;
        }

        .valeur-body h4,
        .conseil-card h4,
        .ministere-card h4,
        .eglise-body h4 {
            font-family: 'Playfair Display', serif;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
            color: var(--text-dark);
        }

        .founders,
        .conseil-grid,
        .ministeres-grid,
        .eglises-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .founder-card,
        .conseil-card,
        .ministere-card,
        .eglise-card {
            overflow: hidden;
        }

        .founder-card img,
        .eglise-img {
            width: 100%;
            height: 320px;
            object-fit: cover;
            border-radius: 12px;
            display: block;
        }

        .founder-info,
        .eglise-body {
            padding: 1.5rem;
        }

        .founder-tag,
        .eglise-badge,
        .ministeicard,
        .pilier-tag {
            color: var(--color-sky);
        }

        .conseil-card {
            background: #fff;
            border: 1px solid rgba(69, 189, 253, 0.12);
            padding: 1.5rem;
            transition: border-color 0.25s ease, transform 0.25s ease;
        }

        .conseil-card:hover {
            border-color: rgba(69, 189, 253, 0.4);
            transform: translateY(-3px);
        }

        .ministere-card {
            background: var(--color-black);
            padding: 2rem 1.8rem;
            border: 1px solid rgba(69, 189, 253, 0.15);
            color: #fff;
            position: relative;
            overflow: hidden;
            border-bottom: 3px solid var(--color-sky);
        }

        .ministere-card:hover::after {
            height: 100%;
        }

        .ministere-card::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 0;
            background: rgba(69, 189, 253, 0.05);
            transition: height 0.3s ease;
        }

        .ministere-card p,
        .conseil-card p,
        .eglise-body p {
            color: rgba(255, 255, 255, 0.75);
            line-height: 1.65;
        }

        .eglise-card {
            background: #fff;
            border: 1px solid rgba(69, 189, 253, 0.15);
            transition: box-shadow 0.3s ease, transform 0.3s ease;
        }

        .eglise-card:hover {
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.08);
            transform: translateY(-4px);
        }

        .eglise-img {
            background: var(--color-black) center/cover no-repeat;
            position: relative;
        }

        .eglise-img::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.7), transparent);
        }

        .eglise-city {
            position: absolute;
            bottom: 16px;
            left: 20px;
            z-index: 2;
            font-family: 'Playfair Display', serif;
            font-size: 1.25rem;
            color: #fff;
            font-weight: 700;
        }

        .eglise-address span {
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .implantation-cta {
            background: var(--color-black);
            padding: 3.5rem 4rem;
            border-radius: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            color: #fff;
        }

        .implantation-cta h3 {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            line-height: 1.2;
            color: #fff;
            margin: 0;
        }

        .implantation-cta p {
            font-size: 0.95rem;
            color: rgba(255, 255, 255, 0.75);
            margin: 0.75rem 0 0;
        }

        .btn-gold {
            background: var(--color-sky);
            color: var(--color-black);
            border-radius: 999px;
            padding: 0.85rem 1.75rem;
            text-decoration: none;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.2em;
        }

        .bridge {
            background: var(--color-black);
            padding: 3.5rem 10vw;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            color: #fff;
            margin: 4rem 0;
            border-radius: 12px;
        }

        .bridge-text h3 {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            line-height: 1.2;
            margin: 0;
        }

        .bridge-text h3 em {
            color: var(--color-sky-light);
        }

        .btn-outline-gold {
            border: 1px solid rgba(69, 189, 253, 0.5);
            color: var(--color-sky);
            background: transparent;
            padding: 0.85rem 1.75rem;
            text-decoration: none;
            border-radius: 999px;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            font-weight: 700;
        }

        @media (max-width: 960px) {
            .histoire-grid,
            .vision-mission,
            .founders,
            .eglises-grid,
            .implantation-cta,
            .bridge {
                grid-template-columns: 1fr;
            }
            .conseil-grid,
            .ministeres-grid {
                grid-template-columns: 1fr 1fr;
            }
            nav .nav-links {
                display: none;
            }
            .bridge {
                flex-direction: column;
                text-align: center;
            }
        }

        @media (max-width: 600px) {
            section,
            #histoire,
            #equipe,
            #eglises {
                padding: 72px 6vw;
            }
            .subnav-btn {
                padding: 0.9rem 1rem;
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
        <div class="hero-gradient"></div>
        <div class="hero-content">
            <nav class="breadcrumb" aria-label="Fil d'ariane">
                <a href="/">Accueil</a>
                <span>›</span>
                <span class="current">À Propos</span>
            </nav>
            <h1>Qui sommes-nous ?<br><em>Notre histoire,<br>nos visages, notre famille.</em></h1>
            <p class="hero-sub">Une communauté fondée sur la foi, unie par l'amour, envoyée pour transformer le Cameroun et le monde.</p>
        </div>
    </section>

    <div class="subnav" id="subnav">
        <div class="subnav-inner">
            <button class="subnav-btn active" onclick="document.querySelector('#histoire').scrollIntoView({behavior:'smooth', block:'start'});">01 Notre Histoire & Valeurs</button>
            <button class="subnav-btn" onclick="document.querySelector('#equipe').scrollIntoView({behavior:'smooth', block:'start'});">02 Notre Équipe</button>
            <button class="subnav-btn" onclick="document.querySelector('#eglises').scrollIntoView({behavior:'smooth', block:'start'});">03 Nos Implantations</button>
        </div>
    </div>

    <section id="histoire">
        <div class="reveal">
            <p class="section-eyebrow">01 — Notre Histoire</p>
            <h2 class="section-title">L'EMEC : une histoire de <em>foi,</em><br>de croissance et d'impact</h2>
        </div>

        <div class="histoire-grid">
            <div class="histoire-text reveal">
                <p>L'Église Messianique Évangélique du Cameroun (EMEC) a été fondée sur des principes solides de foi et de dévotion. Depuis ses humbles débuts, elle s'est développée pour devenir une communauté dynamique, dédiée à la proclamation de l'Évangile et au service de la société.</p>
                <p>Sous la vision apostolique de l'Apôtre Samuel Dalle, l'EMEC est aujourd'hui un phare d'espoir et de transformation, touchant d'innombrables vies à travers le Cameroun et au-delà — par l'enseignement profond de la Parole, le service concret aux plus démunis et une présence forte dans les cités.</p>
                <div class="verse-block">
                    <blockquote>« Mais vous, vous êtes une race choisie, un sacerdoce royal, une nation sainte, un peuple acquis, afin que vous annonciez les vertus de celui qui vous a appelés des ténèbres à son admirable lumière. »</blockquote>
                    <cite>1 Pierre 2:9</cite>
                </div>
            </div>
            <div class="vision-mission reveal">
                <div class="vm-card">
                    <h3>Notre Vision</h3>
                    <p>Devenir une église dynamique et influente, transformant des vies et des communautés par la puissance de l'Évangile, et rayonnant l'amour de Christ dans le monde entier.</p>
                </div>
                <div class="vm-card">
                    <h3>Notre Mission</h3>
                    <p>Proclamer la bonne nouvelle de Jésus-Christ, faire des disciples de toutes les nations, les baptiser et les enseigner — tout en manifestant l'amour de Dieu par des œuvres de compassion, de justice et de paix.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="equipe">
        <div class="reveal">
            <p class="section-eyebrow">02 — Notre Équipe</p>
            <h2 class="section-title">Les visages dévoués<br>de <em>l'EMEC</em></h2>
        </div>
        <div class="founders reveal">
            <div class="founder-card">
                <img src="{{ asset('images/home-11.jpeg') }}" alt="Apôtre Samuel Dalle" />
                <div class="founder-info">
                    <p class="founder-tag">Berger Principal & Fondateur</p>
                    <h3 class="founder-name">Apôtre Samuel Dalle</h3>
                    <p class="founder-role">Fondateur de la communauté EMEC</p>
                    <p class="founder-quote">« Ma vision est de voir chaque membre de l'EMEC devenir un disciple mature de Christ, équipé pour impacter positivement son environnement. »</p>
                </div>
            </div>
            <div class="founder-card">
                <img src="{{ asset('images/home-7.jpg') }}" alt="Pasteur Grâce Dalle" />
                <div class="founder-info">
                    <p class="founder-tag">Co-Fondatrice</p>
                    <h3 class="founder-name">Pasteure Grâce Dalle</h3>
                    <p class="founder-role">Pilier spirituel de l'EMEC</p>
                    <p class="founder-quote">« Ensemble, nous bâtissons un lieu où chaque vie trouve sa restauration et sa destinée en Dieu. »</p>
                </div>
            </div>
        </div>
        <div class="conseil-title reveal">Conseil Exécutif</div>
        <p class="conseil-desc reveal">Le Conseil Exécutif travaille en étroite collaboration avec les fondateurs pour assurer la vision et soutenir les différents ministères.</p>
        <div class="conseil-grid reveal">
            <div class="conseil-card">
                <div class="conseil-avatar">SG</div>
                <h4>Secrétaire Général</h4>
                <p>Administration & Gouvernance</p>
            </div>
            <div class="conseil-card">
                <div class="conseil-avatar">TR</div>
                <h4>Trésorier</h4>
                <p>Finances & Ressources</p>
            </div>
            <div class="conseil-card">
                <div class="conseil-avatar">CO</div>
                <h4>Coordinateur de Ministères</h4>
                <p>Coordination & Stratégie</p>
            </div>
        </div>
        <div class="ministeres-title reveal">Les Organes de l'EMEC</div>
        <div class="ministeres-grid reveal">
            <div class="ministere-card"><span class="min-icon">🌸</span><h4>Groupe des Femmes</h4><p>Un espace de croissance spirituelle, de soutien mutuel et d'engagement communautaire.</p></div>
            <div class="ministere-card"><span class="min-icon">📖</span><h4>ECODIM</h4><p>L'école du dimanche de l'EMEC — un enseignement biblique vivant et adapté aux enfants.</p></div>
            <div class="ministere-card"><span class="min-icon">⚡</span><h4>Jeunes Pour Christ</h4><p>Le ministère des jeunes — dédié à l'épanouissement spirituel et social d'une génération engagée.</p></div>
        </div>
    </section>

    <div class="bridge reveal">
        <div class="bridge-text">
            <p class="eyebrow">Ensuite</p>
            <h3>Trouvez <em>une église EMEC</em> près de chez vous et rejoignez votre famille locale.</h3>
        </div>
        <a href="#eglises" class="btn-outline-gold">Voir les implantations</a>
    </div>

    <section id="eglises">
        <div class="reveal">
            <p class="section-eyebrow">03 — Nos Implantations</p>
            <h2 class="section-title">Une famille en pleine<br><em>croissance</em> à travers le Cameroun</h2>
        </div>
        <div class="eglises-grid reveal">
            <div class="eglise-card">
                <div class="eglise-img" style="background-image: url({{ asset('images/home-2.jpg') }});"></div>
                <div class="eglise-body">
                    <span class="eglise-badge">Siège National</span>
                    <h4>EMEC Yaoundé — Siège</h4>
                    <p>L'église mère, cœur battant de toute la vision EMEC. Cultes, formations et administration centrale.</p>
                    <div class="eglise-address"><span>📍 Entrée OPEP, Minboman, Yaoundé</span></div>
                </div>
            </div>
            <div class="eglise-card">
                <div class="eglise-img" style="background-image: url({{ asset('images/home-4.jpg') }});"></div>
                <div class="eglise-body">
                    <span class="eglise-badge">Église Locale</span>
                    <h4>EMEC Douala</h4>
                    <p>Une communauté dynamique au cœur de la capitale économique — portes ouvertes à tous.</p>
                    <div class="eglise-address"><span>📍 Douala, Cameroun</span></div>
                </div>
            </div>
            <div class="eglise-card">
                <div class="eglise-img" style="background-image: url({{ asset('images/home-6.jpg') }});"></div>
                <div class="eglise-body">
                    <span class="eglise-badge">Église Locale</span>
                    <h4>EMEC Bertoua</h4>
                    <p>En pleine expansion dans l'Est Cameroun — rejoignez la campagne d'évangélisation en cours.</p>
                    <div class="eglise-address"><span>📍 Bertoua, Est Cameroun</span></div>
                </div>
            </div>
        </div>
        <div class="implantation-cta reveal">
            <div>
                <h3>Votre ville n'est pas encore couverte ?<br><em>Devenez un pionnier.</em></h3>
                <p>Contactez-nous pour implanter une cellule ou une église EMEC dans votre quartier ou ville.</p>
            </div>
            <a href="/contact-us" class="btn-gold">Nous contacter</a>
        </div>
    </section>
@endsection
