@extends('index')

@push('styles')
    <style>
        .small-header {
            background-image: linear-gradient(to top,
                    rgba(0, 0, 0, 0.832),
                    rgba(0, 0, 0, 0.75)),
                url({{ asset('images/home-2.jpg') }});
        }

        .donation-form-container {
            background-color: #f8f9fa;
            padding: 2.5rem;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .payment-methods {
            margin-top: 2rem;
            margin-bottom: 2rem;
        }

        .payment-method-option {
            display: flex;
            align-items: center;
            padding: 1rem;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
        }

        .payment-method-option:not(:last-child) {
            margin-bottom: 1rem;
        }

        .payment-method-option:hover,
        .payment-method-option.selected {
            border-color: #ffb700;
            background-color: #fff;
        }

        .payment-method-option input[type="radio"] {
            margin-right: 1rem;
        }

        .payment-method-option .payment-logo {
            height: 25px;
            margin-left: auto;
        }
    </style>
@endpush

@section('content')
<section class="small-header">
    <div>
        <div class="line"></div>
        <h1>Faire un Don</h1>
        <p>Votre généreuse contribution nous aide à poursuivre notre mission.</p>
    </div>
</section>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="donation-form-container">
                <h2 class="text-center mb-4">Soutenez notre mission</h2>

                <form action="{{ route('donate.store') }}" method="POST" id="donation-form">
                    @csrf

                    <div class="mb-3">
                        <label for="amount" class="form-label">Montant du don (XAF)</label>
                        <input type="number" class="form-control" id="amount" name="amount" min="100"
                            step="100" required placeholder="Ex: 5000">
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label">Nom complet</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>

                    <div class="payment-methods">
                        <h5 class="mb-3">Choisissez votre méthode de paiement</h5>

                        <div class="payment-method-option" data-payment="mobile">
                            <input type="radio" id="mtn" name="payment_method" value="mtn" required>
                            <label for="mtn" class="form-check-label mb-0">MTN Mobile Money</label>
                            <img src="{{ asset('images/payment/mtn.png') }}" alt="MTN Mobile Money"
                                class="payment-logo">
                        </div>

                        <div class="payment-method-option" data-payment="mobile">
                            <input type="radio" id="om" name="payment_method" value="om" required>
                            <label for="om" class="form-check-label mb-0">Orange Money</label>
                            <img src="{{ asset('images/payment/om.png') }}" alt="Orange Money" class="payment-logo">
                        </div>

                        <div class="payment-method-option" data-payment="card">
                            <input type="radio" id="card" name="payment_method" value="card" required>
                            <label for="card" class="form-check-label mb-0">Carte bancaire</label>
                            <img src="{{ asset('images/payment/visa-mastercard.png') }}" alt="Visa & MasterCard"
                                class="payment-logo">
                        </div>
                    </div>

                    <button type="button" id="continue-btn" class="btn btn-primary w-100 mt-4">Continuer vers le paiement</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Boutons cachés pour déclencher les modales via HTML (évite les erreurs JS si bootstrap n'est pas défini) -->
<button type="button" id="trigger-mobile-money" class="d-none" data-bs-toggle="modal" data-bs-target="#mobileMoneyModal"></button>
<button type="button" id="trigger-card-payment" class="d-none" data-bs-toggle="modal" data-bs-target="#cardPaymentModal"></button>

<!-- Modale Mobile Money -->
<div class="modal fade" id="mobileMoneyModal" tabindex="-1" aria-labelledby="mobileMoneyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mobileMoneyModalLabel">Paiement par Mobile Money</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Veuillez entrer votre numéro de téléphone pour initier le paiement.</p>
                <div class="mb-3">
                    <label for="phone_number" class="form-label">Numéro de téléphone</label>
                    <input type="tel" class="form-control" id="phone_number" name="phone_number"
                        placeholder="6XXXXXXXX" form="donation-form">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" form="donation-form" class="btn btn-primary">Effectuer le paiement</button>
            </div>
        </div>
    </div>
</div>

<!-- Modale Carte Bancaire -->
<div class="modal fade" id="cardPaymentModal" tabindex="-1" aria-labelledby="cardPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cardPaymentModalLabel">Paiement par Carte Bancaire</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Veuillez entrer les informations de votre carte.</p>
                <div class="mb-3">
                    <label for="card_holder_name" class="form-label">Nom sur la carte</label>
                    <input type="text" class="form-control" id="card_holder_name" name="card_holder_name"
                        form="donation-form">
                </div>
                <div class="mb-3">
                    <label for="card_number" class="form-label">Numéro de carte</label>
                    <input type="text" class="form-control" id="card_number" name="card_number" form="donation-form"
                        placeholder="•••• •••• •••• ••••">
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="card_expiry" class="form-label">Expiration (MM/AA)</label>
                        <input type="text" class="form-control" id="card_expiry" name="card_expiry"
                            placeholder="MM/AA" form="donation-form">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="card_cvc" class="form-label">CVC</label>
                        <input type="text" class="form-control" id="card_cvc" name="card_cvc" placeholder="123"
                            form="donation-form">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" form="donation-form" class="btn btn-primary">Effectuer le paiement</button>
            </div>
        </div>
    </div>
</div>
@endsection
