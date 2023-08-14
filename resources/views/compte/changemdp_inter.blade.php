<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="{{asset('bootstrap/css/bootstrap.min.css')}}">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="{{asset('bootstrap/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('chart.min.js')}}"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.all.min.js"></script>


    <title>Change mot de passe admin</title>
    <style>
         /* Ajoutez la règle pour continuer l'animation en boucle */
         .animate__pulse {
            animation-iteration-count: infinite;
        }
        .animate__headShake {
            animation-iteration-count: infinite;
        }
    </style>
</head>
<body>
    @if (session()->has('successmodifCompte'))
            <script>
                Swal.fire(
                'Succès !',
                '{{session('successmodifCompte')}}',
                'success'
                )
            </script>
    @endif
        @extends("dashboard.dashboard")
        @section("contenu")
            {{-- <h2 class="main-title">Menu Principale</h2> --}}
            <div class="container-fluide">
                <form class="sign-up-form form" action="/changemdp-inter/{{$intervenant->first()->id}}" method="POST">
                    @csrf
                    @method('put')
                    <h3 class="sign-up__title">Changez Votre Mot de passe</h3>
                    <div class="form-group">
                        <label for="password">Mot de passe Actuel:</label>
                        <div class="input-group">
                            <input type="password" class="form-control form-input" placeholder="Saisir mot de passe" name="mdp_actuel" required>
                            <div class="input-group-append">
                              <button class="btn btn-outline-secondary" type="button" id="togglePassword1">
                                <i class="fas fa-eye"></i>
                              </button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password">Nouveau Mot de passe:</label>
                        <div class="input-group">
                            <input type="password" class="form-control form-input" placeholder="Saisir mot de passe" name="mdp_int1" required>
                            <div class="input-group-append">
                              <button class="btn btn-outline-secondary" type="button" id="togglePassword2">
                                <i class="fas fa-eye"></i>
                              </button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password">Confirmez Nouveau Mot de passe:</label>
                        <div class="input-group">
                            <input type="password" class="form-control form-input" placeholder="Saisir mot de passe" name="mdp_i" required>
                            <div class="input-group-append">
                              <button class="btn btn-outline-secondary" type="button" id="togglePassword3">
                                <i class="fas fa-eye"></i>
                              </button>
                            </div>
                        </div>
                    </div>
                  <button class="form-btn primary-default-btn transparent-btn my-3">Changer</button>
                </form>
            </div>

            <script>
                $(document).ready(function() {
                    // Écouteur d'événement sur le bouton de soumission du formulaire
                    $('.sign-up-form').submit(function(event) {
                        // Récupérer la valeur du champ "Mot de passe Actuel"
                        var mdpActuel = $('input[name="mdp_actuel"]').val();
                        var mdpAdmin1 = $('input[name="mdp_int1"]').val();
                        var mdpA = $('input[name="mdp_i"]').val();

                        // Compareraison mdps
                        if (mdpActuel !== "{{ $compte->mdp }}") {
                            // Empêcher le formulaire de se soumettre
                            event.preventDefault();

                            // Afficher la boîte de dialogue SweetAlert pour le message d'erreur
                            Swal.fire({
                                icon: 'error',
                                title: 'Erreur',
                                text: 'Le mot de passe actuel saisi est incorrect.',
                            });
                        }

                        // Vérifier si les nouveaux mots de passe correspondent
                        else if (mdpAdmin1 !== mdpA) {
                            // Empêcher le formulaire de se soumettre
                            event.preventDefault();

                            // Afficher la boîte de dialogue SweetAlert pour le message d'erreur
                            Swal.fire({
                                icon: 'error',
                                title: 'Erreur',
                                text: 'Les deux nouveaux mots de passe doivent être identiques.',
                            });
                        }
                    });
                });
                </script>
            <script>
                var togglePassword = document.getElementById('togglePassword2');
                var passwordInput = document.querySelector('input[name="mdp_int1"]');

                togglePassword.addEventListener('click', function() {
                  if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    togglePassword.innerHTML = '<i class="fas fa-eye-slash"></i>';
                  } else {
                    passwordInput.type = 'password';
                    togglePassword.innerHTML = '<i class="fas fa-eye"></i>';
                  }
                });
                var togglePassword3 = document.getElementById('togglePassword3');
                var passwordInput3 = document.querySelector('input[name="mdp_i"]');

                togglePassword3.addEventListener('click', function() {
                  if (passwordInput3.type === 'password') {
                    passwordInput3.type = 'text';
                    togglePassword3.innerHTML = '<i class="fas fa-eye-slash"></i>';
                  } else {
                    passwordInput3.type = 'password';
                    togglePassword3.innerHTML = '<i class="fas fa-eye"></i>';
                  }
                });
                var togglePassword1 = document.getElementById('togglePassword1');
                var passwordInput1 = document.querySelector('input[name="mdp_actuel"]');

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
        @endsection
</body>
</html>
