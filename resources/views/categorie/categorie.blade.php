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
    <title>liste des categories</title>
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
    @if (session()->has('successAjout'))
            <script>
                Swal.fire(
                'Good job!',
                'Ajout Nouveau Catégorie Réussite!',
                'success'
                )
            </script>
        @endif
        @if (session()->has('successModif'))
            <script>
                Swal.fire(
                'Good job!',
                'Modification Action Réussite!',
                'success'
                )
            </script>
        @endif
        @if (session()->has('successDelete'))
            <script>
                Swal.fire(
                'Good job!',
                'Suppression Categorie terminé avec succè!',
                'success'
                )
            </script>
        @endif
        @if (session()->has('errorDelete'))
            <script>
                Swal.fire(
                'Erreur Survenu!',
                'cet categorie est insupprimable!',
                'error'
                )
            </script>
        @endif
        @if (session()->has('import'))
            <script>
                Swal.fire(
                    'Réussir!',
                    '{{ session('import') }}',
                    'success'
                )
            </script>
           @endif
           @if (session()->has('errorImport'))
            <script>
                Swal.fire(
                    "Echec d'importation de fichier!",
                    '{{ session('errorImport') }}',
                    'error'
                )
            </script>
           @endif
        <br>
        <div class="container">

            <div class="d-flex justify-content-between">
                <h2 class="my-3 main-title">Liste des Categories :</h2>
                <p>
                    <form action="{{ route('categorie.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="input-group">
                            <input type="file" class="form-input form-control my-3 mr-3" title="Choisir le fichier à importer dans le liste" id="excel_file" name="excel_file" accept=".xlsx, .xls" required>
                            <button type="submit" class="btn btn-success my-3">
                                <i class="fas fa-file-excel text-green"></i> Importer
                            </button>
                        </div>
                    </form>
                    <button type="button" class="btn btn-primary my-3" data-toggle="modal" data-target="#myModalAjout">
                        <i class="fas fa-plus-circle"></i> Ajouter Nouveau
                    </button>
                </p>
            </div>
            <div class="users-table">
                <table class="table table-striped table-bordered" id="ma-table">
                    <thead>
                      <tr class="users-table-info">
                          <th>#</th>
                          <th>code Categorie</th>
                          <th>Nom Categorie</th>
                          <th>Date d'Ajout</th>
                          <th>Date de modif</th>
                          <th>Evenements</th>
                      </tr>
                    </thead>
                    <tbody>
                                  @foreach ($categories as $categ)
                                      <tr>
                                          <td>{{$categ->id}}</td>
                                          <td>{{$categ->code_cat}}</td>
                                          <td>{{$categ->nom_cat}}</td>
                                          <td>{{$categ->created_at}}</td>
                                          <td>{{$categ->updated_at}}</td>
                                          <td>
                                              <button type="button" class="btn btn-outline-primary edit-btn" data-toggle="modal" data-target="#myModal-{{ $categ->id }}" data-id="{{ $categ->id }}" data-code="{{ $categ->code_cat}}" data-nom="{{ $categ->nom_cat}}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                              <button onclick="supprimer({{$categ->id}})" class="btn btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                          </td>
                                      </tr>
                                  @endforeach
                    </tbody>
                  </table>
            </div>
        @foreach ($categories as $categ)
                <!-- Fenêtre modale spécifique à chaque intervenant -->
                <div class="modal fade" id="myModal-{{ $categ->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <!-- En-tête de la fenêtre modale -->
                            <div class="modal-header">
                                <h5 class="modal-title">Modification Categorie</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <!-- Contenu de la fenêtre modale -->
                            <div class="modal-body">
                                <form action="/categorie-update/{{ $categ->id }}" class="was-validated" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('put')
                                    <div class="container">
                                        <input type="hidden" class="edit-id" value="{{ $categ->id }}">
                                        <div class="form-group">
                                            <label for="edit-nom-{{ $categ->id }}">Code Categorie:</label>
                                            <input type="text" class="form-control edit-nom form-input" id="edit-code-{{ $categ->id }}" placeholder="Saisir le code de la Categorie" name="code_cat" value="{{ $categ->code_cat }}" required>
                                            <div class="valid-feedback">Valide.</div>
                                            <div class="invalid-feedback">Veuillez saisir le code de la Categorie.</div>
                                        </div>
                                        <div class="form-group">
                                            <label for="edit-nom-{{ $categ->id }}">Nom Categorie:</label>
                                            <input type="text" class="form-control edit-nom form-input" id="edit-nom-{{ $categ->id }}" placeholder="Saisir le nom de l'acion" name="nom_cat" value="{{ $categ->nom_cat }}" required>
                                            <div class="valid-feedback">Valide.</div>
                                            <div class="invalid-feedback">Veuillez saisir le nom de la Categorie.</div>
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
            </div>

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
                            window.location.href = '/categorie-delete/' + id;
                        }
                    })
                }
            </script>

<script>
    $(document).ready(function() {
        // Écouter le clic sur le bouton "Edit"
        $('.edit-btn').click(function() {
            var id = $(this).data('id');
            var code = $(this).data('code');
            var nom = $(this).data('nom');

            // Remplir les champs du modal avec les données de l'intervenant sélectionné
            $(this).closest('.modal').find('.edit-id').val(id);
            $(this).closest('.modal').find('.edit-code').val(code);
            $(this).closest('.modal').find('.edit-nom').val(nom);
        });

        // Écouter le clic sur le bouton "Enregistrer"
        $('.save-btn').click(function() {
            var id = $(this).closest('.modal').find('.edit-id').val();
            var code = $(this).closest('.modal').find('.edit-code').val();
            var nom = $(this).closest('.modal').find('.edit-nom').val();
            // Envoyer les données à votre backend (par exemple, via une requête Ajax) pour effectuer la mise à jour

            // Fermer le modal
            $(this).closest('.modal').modal('hide');
        });
    });
</script>



{{-- AJOUT --}}
<!-- Ajout du bouton pour ouvrir le modal d'ajout -->


<!-- Modal d'ajout -->
<div class="modal fade" id="myModalAjout" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- En-tête de la fenêtre modale -->
            <div class="modal-header">
                <h5 class="modal-title">Ajout Nouvelle Categorie</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!-- Contenu de la fenêtre modale -->
            <div class="modal-body">
                <form action="/categorie-ajout" class="was-validated" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="container">
                        <div class="form-group">
                            <label for="code">Code Categorie:</label>
                            <input type="text" class="form-control form-input" id="code" placeholder="Saisir le code de la Categorie" name="code_cat" required>
                            <div class="valid-feedback">Valide.</div>
                            <div class="invalid-feedback">Veuillez saisir le code de la Categorie.</div>
                        </div>
                        <div class="form-group">
                            <label for="nom">Nom Categorie:</label>
                            <input type="text" class="form-control form-input" id="nom" placeholder="Saisir le nom de la Categorie" name="nom_cat" required>
                            <div class="valid-feedback">Valide.</div>
                            <div class="invalid-feedback">Veuillez saisir le nom de la Categorie.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- RECHERCHE --}}
<script>
    // Sélectionnez le champ de saisie et le tableau
    var input = document.getElementById("myInput");
    var table = document.getElementById("ma-table");

    // Ajoutez un écouteur d'événement sur le champ de saisie
    input.addEventListener("keyup", function() {
    // Récupérez la valeur saisie par l'utilisateur
    var filter = input.value.toUpperCase();

    // Parcourez toutes les lignes du tableau, en excluant la première ligne (l'entête)
    for (var i = 1; i < table.rows.length; i++) {
        var row = table.rows[i];

        // Parcourez toutes les colonnes de chaque ligne
        for (var j = 0; j < row.cells.length; j++) {
        var cell = row.cells[j];

        // Si la cellule contient la valeur saisie, affichez la ligne, sinon masquez-la
        if (cell.innerHTML.toUpperCase().indexOf(filter) > -1) {
            row.style.display = "";
            break;
        } else {
            row.style.display = "none";
        }
        }
    }
    });

</script>
@endsection
</body>
</html>
