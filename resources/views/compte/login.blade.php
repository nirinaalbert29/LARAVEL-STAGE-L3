<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login Compte</title>
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
            background: linear-gradient(100deg, #34204e, #880260, #1e1d2c);
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
            <main class="page-center">
                <article class="sign-up">
                  <form class="sign-up-form form" action="/" method="POST" id="divlog">
                      @csrf
                      <div class="d-flex justify-content-center">
                          <img src="/logo_open.jpg" class="rounded-circle"
                                  alt="logo" srcset="" width="100">
                      </div>
                      <h3 class="sign-up__title">Connectez-Vous</h3>
                      <div class="form-group">
                          <label for="nom">Nom Intervenant</label>
                          <input type="text" class="form-control form-input" placeholder="Saisir nom de l'intervenant" name="interv" required>
                      </div>
                      <div class="form-group">
                          <label for="password">Mot de passe:</label>
                          <div class="input-group">
                              <input type="password" class="form-control form-input" placeholder="Saisir mot de passe" name="mdp" required>
                              <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                  <i class="fas fa-eye"></i>
                                </button>
                              </div>
                            </div>
                      </div>
                    <button class="form-btn primary-default-btn transparent-btn my-3">Connecter</button>
                    <a href="/newcompte"> <p class="my-4"><i class="fas fa-plus"></i> Créer nouveau Compte>></p></a>
                    <a href="/mdpoublie"> <p class="my-4"><i class="fas fa-trash"></i> Mot de passe Oublié ???>></p></a>
                  </form>
                </article>
              </main>
<script>
    var togglePassword = document.getElementById('togglePassword');
    var passwordInput = document.querySelector('input[name="mdp"]');

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
