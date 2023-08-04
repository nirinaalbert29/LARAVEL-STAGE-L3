<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('bootstrap/css/bootstrap.min.css')}}">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="{{asset('bootstrap/js/bootstrap.min.js')}}"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.5/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.5/dist/sweetalert2.min.js"></script>
    <title>liste des intervenant</title>
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
</head>
<body>
        @extends("admin.layout.admin-dashboard")
        @section("contenu")
        @if (session()->has('success'))
            <script>
                Swal.fire(
                'Succès!',
                'Modification Intervenant Réussite!',
                'success'
                )
            </script>
        @endif


        @if ($errors->any())
        @foreach ($errors->all() as $error)
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: '{{ $error }}',
                });
            </script>
        @endforeach
@endif

        <!-- Afficher les erreurs de validation -->
        @if (session()->has('errorFile'))
        <script>
            Swal.fire(
                'Erreur !',
                '{{ session('errorFile') }}',
                'error'
            )
        </script>
        @endif

        @if (session()->has('successAjout'))
            <script>
                Swal.fire(
                'Succès!',
                'Ajout Intervenant Réussite!',
                'success'
                )
            </script>
        @endif
        @if (session()->has('successDelete'))
            <script>
                Swal.fire(
                'Succès!',
                'Suppression Intervenant terminé avec succès!',
                'success'
                )
            </script>
        @endif
        @if (session()->has('errorDelete'))
            <script>
                Swal.fire(
                'Attention!',
                'Une erreur est survenue lors de la suppression de l intervenant,l intervenant qui a déjà fait un ticket ne peut pas supprimer!',
                'warning'
                )
            </script>
        @endif


    <div class="container">
        <div class="d-flex justify-content-between my-3">
            <h4 class="my-3 main-title">Liste des intervenants</h4>
            <button type="button" class="btn btn-primary my-3" data-toggle="modal" data-target="#myModalAjout"><i class="fas fa-plus-circle"></i> Ajouter Nouveau</button>
        </div>

        <div class="row stat-cards">
            @foreach ($intervenants as $interv)
            <div class="col-md-6 col-xl-3">
                <article class="stat-cards-item">
                        @if ($interv->photo_inter)
                            <img src="{{ asset('photos/' . $interv->photo_inter) }}" class="rounded-circle" width="200px" height="200px" alt="Photo de l'intervenant">
                        @endif
                        <div class="card-body">
                            <h5 class="stat-cards-info__num">{{ $interv->nom_inter }} {{ $interv->prenom_inter }}</h5>
                            <p class="stat-cards-info__title">{{ $interv->email_inter }}</p>
                        </div>
                        <p>
                            <button type="button" class="btn btn-outline-primary edit-btn" data-toggle="modal" data-target="#myModal-{{ $interv->id }}" data-id="{{ $interv->id }}" data-nom="{{ $interv->nom_inter }}" data-prenom="{{ $interv->prenom_inter }}" data-email="{{ $interv->email_inter }}"><i class="fas fa-edit"></i> Modifier</button>
                            <button onclick="supprimer({{$interv->id}})" class="btn btn-outline-danger"> <i class="fas fa-trash"></i> Supprimer</button>
                        </p>
                         </article>
            </div>
            @endforeach
        </div>
    </div>
    @foreach ($intervenants as $interv)
    <!-- Fenêtre modale spécifique à chaque intervenant -->
    <div class="modal fade" id="myModal-{{ $interv->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- En-tête de la fenêtre modale -->
                <div class="modal-header">
                    <h5 class="modal-title">Modification Intervenant</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!-- Contenu de la fenêtre modale -->
                <div class="modal-body">
                    <form action="/intervenant-update/{{ $interv->id }}" class="was-validated" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('put')
                        <div class="container">
                            <input type="hidden" class="edit-id" value="{{ $interv->id }}">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="profile-image-container">
                                            @if ($interv->photo_inter)
                                                <img src="{{ asset('photos/' . $interv->photo_inter) }}" id="preview-image-{{ $interv->id }}" class="profile-image" alt="Photo de profil">
                                            @else
                                                <img src="#" id="preview-image-{{ $interv->id }}" class="profile-image" alt="Photo de profil">
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-8">
                                        <label for="profile-picture-{{ $interv->id }}" class="btn btn-outline-primary">
                                            <i class="fas fa-cloud-download-alt"></i>
                                            <input type="file" id="profile-picture-{{ $interv->id }}" class="custom-file-input" accept=".jpg, .jpeg, .png" maxlength="8000000" name="photo_i">
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="edit-nom-{{ $interv->id }}">Nom intervenant:</label>
                                <input type="text" class="form-control edit-nom form-input" id="edit-nom-{{ $interv->id }}" placeholder="Saisir le nom de l'intervenant" name="nom_i" value="{{ $interv->nom_inter }}" required>
                            </div>
                            <div class="form-group">
                                <label for="edit-prenom-{{ $interv->id }}">Prénom intervenant:</label>
                                <input type="text" class="form-control edit-prenom form-input" id="edit-prenom-{{ $interv->id }}" placeholder="Saisir le prénom de l'intervenant" name="prenom_i" value="{{ $interv->prenom_inter }}" required>
                            </div>
                            <div class="form-group">
                                <label for="edit-email-{{ $interv->id }}">Email intervenant:</label>
                                <input type="email" class="form-control edit-email form-input" id="edit-email-{{ $interv->id }}" placeholder="Saisir l'email de l'intervenant" name="email_i" value="{{ $interv->email_inter }}" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                            <button type="submit" class="btn btn-primary save-btn">Enregistrer</button>
                        </div>
                    </form>
                </div>
                <!-- Pied de la fenêtre modale -->

            </div>
        </div>
    </div>
@endforeach

{{-- AJOUT --}}
<!-- Ajout du bouton pour ouvrir le modal d'ajout -->
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
                <form action="/intervenant-ajout" class="was-validated" method="POST" enctype="multipart/form-data">
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
                                        <i class="fas fa-cloud-download-alt"></i>
                                        <input type="file" id="profile-picture" class="custom-file-input" accept=".jpg, .jpeg, .png" maxlength="8000000" name="photo_i">
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
    $(document).ready(function() {
        // Écouter le clic sur le bouton "Edit"
        $('.edit-btn').click(function() {
            var id = $(this).data('id');
            var nom = $(this).data('nom');
            var prenom = $(this).data('prenom');
            var email = $(this).data('email');

            // Remplir les champs du modal avec les données de l'intervenant sélectionné
            $(this).closest('.modal').find('.edit-id').val(id);
            $(this).closest('.modal').find('.edit-nom').val(nom);
            $(this).closest('.modal').find('.edit-prenom').val(prenom);
            $(this).closest('.modal').find('.edit-email').val(email);
        });

        // Écouter le clic sur le bouton "Enregistrer"
        $('.save-btn').click(function() {
            var id = $(this).closest('.modal').find('.edit-id').val();
            var nom = $(this).closest('.modal').find('.edit-nom').val();
            var prenom = $(this).closest('.modal').find('.edit-prenom').val();
            var email = $(this).closest('.modal').find('.edit-email').val();

            // Envoyer les données à votre backend (par exemple, via une requête Ajax) pour effectuer la mise à jour

            // Fermer le modal
            $(this).closest('.modal').modal('hide');
        });
    });
</script>
<script>
    function supprimer(id) {
        Swal.fire({
            title: 'Êtes-vous sûr de vouloir supprimer?',
            text: "Vous ne pourrez pas revenir en arrière!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Oui, supprimez-le!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '/intervenant-delete/' + id;
            }
        })
    }
</script>


<script>
    $(document).ready(function() {
        // Prévisualiser l'image sélectionnée avant de l'uploader
        $('.custom-file-input').change(function() {
            const maxSize = 8000000; // Taille maximale en octets (8 Mo)
            const fileInput = $(this)[0];
            const file = fileInput.files[0];

            if (file) {
                // Vérifier la taille du fichier
                if (file.size > maxSize) {
                    // alert('Le fichier est trop volumineux. La taille maximale autorisée est de 8 Mo.');
                    Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: 'Le fichier est trop volumineux. La taille maximale autorisée est de 8 Mo.',
                });
                    // Réinitialiser le champ de téléchargement de fichier pour empêcher l'envoi
                    fileInput.value = '';
                    // Effacer l'aperçu de l'image
                    $(this).closest('.modal').find('.profile-image').attr('src', '#');
                } else {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        // Prévisualiser l'image
                        $(fileInput).closest('.modal').find('.profile-image').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(file);
                }
            }
        });
    });
</script>


@endsection
</body>
</html>
