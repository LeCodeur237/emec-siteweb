<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('css/css.css') }}">
    <style>
        @import url(https://fonts.googleapis.com/css?family=Roboto:300,400,700&display=swap);

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            overflow-x: hidden;
            margin: 0;
            padding: 0;
        }

        .content {
            margin: 0;
            padding: 0;
        }
    </style>

    @stack('styles')

    <title>EMEC | Eglise Messiannique Evangélique du Cameroun</title>
</head>

<body>
    @include('widgets.header')
    <div class="content">
        @yield('content')
    </div>
    @include('widgets.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            window.addEventListener('scroll', function() {
                const navbar = document.getElementById('mainNav');
                const logo = document.getElementById('logoImg');
                const navLinks = navbar.querySelectorAll('.nav-link');
                const logoWhite = "{{ asset('logo/emec-logo-white.png') }}";
                const logoBlack = "{{ asset('logo/emec-logo-black.png') }}";

                if (window.scrollY > 50) {
                    navbar.classList.add('bg-white', 'navbar-light');
                    navbar.classList.remove('bg-transparent', 'navbar-dark');
                    logo.src = logoBlack;
                    navLinks.forEach(link => {
                        link.classList.add('text-dark');
                        link.classList.remove('text-white');
                    });
                } else {
                    navbar.classList.remove('bg-white', 'navbar-light');
                    navbar.classList.add('bg-transparent', 'navbar-dark');
                    logo.src = logoWhite;
                    navLinks.forEach(link => {
                        link.classList.remove('text-dark');
                        link.classList.add('text-white');
                    });
                }
            });
        });


        document.addEventListener('DOMContentLoaded', function() {
            const mobileMoneyModalEl = document.getElementById('mobileMoneyModal');
            const cardPaymentModalEl = document.getElementById('cardPaymentModal');

            const donationForm = document.getElementById('donation-form');
            const paymentOptions = document.querySelectorAll('.payment-method-option');
            const mobileMoneyInputs = mobileMoneyModalEl.querySelectorAll('input');
            const cardInputs = cardPaymentModalEl.querySelectorAll('input');
            const continueBtn = document.getElementById('continue-btn');

            // Sélection visuelle de l'option de paiement
            paymentOptions.forEach(option => {
                option.addEventListener('click', function() {
                    paymentOptions.forEach(opt => opt.classList.remove('selected'));
                    this.classList.add('selected');
                    this.querySelector('input[type="radio"]').checked = true;
                });
            });

            continueBtn.addEventListener('click', function() {
                if (!donationForm.checkValidity()) {
                    donationForm.reportValidity();
                    return;
                }

                const selectedOption = document.querySelector('input[name="payment_method"]:checked');
                if (!selectedOption) {
                    alert('Veuillez choisir une méthode de paiement avant de continuer.');
                    return;
                }

                const paymentType = selectedOption.closest('.payment-method-option').dataset.payment;
                const paymentName = selectedOption.nextElementSibling.innerText;

                if (paymentType === 'mobile') {
                    cardInputs.forEach(input => input.required = false);
                    mobileMoneyInputs.forEach(input => input.required = true);

                    // Mettre à jour le titre de la modale
                    mobileMoneyModalEl.querySelector('.modal-title').innerText =
                        `Paiement par ${paymentName}`;

                    document.getElementById('trigger-mobile-money').click();
                } else if (paymentType === 'card') {
                    mobileMoneyInputs.forEach(input => input.required = false);
                    cardInputs.forEach(input => input.required = true);

                    document.getElementById('trigger-card-payment').click();
                }
            });
        });
    </script>
</body>

</html>
