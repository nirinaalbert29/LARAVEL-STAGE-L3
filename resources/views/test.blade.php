<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Test élément stage</title>
  <link rel="stylesheet" href="{{asset('bootstrap/css/bootstrap.min.css')}}">
  <link rel="stylesheet" href="{{asset('animate/animate.min.css')}}">
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="{{asset('bootstrap/js/bootstrap.min.js')}}"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.5/dist/sweetalert2.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.5/dist/sweetalert2.min.js"></script>

<!-- Inclure le CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

<!-- Inclure le JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>



  <!-- CSS de Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">

<!-- JS de Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

  <style>
    .card {
      display: none;
    }
  </style>
</head>
<body>
  <div class="container"><br>
    <div class="row">
      <div class="col-md-4">
        <select id="mySelect" class="form-control">
          <option value="">Sélectionnez un élément</option>
          <option value="1">Élément 1</option>
          <option value="2">Élément 2</option>
          <option value="3">Élément 3</option>
        </select>
      </div>
    </div>
    <br>
    <div class="row">
      <div id="card" class="col-md-4 card animate__animated">
        <div class="card-body">
          <h5 class="card-title">Détails de l'élément</h5>
          <p class="card-text">Les détails de l'élément sélectionné s'afficheront ici.</p>
        </div>
      </div>
    </div>
  </div>

  <script>
    $(document).ready(function() {
      // Masquer la carte au chargement de la page
      $("#card").hide();

      // Gestionnaire d'événements pour le changement de l'option sélectionnée
      $("#mySelect").change(function() {
        var selectedValue = $(this).val();

        if (selectedValue === "") {
          // Si l'option par défaut est sélectionnée, masquer la carte avec animation
          $("#card").removeClass("animate__backInDown").addClass("animate__backOutDown").hide();
        } else {
          // Afficher la carte avec animation
          $("#card").removeClass("animate__backOutDown").addClass("animate__backInDown").show();
          // Mettre à jour les informations de l'élément sélectionné dans le card
          var selectedText = $("#mySelect option:selected").text();
          $(".card-title").text("Détails de " + selectedText);
          $(".card-text").text("Les détails de " + selectedText + " s'afficheront ici.");
        }
      });
    });
  </script>



<hr><hr>
<div><hr>
    <select class="select2">
        <option value="1">Option 1</option>
        <option value="2">Option 2</option>
        <option value="3">Option 3</option>
        <!-- Aj
            outez ici plus d'options si nécessaire -->
      </select>
</div>

<script>
    $(document).ready(function() {
      $('.select2').select2();
    });
  </script>

</body>
</html>
