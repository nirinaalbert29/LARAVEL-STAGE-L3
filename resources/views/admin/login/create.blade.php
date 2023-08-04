<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>création nouveau Compte-admin</title>
    <link rel="stylesheet" href="{{asset('bootstrap/css/bootstrap.min.css')}}">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="{{asset('bootstrap/js/bootstrap.min.js')}}"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.5/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.5/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Animation de fondu entrant */
        .modal.fade .modal-dialog {
          transform: translate(0, -50%);
          opacity: 0;
          transition: transform 0.3s ease-out, opacity 0.3s ease-out;
        }
        .modal.fade.show .modal-dialog {
          transform: translate(0, 0);
          opacity: 1;
        }
      </style>
       <style>
        /* Styliser l'input file */
        .custom-file-input {
          visibility: hidden;
          width: 0;
        }

        /* Styliser l'aperçu de l'image */
        .profile-image {
          width: 150px;
          height: 150px;
          border-radius: 50%;
          object-fit: cover;
        }

        /* Ajouter un contour autour de l'aperçu de l'image */
        .profile-image-container {
          width: 150px;
          height: 150px;
          border: 2px solid #ccc;
          border-radius: 50%;
          overflow: hidden;
        }
      </style>
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
    @if (session()->has('successCreate'))
            <script>
                Swal.fire(
                'Félicitation !',
                '{{session('successCreate')}}',
                'success'
                )
            </script>
        @endif

    <div class="layer"></div>
<main class="page-center">
  <article class="sign-up">
    <form class="sign-up-form form" action="/create-admin" method="post">
        @csrf
        <div class="d-flex justify-content-center">
            <img src="/logo_open.jpg" class="rounded-circle"
                    alt="logo" srcset="" width="100">
        </div>
        <h3 class="text-primary mb-4">Créer Nouveau Compte</h3>
            <div class="form-group">
                <label for="nom">Nom Admin</label>
                <input type="text" class="form-control form-input" placeholder="Saisir nom_admin" name="nom_admin" required>
            </div>
            <div class="form-group">
                <label for="nom">Email Admin</label>
                <input type="text" class="form-control form-input" placeholder="Saisir Email admin" name="email_admin" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe:</label>
                <div class="input-group">
                    <input type="password" class="form-control form-input" placeholder="Saisir mot de passe" name="mdp1" required>
                    <div class="input-group-append">
                      <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                        <i class="fas fa-eye"></i>
                      </button>
                    </div>
                  </div>
            </div>
            <div class="form-group">
                <label for="password">Confirmez Mot de passe:</label>
                <div class="input-group">
                    <input type="password" class="form-control form-input" placeholder="Saisir mot de passe" name="mdp2" required>
                    <div class="input-group-append">
                      <button class="btn btn-outline-secondary" type="button" id="togglePassword1">
                        <i class="fas fa-eye"></i>
                      </button>
                    </div>
                  </div>
            </div>
      <label class="form-checkbox-wrapper">
        <input class="form-checkbox" type="checkbox" required>
        <span class="form-checkbox-label">Confirmez-moi!</span>
      </label>
      <button type="submit" class="form-btn primary-default-btn transparent-btn">Créer</button><br>
      <a href="/login-admin"> <p> << J'ai Déjà de compte , Connecte </p></a>
    </form>
  </article>
</main>
    <script>
        document.querySelector('form').addEventListener('submit', function(event) {
            event.preventDefault(); // Empêche la soumission du formulaire

            // Récupère les valeurs des champs de mot de passe
            var password1 = document.querySelector('input[name="mdp1"]').value;
            var password2 = document.querySelector('input[name="mdp2"]').value;

            // Vérifie si les mots de passe sont différents
            if (password1 !== password2) {
                Swal.fire(
                    'Erreur',
                    'Les mots de passe ne correspondent pas.',
                    'error'
                );
            } else {
                // Les mots de passe sont identiques, vous pouvez soumettre le formulaire ici
                event.target.submit();
            }
        });
    </script>

<script>
    var togglePassword = document.getElementById('togglePassword');
    var passwordInput = document.querySelector('input[name="mdp1"]');

    togglePassword.addEventListener('click', function() {
      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        togglePassword.innerHTML = '<i class="fas fa-eye-slash"></i>';
      } else {
        passwordInput.type = 'password';
        togglePassword.innerHTML = '<i class="fas fa-eye"></i>';
      }
    });
  </script>
  <script>
    var togglePassword1 = document.getElementById('togglePassword1');
    var passwordInput1 = document.querySelector('input[name="mdp2"]');

    togglePassword1.addEventListener('click', function() {
      if (passwordInput1.type === 'password') {
        passwordInput1.type = 'text';
        togglePassword1.innerHTML = '<i class="fas fa-eye-slash"></i>';
      } else {
        passwordInput1.type = 'password';
        togglePassword1.innerHTML = '<i class="fas fa-eye"></i>';
      }
    });
  </script>
</body>
</html>
