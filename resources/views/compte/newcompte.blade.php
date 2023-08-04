<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>création nouveau Compte</title>
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
    <link rel="shortcut icon" href="./logo_open.jpg" type="image/x-icon">
<!-- Custom styles -->
<link rel="stylesheet" href="./css/style.min.css">
</head>
<body>
    @if (session()->has('successCreate'))
            <script>
                Swal.fire(
                'Félicitation !',
                'Votre compte est crée avec succè!',
                'success'
                )
            </script>
        @endif
        @if (session()->has('successAjout'))
        <script>
            Swal.fire(
                'Succès!',
                '{{ session('successAjout') }}',
                'success'
            )
        </script>
    @endif
    @if (session()->has('incorrect'))
        <script>
            Swal.fire(
                'Succès!',
                '{{ session('incorrect') }}',
                'error'
            )
        </script>
    @endif


    <div class="layer"></div>
<main class="page-center">
  <article class="sign-up">

    <form class="sign-up-form form" action="/compte-create" method="post">
        @csrf
        <div class="d-flex justify-content-center">
            <img src="/logo_open.jpg" class="rounded-circle"
                    alt="logo" srcset="" width="100">
        </div>
        <h1 class="sign-up__title">Créer nouveau Compte</h1>
            <div class="form-group">
                <label for="nom">Email Intervenant</label>
                <input type="email" class="form-control form-input" placeholder="Saisir email de l'intervenant" name="interv" required>
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
      <a href="/"> <p> << J'ai Déjà de compte , Connecte </p></a>
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



<!-- Modal d'ajout -->
<div class="modal fade" id="myModalAjout" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- En-tête de la fenêtre modale -->
            <div class="modal-header"><br>
                <h5 class="modal-title">Ajout Nouveau Intervenant</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!-- Contenu de la fenêtre modale -->
            <div class="modal-body">
                <form action="/intervenant-ajout-login" class="was-validated" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="container">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-4">
                                    <div class="profile-image-container">
                                        <img src="#" id="preview-image" class="profile-image" alt="Photo de profil">
                                    </div>
                                </div>
                                <div class="col-8">
                                    <label for="profile-picture" class="btn btn-outline-primary">
                                        Choisir une image
                                        <input type="file" id="profile-picture" class="custom-file-input" accept="image/*" name="photo_i">
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="nom">Nom intervenant:</label>
                            <input type="text" class="form-control form-input" placeholder="Saisir le nom de l'intervenant" name="nom_i" required>

                        </div>
                        <div class="form-group">
                            <label for="prenom">Prénom intervenant:</label>
                            <input type="text" class="form-control form-input" placeholder="Saisir le prénom de l'intervenant" name="prenom_i" required>

                        </div>
                        <div class="form-group">
                            <label for="email">Email intervenant:</label>
                            <input type="email" class="form-control form-input" placeholder="Saisir l'email de l'intervenant" name="email_i" required>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-primary save-btn">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Prévisualiser l'image sélectionnée avant de l'uploader
    $('.custom-file-input').change(function() {
        var id = $(this).attr('id').split('-')[2];
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#preview-image-' + id).attr('src', e.target.result);
            }
            reader.readAsDataURL(this.files[0]);
        }
    });

    // Prévisualiser l'image2 sélectionnée avant de l'uploader
    $('.custom-file-input').change(function() {
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#preview-image').attr('src', e.target.result);
            }
            reader.readAsDataURL(this.files[0]);
        }
    });
</script>
</body>
</html>
