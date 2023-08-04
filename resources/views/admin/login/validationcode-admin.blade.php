<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Validation Compte</title>
    <link rel="stylesheet" href="{{asset('bootstrap/css/bootstrap.min.css')}}">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="{{asset('bootstrap/js/bootstrap.min.js')}}"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.5/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.5/dist/sweetalert2.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .input-group-text {
            cursor: pointer;
        }

        .input-group-text i {
            pointer-events: none;
        }

        .form-control:focus + .input-group-append .input-group-text {
            background-color: #f8f9fa;
        }

        .form-control:focus + .input-group-append .input-group-text i {
            color: #495057;
        }
    </style>
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
    <link rel="shortcut icon" href="./logo_open.jpg" type="image/x-icon">
<!-- Custom styles -->
<link rel="stylesheet" href="./css/style.min.css">
</head>
<body>
    @if (session()->has('incorrect'))
            <script>
                Swal.fire(
                'Erreur!',
                '{{ session('incorrect') }}',
                'error'
                )
            </script>
        @endif
        @if (session()->has('validmdp'))
            <script>
                Swal.fire(
                'Réussit!',
                '{{ session('validmdp') }}',
                'success'
                )
            </script>
        @endif
<main class="page-center">
  <article class="sign-up">
    <h2 class="sign-up__title">Validation Mot de passe</h2>
    <form class="sign-up-form form" action="#" method="">
        @csrf
        <div class="d-flex justify-content-center">
            <img src="/logo_open.jpg" class="rounded-circle"
                    alt="logo" srcset="" width="100">
        </div>
        <input type="hidden" name="code" value="{{ $code }}">
        <div class="form-group">
            <label for="password">Entrez le Code de Validation :</label>
            <div class="input-group">
                <input type="number" class="form-control form-input" placeholder="Saisir code de validation..." name="mail" required>
              </div>
        </div>
        <button class="form-btn primary-default-btn transparent-btn" type="submit">VALIDER</button><br>
      <a href="/login-admin"> <p> << Retour au Login.. </p></a>
    </form>
  </article>
</main>
<script>
    // Sélectionnez le formulaire par son ID ou classe CSS (selon vos besoins) et écoutez l'événement de soumission
    document.querySelector('.sign-up-form').addEventListener('submit', function(event) {
        event.preventDefault(); // Empêche la soumission normale du formulaire
        validerCode(); // Appelez votre fonction de validation
    });

    function validerCode() {
        const codeSaisi = document.querySelector('input[name="mail"]').value;
        const codeValidation = document.querySelector('input[name="code"]').value;

        if (codeSaisi === codeValidation) {
            const mdp = "{{ $mdp }}"; // Récupérez le mot de passe depuis la variable PHP $mdp
            Swal.fire({
                icon: 'success',
                title: 'Code de validation correct',
                text: 'Votre mot de passe est : ' + mdp,
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Code de validation incorrect',
                text: 'Veuillez saisir le code de validation correct.',
            });
        }
    }
</script>
</body>
</html>
