<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- Inclure les fichiers CSS -->
    <link rel="stylesheet" href="{{asset('bootstrap/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.5/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <!-- Inclure jsPDF, html2canvas et SweetAlert 2 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.3.2/html2canvas.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.5/dist/sweetalert2.min.js"></script>

    <!-- Inclure les fichiers JavaScript -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="{{asset('bootstrap/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('excellib/FileSaver.min.js')}}"></script>
    <script src="{{asset('excellib/xlsx.full.min.js')}}"></script>

    <!-- Votre propre code JavaScript -->

<!-- Inclure les fichiers jsPDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>

<!-- Inclure le fichier jsPDF Autotable que vous avez placé localement -->
<script src="{{asset('/jspdf.debug.js')}}"></script>
<script src="{{asset('/jspdf.plugin.autotable.js')}}"></script>
<link rel="shortcut icon" href="./logo_open.jpg" type="image/x-icon">


    <title>liste des actions</title>
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
                'Good job!',
                'Ajout Nouveau Action Réussite!',
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
                'Suppression Action terminé avec succès!',
                'success'
                )
            </script>
        @endif
        @if (session()->has('errorDelete'))
            <script>
                Swal.fire(
                'Erreur Survenu!',
                'cet Action est insupprimable!',
                'error'
                )
            </script>
        @endif
        <div class="container"> <br>
            <div class="container">
                <div class="d-flex justify-content-between">
                    <h2 class="my-3 main-title">
                        Liste des Actions :
                        <!--  incluant l'attribut "download" pour laisser le choix de l'emplacement d'enregistrement -->
                        <button id="export-btn" class="btn btn-success ri-file-excel-2-fill" download><i class="fas fa-file-excel"></i>Exporter en EXCEL</button>
                    </h2>
                    <button type="button" class="btn btn-primary my-3" data-toggle="modal" data-target="#myModalAjout"><i class="fas fa-plus-circle"></i> Ajouter Nouveau</button>
                </div>
        <div class="users-table" id="printdiv">
            <table class="table table-striped table-bordered" id="ma-table">
                <thead>
                  <tr class="users-table-info">
                      <th>#</th>
                      <th>Nom Action</th>
                      <th>Date d'Ajout</th>
                      <th>Evenements</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($actions as $action)
                                  <tr>
                                      <td>{{$action->id}}</td>
                                      <td>{{$action->nom_action}}</td>
                                      <td>{{$action->created_at}}</td>
                                      <td>
                                          <button type="button" class="btn btn-outline-primary edit-btn" data-toggle="modal" data-target="#myModal-{{ $action->id }}" data-id="{{ $action->id }}" data-nom="{{ $action->nom_action }}">
                                            <i class="fas fa-edit"></i>
                                          </button>
                                          <button onclick="supprimer({{$action->id}})" class="btn btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                          </button>
                                      </td>
                                  </tr>
                              @endforeach
                </tbody>
              </table>
        </div>
          </div>
        @foreach ($actions as $action)
                <!-- Fenêtre modale spécifique à chaque intervenant -->
                <div class="modal fade" id="myModal-{{ $action->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <!-- En-tête de la fenêtre modale -->
                            <div class="modal-header">
                                <h5 class="modal-title">Modification Action</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <!-- Contenu de la fenêtre modale -->
                            <div class="modal-body">
                                <form action="/action-update/{{ $action->id }}" class="was-validated" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('put')
                                    <div class="container">
                                        <input type="hidden" class="edit-id" value="{{ $action->id }}">
                                        <div class="form-group">
                                            <label for="edit-nom-{{ $action->id }}">Nom Action:</label>
                                            <input type="text" class="form-control edit-nom form-input" id="edit-nom-{{ $action->id }}" placeholder="Saisir le nom de l'acion" name="nom_action" value="{{ $action->nom_action }}" required>
                                            <div class="valid-feedback">Valide.</div>
                                            <div class="invalid-feedback">Veuillez saisir le nom de l'action.</div>
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
                            window.location.href = '/action-delete/' + id;
                        }
                    })
                }
            </script>

<script>
    $(document).ready(function() {
        // Écouter le clic sur le bouton "Edit"
        $('.edit-btn').click(function() {
            var id = $(this).data('id');
            var nom = $(this).data('nom');

            // Remplir les champs du modal avec les données de l'intervenant sélectionné
            $(this).closest('.modal').find('.edit-id').val(id);
            $(this).closest('.modal').find('.edit-nom').val(nom);
        });

        // Écouter le clic sur le bouton "Enregistrer"
        $('.save-btn').click(function() {
            var id = $(this).closest('.modal').find('.edit-id').val();
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
                <h5 class="modal-title">Ajout Nouvelle Action</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!-- Contenu de la fenêtre modale -->
            <div class="modal-body">
                <form action="/action-ajout" class="was-validated" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="container">
                        <div class="form-group">
                            <label for="nom">Nom Action:</label>
                            <input type="text" class="form-control form-input" id="nom" placeholder="Saisir le nom de la Action" name="nom_action" required>
                            <div class="valid-feedback">Valide.</div>
                            <div class="invalid-feedback">Veuillez saisir le nom de la Action.</div>
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

{{-- PRINT EXCEL --}}
<script type="text/javascript">
    $(document).ready(function() {
      $('#export-btn').click(function() {
        // Utiliser SweetAlert 2 pour saisir le nom du fichier
        Swal.fire({
          title: "Saisir le nom du fichier (sans l'extension) :",
          input: 'text',
          inputAttributes: {
            autocapitalize: 'off'
          },
          showCancelButton: true,
          confirmButtonText: 'OK',
          cancelButtonText: 'Annuler',
          showLoaderOnConfirm: true,
          preConfirm: (fileName) => {
            if (!fileName || fileName.trim() === "") {
              Swal.showValidationMessage("Le nom de fichier est requis !");
            } else {
              /* Export to Excel */
              var table = document.getElementById('ma-table');

              // Supprimer la dernière colonne de la table
              var rows = table.getElementsByTagName('tr');
              for (var i = 0; i < rows.length; i++) {
                var lastCell = rows[i].cells.length - 1;
                rows[i].deleteCell(lastCell);
              }

              var wb = XLSX.utils.table_to_book(table, {sheet:"Sheet 1"});
              var wbout = XLSX.write(wb, {bookType:'xlsx',  type: 'binary'});

              function s2ab(s) {
                var buf = new ArrayBuffer(s.length);
                var view = new Uint8Array(buf);
                for (var i=0; i < s.length; i++) view[i] = s.charCodeAt(i) & 0xFF;
                return buf;
              }

              // Utiliser FileSaver.js pour permettre à l'utilisateur de choisir le dossier de destination
              var file = new Blob([s2ab(wbout)], { type: "application/octet-stream" });
              saveAs(file, fileName + '.xlsx');
            }
          },
        }).then((result) => {
          if (result.isDismissed) {
            // L'utilisateur a cliqué sur "Annuler" ou fermé la boîte de dialogue
            // Mettez ici le code à exécuter en cas d'annulation
          }
        });
      });
    });
  </script>

{{-- PRINT PDF --}}
<!-- Votre script JavaScript -->
<script type="text/javascript">
    $(document).ready(function() {
      $('#export-btn-pdf').click(function() {
        // Utiliser SweetAlert 2 pour saisir le nom de fichier et choisir la destination
        Swal.fire({
          title: "Saisir le nom du fichier (sans l'extension) :",
          input: 'text',
          inputAttributes: {
            autocapitalize: 'off'
          },
          showCancelButton: true,
          confirmButtonText: 'Exporter',
          cancelButtonText: 'Annuler',
          showLoaderOnConfirm: true,
          preConfirm: (fileName) => {
            if (!fileName || fileName.trim() === "") {
              Swal.showValidationMessage("Le nom de fichier est requis !");
              return;
            }

            // Capturez le tableau en tant qu'image avec html2canvas
            html2canvas(document.getElementById('printdiv')).then(function(canvas) {
              var imgData = canvas.toDataURL('image/png');

              // Calculez les dimensions de la page PDF en fonction de l'image du tableau
              var imgWidth = 210; // Largeur de la page A4 (en mm)
              var pageHeight = imgWidth * canvas.height / canvas.width;

              // Générez un objet jsPDF
              var pdf = new jsPDF('p', 'mm', 'a4');

              // Ajoutez l'image du tableau au PDF
              pdf.addImage(imgData, 'PNG', 0, 0, imgWidth, pageHeight);

              // Demandez à l'utilisateur de choisir la destination
              pdf.save(fileName + '.pdf');
            });
          },
        });
      });
    });
  </script>

@endsection
</body>
</html>
