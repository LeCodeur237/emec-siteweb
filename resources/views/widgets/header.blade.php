
<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-transparent fixed-top" id="mainNav">
        <div class="container">
            <a class="navbar-brand" href="/">
                <img src="{{ asset('logo/emec-logo-white.png') }}" alt="Logo" height="50" id="logoImg">

            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto" style="color: #fff; font-size: 0.7rem; font-weight: bold; text-transform: uppercase;">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="/">Home</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdownChurch"
                            role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Church
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownChurch">
                            <li><a class="dropdown-item" href="/about-us">À propos de nous</a></li>
                            <li><a class="dropdown-item" href="/our-team">Nos organes</a></li>
                            <li><a class="dropdown-item" href="/church">Nos églises</a></li>
                            <li><img src="{{ asset('images/home-2.jpg') }}" alt=""></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="/mandate">The Mandate</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="/get-connected">Get Connected</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="/events">Events</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="/media-center">Media Center</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#">Our Projects</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#">Contact us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#" id="donate-link">Donate</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
