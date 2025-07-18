<!DOCTYPE html>
<html>
<head>
    <title>Stripe Payment</title>
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body class="p-8">
    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif
    @if(session('error'))
        <p style="color: red;">{{ session('error') }}</p>
    @endif

    <form action="{{ route('payment.process') }}" method="POST" id="payment-form">
        @csrf
        <input type="text" name="cardholder-name" placeholder="Cardholder Name" required><br><br>

        <div id="card-element"></div>
        <br>
        <button id="card-button">Pay $10</button>
    </form>

    <script>
        const stripe = Stripe("{{ config('services.stripe.key') }}");
        const elements = stripe.elements();
        const cardElement = elements.create('card');
        cardElement.mount('#card-element');

        const form = document.getElementById('payment-form');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            const {token, error} = await stripe.createToken(cardElement);

            if (error) {
                alert(error.message);
            } else {
                const hiddenInput = document.createElement('input');
                hiddenInput.setAttribute('type', 'hidden');
                hiddenInput.setAttribute('name', 'stripeToken');
                hiddenInput.setAttribute('value', token.id);
                form.appendChild(hiddenInput);

                form.submit();
            }
        });
    </script>
</body>
</html>