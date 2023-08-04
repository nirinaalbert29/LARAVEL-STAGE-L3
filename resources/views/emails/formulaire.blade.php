<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>formulaire</title>
</head>
<body>

@if(session('success'))
<p>{{ session('success') }}</p>
@endif


<form method="POST" action="{{ route('envoyer.message') }}">
    @csrf

    <label for="email">Adresse e-mail :</label>
    <input type="email" name="email" id="email" required>

    <label for="message">Message :</label>
    <textarea name="message" id="message" required></textarea>

    <button type="submit">Envoyer</button>
</form>
</body>
</html>
