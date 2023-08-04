<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Mot de passe Oublie</title>
    <link rel="stylesheet" href="{{asset('bootstrap/css/bootstrap.min.css')}}">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="{{asset('bootstrap/js/bootstrap.min.js')}}"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.5/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.5/dist/sweetalert2.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="shortcut icon" href="./logo_open.jpg" type="image/x-icon">
<!-- Custom styles -->
<link rel="stylesheet" href="./css/style.min.css">
<style>
    body {
        /* Utilisez la fonction d'aide asset pour obtenir le chemin complet de l'image */
        background-image: url("{{ asset('iconlog.jpg') }}");
        /* Réglez les autres propriétés CSS pour la mise en page selon vos besoins */
        background-size: cover;
        background-repeat: no-repeat;
        /* Vous pouvez également définir la hauteur et la largeur de la section si nécessaire */
        height: 100vh;
    }
</style>
</head>
<body>
    @if (session()->has('erroremail'))
            <script>
                Swal.fire(
                'Erreur!',
                '{{ session('erroremail') }}',
                'error'
                )
            </script>
        @endif
        <div class="layer"></div>
<main class="page-center">
  <article class="sign-up">
    <h1 class="sign-up__title">Mot de passe oublié Admin</h1>
    <form class="sign-up-form form" action="/mdpoublie-admin" method="POST">
        @csrf
        <div class="d-flex justify-content-center">
            <img src="/logo_open.jpg" class="rounded-circle"
                    alt="logo" srcset="" width="100">
        </div>
        <div class="form-group">
            <label for="password">Saisir Votre Email:</label>
            <div class="input-group">
                <input type="email" class="form-control form-input" placeholder="Saisir votre email..." name="mail" required>
              </div>
        </div>
      <button class="form-btn primary-default-btn transparent-btn">Valider</button><br>
      <a href="/login-admin"> <p> << Retour au Login.. </p></a>
    </form>
  </article>
</main>
</body>
</html>
