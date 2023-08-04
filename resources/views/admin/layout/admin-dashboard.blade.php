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

</head>
<body>
  <div class="layer"></div>
<!-- ! Body -->
<a class="skip-link sr-only" href="#skip-target">Skip to content</a>
<div class="page-flex">
  <!-- ! Sidebar -->
  <aside class="sidebar">
    <div class="sidebar-start">
        <div class="sidebar-head">
            <a href="#" class="logo-wrapper" title="Home">
                <span class="sr-only">ADMIN</span>
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
                    <a class="active" href="/principale-admin"><span class="icon home" aria-hidden="true"></span>Admin-Home</a>
                </li>
                <li>
                    <li>
                        <a class="inactive" href="/intervenants"><span class="icon fas fa-users" aria-hidden="true"></span>Intevenant</a>
                    </li>
                </li>
                <li>
                    <a class="inactive" href="/categorie"><span class="icon fas fa-folder" aria-hidden="true"></span>Categories</a>
                </li>
                <li>
                    <a class="inactive" href="/action-liste"><span class="icon fas fa-cogs" aria-hidden="true"></span>Action</a>
                </li>
            </ul>
            <span class="system-menu__title">Autres</span>
            <ul class="sidebar-body-menu">
                <li>
                    <a class="show-cat-btn" href="##">
                        <span class="icon fas fa-chart-line" aria-hidden="true"></span>Stat Personnel
                        <span class="category__btn transparent-btn" title="Open list">
                            <span class="sr-only">ouvrir liste</span>
                            <span class="icon arrow-down" aria-hidden="true"></span>
                        </span>
                    </a>
                    <ul class="cat-sub-menu">
                        <li>
                            <a href="statmensuel-admin">Statistique Mensuel</a>
                        </li>
                        <li>
                            <a href="stathebdo-admin">Stat Hebdomadaire</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a class="show-cat-btn" href="##">
                        <span class="icon fas fa-chart-pie" aria-hidden="true"></span>Statistique Global
                        <span class="category__btn transparent-btn" title="Open list">
                            <span class="sr-only">Open list</span>
                            <span class="icon arrow-down" aria-hidden="true"></span>
                        </span>
                    </a>
                    <ul class="cat-sub-menu">
                        <li>
                            <a href="/statglobalmensuel">Statistique Mensuel</a>
                        </li>
                        <li>
                            <a href="/statglobalhebdo">Stat Hebdomadaire</a>
                        </li>
                        <li>
                            <a href="/statglobalAjourd">Stat Semaine dernier</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="/export"><span class="icon fas fa-file-excel" aria-hidden="true"></span>  Exportation Ticket</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="sidebar-footer">
        <a href="#" class="sidebar-user">
            <span class="sidebar-user-img">
                    <picture><source srcset="{{ asset('/logo_open.jpg') }}" type="image/webp"><img src="{{ asset('/logo_open.jpg') }}" alt="User name"></picture>
                </span>
            <div class="sidebar-user-info">
                <span class="sidebar-user__title">Open Data.</span>
            </div>
            <span class="sidebar-user__subtitle">opendata@gmail.com</span>
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
        <button class="theme-switcher gray-circle-btn" type="button" title="Changer théme">
            <span class="sr-only">Change theme</span>
            <i class="sun-icon" data-feather="sun" aria-hidden="true"></i>
            <i class="moon-icon" data-feather="moon" aria-hidden="true"></i>
        </button>
      <div class="nav-user-wrapper">
            <button href="##" class="nav-user-btn dropdown-btn" title="My profile" type="button">
            <span class="sr-only">My profile</span>
            <span class="nav-user-img">
                <picture><source srcset="{{ asset('/logo_open.jpg') }}" type="image/webp"><img src="{{ asset('/logo_open.jpg') }}" alt="User name"></picture>
            </span>
            </button>
        <ul class="users-item-dropdown nav-user-dropdown dropdown">
          <li><a class="danger" href="/login-admin">
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
<!-- Chart library -->
<script src="./plugins/chart.min.js"></script>
<!-- Icons library -->
<script src="plugins/feather.min.js"></script>
<!-- Custom scripts -->
<script src="js/script.js"></script>
</body>

</html>
