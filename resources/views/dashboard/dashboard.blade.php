<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>OpenData Dashboard | Dashboard</title>
  <!-- Favicon -->
  <link rel="shortcut icon" href="./logo_open.jpg" type="image/x-icon">
  <!-- Custom styles -->
  <link rel="stylesheet" href="./css/style.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" />

  <script src="{{asset('excellib/FileSaver.min.js')}}"></script>
  <script src="{{asset('excellib/xlsx.full.min.js')}}"></script>
  <!-- Inclure le CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

<!-- Inclure le JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>


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

<script>
    // Fonction pour trier les données dans la colonne "Numero"
    function sortTable() {
      const table = document.getElementById("ma-table");
      const rows = table.rows;
      const data = [];

      // Récupérer les données de la colonne "Numero" dans un tableau
      for (let i = 1; i < rows.length; i++) {
        data.push(parseInt(rows[i].cells[4].innerText));
      }

      // Trier le tableau de données en ordre croissant
      data.sort((a, b) => a - b);

      // Mettre à jour les valeurs dans la colonne "Numero"
      for (let i = 1; i < rows.length; i++) {
        rows[i].cells[4].innerText = data[i - 1];
      }
    }

    // Appeler la fonction de tri lorsque la page est chargée
    window.addEventListener("load", sortTable);
  </script>


<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.5/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.5/dist/sweetalert2.min.js"></script>

</head>
<body>
    @if (session()->has('errorFile'))
        <script>
            Swal.fire(
                'Erreur !',
                '{{ session('errorFile') }}',
                'error'
            )
        </script>
        @endif

        @if (session()->has('success'))
            <script>
                Swal.fire(
                'Succès!',
                '{{ session('success') }}',
                'success'
                )
            </script>
        @endif
  <div class="layer"></div>
<!-- ! Body -->
<a class="skip-link sr-only" href="#skip-target">Skip to content</a>
<div class="page-flex">
  <!-- ! Sidebar -->
  <aside class="sidebar">
    <div class="sidebar-start">
        <div class="sidebar-head">
            <a href="#" class="logo-wrapper" title="Home">
                <span class="sr-only">Home</span>
                <span class="icon" aria-hidden="true"><img src="/logo_open.jpg" class="rounded-circle"
                    alt="logo" srcset="" width="60"></span>
                <div class="logo-text">
                    <span class="logo-title"> Open</span>
                    <span class="logo-subtitle">DataMada</span>
                </div>
            </a>
            <button class="sidebar-toggle transparent-btn" title="Menu" type="button">
                <span class="sr-only">Cacher/Afficher menu</span>
                <span class="icon menu-toggle" aria-hidden="true"></span>
            </button>
        </div>
        <div class="sidebar-body">
            <ul class="sidebar-body-menu">
                <li>
                    <a class="active" href="/principale"><span class="icon home" aria-hidden="true"></span>Intervenant-Home</a>
                </li>
                <li>
                    <a class="inactive" href="/ticketglobal"><span class="icon fas fa-users" aria-hidden="true"></span>Ticket Global</a>
                </li>
                <li>
                    <a class="inactive" href="/ticketok"><span class="icon fas fa-folder" aria-hidden="true"></span>Ticket OK</a>
                </li>
                <li>
                    <a class="inactive" href="/ticket"><span class="icon fas fa-cogs" aria-hidden="true"></span>Ticket en Cours</a>
                </li>
                <li>
                    <a class="inactive" href="/ticketsuivre"><span class="icon fas fa-cogs" aria-hidden="true"></span>Ticket Suivre/Bloqué</a>
                </li>
                <li>
                    <a class="inactive" href="/ticketInaccessible"><span class="icon fas fa-cogs" aria-hidden="true"></span>Ticket Inaccessible</a>
                </li>
            </ul>
            <span class="system-menu__title">Autres</span>
            <ul class="sidebar-body-menu">
                <li>
                    <a href="/statmensuel"><span class="icon fas fa-chart-line" aria-hidden="true"></span>  Statistique Mensuel</a>
                </li>
                <li>
                    <a href="/stathebdo"><span class="icon fas fa-chart-bar" aria-hidden="true"></span>  Stat Hebdomadaire</a>
                </li>
                <li>
                    <a href="/stathebdoAjourd"><span class="icon fas fa-chart-pie" aria-hidden="true"></span>  Stat Semaine dernier</a>
                </li>
            </ul>
        </div>
    </div>
    @if (isset($intervenant))
    @foreach ($intervenant as $intervenant)
    <div class="sidebar-footer">
        <a href="#" class="sidebar-user">
            <span class="sidebar-user-img">
                @if ($intervenant->photo_inter)
                    <picture><source srcset="{{ asset('photos/' . $intervenant->photo_inter) }}" type="image/webp"><img src="{{ asset('photos/' . $intervenant->photo_inter) }}" alt="User name"></picture>
                @endif
                </span>
            <div class="sidebar-user-info">
                <span class="sidebar-user__title">{{ $intervenant->prenom_inter }}.</span>
            </div>
            <span class="sidebar-user__subtitle">{{ $intervenant->email_inter }}</span>
        </a>
    </div>
</aside>
  <div class="main-wrapper">
    <!-- ! Main nav -->
    <nav class="main-nav--bg">
  <div class="container main-nav">
    <div class="main-nav-start">
      <div class="search-wrapper">
        <i data-feather="search" aria-hidden="true"></i>
        <input type="text" placeholder="Rechercher par ici ..." required id="myInput">
      </div>
    </div>
    <div class="main-nav-end">
      <button class="sidebar-toggle transparent-btn" title="Menu" type="button">
        <span class="sr-only">Toggle menu</span>
        <span class="icon menu-toggle--gray" aria-hidden="true"></span>
      </button>
      {{-- <div class="lang-switcher-wrapper">
        <button class="lang-switcher transparent-btn" type="button">
          EN-FR
          <i data-feather="chevron-down" aria-hidden="true"></i>
        </button>
        <ul class="lang-menu dropdown">
          <li><a href="##">English</a></li>
          <li><a href="##">French</a></li>
        </ul>
    </div> --}}
        <button class="theme-switcher gray-circle-btn" type="button" title="Changer théme">
            <span class="sr-only">Change theme</span>
            <i class="sun-icon" data-feather="sun" aria-hidden="true"></i>
            <i class="moon-icon" data-feather="moon" aria-hidden="true"></i>
        </button>
      <div class="nav-user-wrapper">
            <button href="##" class="nav-user-btn dropdown-btn" title="My profile" type="button">
            <span class="sr-only">Mon profile</span>
            <span class="nav-user-img">
                @if ($intervenant->photo_inter)
                    <picture><source srcset="{{ asset('photos/' . $intervenant->photo_inter) }}" type="image/webp"><img src="{{ asset('photos/' . $intervenant->photo_inter) }}" alt="User name"></picture>
                @endif
            </span>
            </button>
        @endforeach
        @endif
        <ul class="users-item-dropdown nav-user-dropdown dropdown">
          <li><a href="#">
              <i data-feather="settings" aria-hidden="true"></i>
              <span data-toggle="modal" data-target="#myModalAjout1">Modifier profil</span>
            </a></li>
            <li><a href="/change-mdp-inter">
                <i data-feather="settings" aria-hidden="true"></i>
                <span>change mdp</span>
              </a></li>
          <li><a class="danger" href="/">
              <i data-feather="log-out" aria-hidden="true"></i>
              <span>Déconnecter</span>
            </a></li>
        </ul>
      </div>
    </div>
  </div>
</nav>
    <!-- ! Main -->
    <main class="main users chart-page" id="skip-target">
      <div class="container">
        @yield("contenu")
      </div>
    </main>
    <!-- ! Footer -->
    <footer class="footer">
        <div class="container footer--flex fixed">
            <div class="footer-start">
            <p>2023 © Gestion de suivie - <a href="#" target="_blank"
                rel="noopener noreferrer">nirinaalexalbert29@gmail.com</a></p>
            </div>
            <ul class="footer-end">
                <li><a href="##">About</a></li>
                <li><a href="##">Support</a></li>
                <li><a href="##">Puchase</a></li>
            </ul>
        </div>
    </footer>
  </div>
</div>



<div class="modal fade" id="myModalAjout1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- En-tête de la fenêtre modale -->
            <div class="modal-header"><br>
                <h5 class="modal-title">Profil de l'intervenant</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!-- Contenu de la fenêtre modale -->
            <div class="modal-body">
                <form action="/intervenant-update-compte/{{ $intervenant->id }}" class="was-validated" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div class="container">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-4">
                                    <div class="profile-image-container">
                                        @if ($intervenant->photo_inter)
                                            <img src="{{ asset('photos/' . $intervenant->photo_inter) }}" id="preview-image-{{ $intervenant->id }}" class="profile-image" alt="Photo de profil">
                                        @else
                                            <img src="#" id="preview-image-{{ $intervenant->id }}" class="profile-image" alt="Photo de profil">
                                        @endif
                                </div>
                                <div class="col-4">
                                    <label for="profile-picture" class="btn btn-outline-primary">
                                        <i class="fas fa-cloud-download-alt"></i>
                                        <input type="file" id="profile-picture" class="custom-file-input" accept=".jpg, .jpeg, .png" maxlength="8000000" value="{{$intervenant->photo_inter}}" name="photo_i">
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="nom">Nom intervenant:</label>
                            <input type="text" class="form-control form-input" placeholder="Saisir le nom de l'intervenant" value="{{$intervenant->nom_inter}}" name="nom_i" required>
                        </div>
                        <div class="form-group">
                            <label for="prenom">Prénom intervenant:</label>
                            <input type="text" class="form-control form-input" placeholder="Saisir le prénom de l'intervenant" value="{{$intervenant->prenom_inter}}" name="prenom_i" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email intervenant:</label>
                            <input type="email" class="form-control form-input" placeholder="Saisir l'email de l'intervenant" value="{{$intervenant->email_inter}}" name="email_i" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Retour</button>
                        <button type="submit" class="btn btn-primary save-btn">Modifier</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

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

<!-- Chart library -->
<script src="./plugins/chart.min.js"></script>
<!-- Icons library -->
<script src="plugins/feather.min.js"></script>
<!-- Custom scripts -->
<script src="js/script.js"></script>
</body>

</html>
