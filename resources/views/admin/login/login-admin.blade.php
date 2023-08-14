<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login Compte-admin</title>
    <link rel="icon" href="/icon_ico.png" type="image/x-icon">
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
        body {
            background: linear-gradient(100deg, #2e1322, #531f34, #bb2e72);
        }
        #divimg{
            background: linear-gradient(100deg, #e84393,#413e58, #2e1322);
            color: #f8f9fa;
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
                'Votre mot de passe est incorrect!',
                'error'
                )
            </script>
        @endif
        <div class="layer"></div>

<main class="page-center">
    <div class="row">
        <div class="col-6">
        <img src="/log_inter.svg" alt="" style="width: 100%">
        </div>
        <div class="col-6">
            <article class="sign-up" >

                <form class="sign-up-form form" action="login-admin" method="POST" id="divimg">
                    @csrf

                    <div class="d-flex justify-content-center">
                        <img src="/logo_open.jpg" class="rounded-circle"
                                alt="logo" srcset="" width="100">
                    </div>
                    <h3 class="sign-up__title text-white">Connection Admin</h3>
                    <div class="form-group">
                        <label for="nom">Nom Administrateur</label>
                        <input type="text" class="form-control form-input" placeholder="Saisir nom admin ..." name="nom_admin" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Mot de passe:</label>
                        <div class="input-group">
                            <input type="password" class="form-control form-input" placeholder="Saisir mot de passe" name="mdp_admin" required>
                            <div class="input-group-append">
                              <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                <i class="fas fa-eye"></i>
                              </button>
                            </div>
                        </div>
                    </div>
                  <button class="form-btn primary-default-btn transparent-btn my-3">Connecter</button>
                  <a href="/create-admin"> <p class="my-4"><i class="fas fa-plus"></i> Créer nouveau Compte>></p></a>
                  <a href="/mdpoublie-admin"> <p class="my-4"><i class="fas fa-trash"></i> Mot de passe Oublié ???>></p></a>
                </form>
              </article>
        </div>
    </div>
</main>


<script>
    var togglePassword = document.getElementById('togglePassword');
    var passwordInput = document.querySelector('input[name="mdp_admin"]');

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
</body>
</html>
