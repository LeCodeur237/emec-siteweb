
@push('styles')
    <style>
        @media (max-width: 992px) {
            .navbar-collapse {
                background-color: rgba(0, 0, 0, 0.95);
                padding: 1rem;
                margin-top: 10px;
                border-radius: 5px;
                max-height: 80vh;
                overflow-y: auto;
            }
            .nav-links {
                flex-direction: column;
                gap: 1rem;
            }
            .navbar-nav {
                width: 100%;
            }
            .nav-item {
                width: 100%;
                text-align: center;
            }
            .nav-link {
                width: 100%;
            }
            .dropdown-menu img {
                display: none;
            }
        }
    </style>
@endpush

<header>
    <nav class="navbar navbar-expand-lg fixed-top" id="mainNav">
        <div class="container">
            <a class="nav-logo" href="/">EM<span>EC</span></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <div class="nav-links ms-auto">
                    <a class="nav-link" href="/">Accueil</a>
                    <a class="nav-link" href="/about-us">À Propos</a>
                    <a class="nav-link" href="/events">Programmes</a>
                    <a class="nav-link" href="/mandate">Notre Mandat</a>
                    <a class="nav-link" href="/media-center">Médias</a>
                    <a class="nav-link" href="/contact-us">Contact</a>
                    <a class="nav-cta" href="/get-connected">Je veux suivre Christ</a>
                </div>
            </div>
        </div>
    </nav>
</header>
