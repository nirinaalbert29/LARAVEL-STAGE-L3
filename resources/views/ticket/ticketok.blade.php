<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ticket OK</title>
    <link rel="stylesheet" href="{{asset('bootstrap/css/bootstrap.min.css')}}">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<!-- Inclure les scripts JavaScript de Bootstrap (popper.js et bootstrap.min.js) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="{{asset('bootstrap/js/bootstrap.min.js')}}"></script>


  <!-- Inclure le CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

<!-- Inclure le JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.5/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.5/dist/sweetalert2.min.js"></script>
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
    @if (isset($intervenant))
        @extends("dashboard.dashboard")
        @section("contenu")
            @if (session()->has('successAjout'))
                <script>
                    Swal.fire(
                    'Succès!',
                    'Ticket bien Enregistré avec succè!',
                    'success'
                    )
                </script>
            @endif
        @if (session()->has('successM'))
                <script>
                    Swal.fire(
                        'Succès!',
                        '{{ session('successM') }}',
                        'success'
                    )
                </script>
            @endif

            <div class="container-fluid mt-3">
                <div class="d-flex justify-content-between">
                    <h5 class="my-3 main-title">Liste des tickets OK :</h5>
                    <button type="button" class="btn btn-primary my-3" data-toggle="modal" data-target="#myModalAjout">
                        <i class="fas fa-plus-circle"></i> Ajouter Nouveau</button>
                </div>
                <div class="users-table">
                    <table class="table table-striped table-bordered" id="ma-table">
                        <thead>
                          <tr class="users-table-info">
                              <th>#</th>
                              <th>Date_Heure debut</th>
                              <th>Action</th>
                              <th>type projet</th>
                              <th>N° Ticket</th>
                              <th>Categorie</th>
                              <th>Pompe Funebre</th>
                              <th>Statut</th>
                              <th>Observation</th>
                              <th>Heure fin</th>
                              <th>Délai</th>
                              <th>Event</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach ($tickets->sortBy('num_tic') as $ticket)
                                          <tr>
                                              <td>{{$ticket->intervenants_id}}</td>
                                              <td>{{$ticket->created_at}}</td>
                                              <td>{{$ticket->nom_action}}</td>
                                              <td>{{$ticket->type_projet}}</td>
                                              <td>{{$ticket->num_tic}}</td>
                                              <td>{{$ticket->nom_cat}}</td>
                                              <td><a href="{{$ticket->lien_pompe}}" target="_blank" title="cliquer pour ouvrir dans un autre onglet" class="text-primary"><u>{{$ticket->nom_pompe}}</u></a></td>
                                              <td>{{$ticket->statut}}</td>
                                              <td>{{$ticket->observation}}</td>
                                              <td>{{ $ticket->dateHeure_fin}}</td>
                                              <td>{{$ticket->delai}}</td>

                                              <td>
                                                <button type="button" class="btn btn-outline-primary edit-btn" data-toggle="modal" data-target="#myModal-{{ $ticket->id }}" data-id="{{ $ticket->id }}">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                              </td>
                                          </tr>
                          @endforeach
                          @foreach ($pauses->sortBy('num_tic') as $ticket)
                                          <tr>
                                              <td>{{$ticket->intervenants_id}}</td>
                                              <td>{{$ticket->created_at}}</td>
                                              <td>{{$ticket->nom_action}}</td>
                                              <td>{{$ticket->type_projet}}</td>
                                              <td>{{$ticket->num_tic}}</td>
                                              <td></td>
                                              <td><a href="{{$ticket->lien_pompe}}" target="_blank" title="cliquer pour ouvrir dans un autre onglet" class="text-primary"><u>{{$ticket->nom_pompe}}</u></a></td>
                                              <td>{{$ticket->statut}}</td>
                                              <td>{{$ticket->observation}}</td>
                                              <td>{{$ticket->dateHeure_fin}}</td>
                                              <td>{{$ticket->delai}}</td>
                                              <td>
                                                <button type="button" class="btn btn-outline-primary view-btn" data-toggle="modal" data-target="#modalmlam-{{ $ticket->id }}"><i class="fas fa-eye"></i></button>
                                               </td>
                                          </tr>

                                        <!-- Modal pour voir le deuxième tr -->
                                        <div class="modal fade" id="modalmlam-{{ $ticket->id }}" tabindex="-1" role="dialog" aria-labelledby="modalmlamLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <!-- En-tête de la fenêtre modale -->
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalmlamLabel">Détails du ticket n° {{ $ticket->num_tic }}</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <!-- Contenu de la fenêtre modale -->
                                                    <div class="modal-body">
                                                        <div class="container">
                                                            <div class="form-group">
                                                                <!-- Affichez ici les détails du ticket -->
                                                                <!-- Utilisez les variables PHP pour remplir les détails du ticket -->
                                                                <b><label for="edit-np-{{ $ticket->id }}">-Action : {{ $ticket->nom_action }}</label></b><br>
                                                                <b><label for="edit-np-{{ $ticket->id }}">-Type projet : {{ $ticket->type_projet }}</label></b><br>
                                                                <b><label for="edit-np-{{ $ticket->id }}">-Nom Pompe : {{ $ticket->nom_pompe }}</label></b><br>
                                                                <b><label for="edit-lp-{{ $ticket->id }}">-Lien Pompe : <a href="{{ $ticket->nom_pompe }}">{{ $ticket->nom_pompe }}</a></label></b><br>
                                                                <b><label for="edit-stat-{{ $ticket->id }}">-Statut : {{ $ticket->statut }}</label></b><br>
                                                                <b><label for="edit-obs-{{ $ticket->id }}">-Observation : {{ $ticket->observation }}</label></b><br>
                                                                <b><label for="edit-cr-{{ $ticket->id }}">-Date-heure debut : {{ $ticket->created_at }}</label></b><br>
                                                                <b><label for="edit-fin-{{ $ticket->id }}">-heure fin : {{ $ticket->dateHeure_fin }}</label></b><br>
                                                                <b><label for="edit-del-{{ $ticket->id }}">-Delai : {{ $ticket->delai }}</label></b><br>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Pied de la fenêtre modale -->
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                          @endforeach
                        </tbody>
                      </table>
                </div>
            </div>

              @foreach ($tickets as $ticket)
                <!-- Fenêtre modale spécifique à chaque intervenant -->
                <div class="modal fade" id="myModal-{{ $ticket->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <!-- En-tête de la fenêtre modale -->
                            <div class="modal-header">
                                <h5 class="modal-title">Detail de ticket n° {{ $ticket->num_tic }}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <!-- Contenu de la fenêtre modale -->
                            <div class="modal-body">
                                <form action="/ticket-update/{{ $ticket->id }}" class="was-validated" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('put')
                                    <div class="container">
                                        <div class="form-group">
                                            <b><label for="edit-action-{{ $ticket->id }}">-Action : {{ $ticket->nom_action }}</label></b><br>
                                            <b><label for="edit-projet-{{ $ticket->id }}">-Type projet : {{ $ticket->type_projet }}</label></b><br>
                                            <b><label for="edit-cat-{{ $ticket->id }}">-Categorie : {{ $ticket->nom_cat }}</label></b><br>
                                            <b><label for="edit-np-{{ $ticket->id }}">-Nom Pompe : {{ $ticket->nom_pompe }}</label></b><br>
                                            <b><label for="edit-lp-{{ $ticket->id }}">-Lien Pompe : <a href="{{ $ticket->nom_pompe }}">{{ $ticket->nom_pompe }}</a></label></b><br>
                                            <b><label for="edit-stat-{{ $ticket->id }}">-Statut : {{ $ticket->statut }}</label></b><br>
                                            <b><label for="edit-obs-{{ $ticket->id }}">-Observation : {{ $ticket->observation }}</label></b><br>
                                            <b><label for="edit-cr-{{ $ticket->id }}">-Date-heure debut : {{ $ticket->created_at }}</label></b><br>
                                            <b><label for="edit-fin-{{ $ticket->id }}">-heure fin : {{ $ticket->dateHeure_fin }}</label></b><br>
                                            <b><label for="edit-del-{{ $ticket->id }}">-Delai : {{ $ticket->delai }}</label></b><br>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                                    </div>
                                </form>
                            </div>
                            <!-- Pied de la fenêtre modale -->

                        </div>
                    </div>
                </div>
                @endforeach


                <script>
                    $(document).ready(function() {
                        // Écouter le clic sur le bouton "Edit"
                        $('.edit-btn').click(function() {
                            var id = $(this).data('id');
                            var dat = $(this).data('dat');
                            // Remplir les champs du modal avec les données de l'intervenant sélectionné
                            $(this).closest('.modal').find('.edit-id').val(id);
                            $(this).closest('.modal').find('.edit-dat').val(dat);
                        });

                        // Écouter le clic sur le bouton "Enregistrer"
                        $('.save-btn').click(function() {
                            var id = $(this).closest('.modal').find('.edit-id').val();
                            var dat = $(this).closest('.modal').find('.edit-dat').val();
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
                <h5 class="modal-title">Ajout Nouveau Ticket</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!-- Contenu de la fenêtre modale -->
            <div class="modal-body">
                <form action="/ticket" method="POST">
                    @csrf
                    <div class="form-group">
                      <label for="email">Action:</label>
                      @foreach ($intervenant as $inter)
                        <input type="hidden"  value="{{$inter->id}}" name="id_inter">
                      @endforeach
                      <select name="action" id="" class="form-control form-input" required>
                        <option value="">Veuillez Sélectionnez l'Action</option>
                        @foreach ($action as $action)
                            <option value="{{$action->id}}" class="form-control form-input">{{$action->nom_action}}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="pwd">Type Projet:</label>
                      <select name="type_projet" id="" class="form-control form-input">
                        <option value="">Veuillez Sélectionnez le type Projet</option>
                            <option value="EXT SUPPORT" class="form-control form-input">EXT SUPPORT</option>
                            <option value="EXT LOT N°1" class="form-control form-input">EXT LOT N°1</option>
                            <option value="EXT LOT N°2" class="form-control form-input">EXT LOT N°2</option>
                            <option value="TÂCHES" class="form-control form-input">TÂCHES</option>
                      </select>
                      </div>
                    <div class="form-group">
                        <label for="pwd">Categorie:</label>
                        <select name="categ" id="" class="form-control form-input select2" style="width: 100%">
                            <option value="">Veuillez Sélectionnez le Categorie</option>
                            @foreach ($categorie as $categ)
                                <option value="{{$categ->id}}" class="form-control form-input">{{$categ->nom_cat}}</option>
                            @endforeach
                          </select>
                    </div>
                    <div class="form-group">
                        <label for="pwd">Nom Pompe Funebre:</label>
                        <input type="text" name="nom_pompe" id="" class="form-control form-input" placeholder="Saisir nom pompe funèbre">
                    </div>
                    <div class="form-group">
                        <label for="pwd">Lien Pompe Funebre:</label>
                        <input type="text" name="lien_pompe" id="" class="form-control form-input" placeholder="Saisir lien pompe funèbre">
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
<script>
    $(document).ready(function() {
      $('.select2').select2();
    });
  </script>

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
    @endif
</body>
</html>
