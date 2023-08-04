<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>export ticket</title>
    <link rel="stylesheet" href="{{asset('bootstrap/css/bootstrap.min.css')}}">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="{{asset('bootstrap/js/bootstrap.min.js')}}"></script>


  <!-- Inclure le CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

<!-- Inclure le JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.5/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.5/dist/sweetalert2.min.js"></script>
</head>
<body>
        @extends("admin.layout.admin-dashboard")
        @section("contenu")
        @if (session()->has('vide'))
        <script>
            Swal.fire(
                'Aucune Données!',
                '{{ session('vide') }}',
                'warning'
            )
        </script>
       @endif
        <div class="container">
            <div class="container my-3 main-nav--bg"><br>
                <h3 class="main-title">Exportation de Ticket :</h3>
                <form action="/export" method="POST">
                    @csrf
                    <label for="month" class="stat-cards-info__title">Choisir un intervenant :</label>
                    <select name="inter" class="form-input">
                        @foreach ($intervenant as $inter)
                            <option value="{{$inter->id}}">{{$inter->nom_inter}}</option>
                        @endforeach
                        <!-- Ajoutez les options pour les autres mois -->
                    </select>
                    <label for="month" class="stat-cards-info__title">Choisir un mois :</label>
                    <select name="month" id="month" class="form-input">
                        <option value="1">Janvier</option>
                        <option value="2">Février</option>
                        <option value="3">Mars</option>
                        <option value="4">Avril</option>
                        <option value="5">Mai</option>
                        <option value="6">Juin</option>
                        <option value="7">Juillet</option>
                        <option value="8">Août</option>
                        <option value="9">Septembre</option>
                        <option value="10">Octobre</option>
                        <option value="11">Novembre</option>
                        <option value="12">Decembre</option>
                        <!-- Ajoutez les options pour les autres mois -->
                    </select>

                    <label for="year" class="stat-cards-info__title">Saisir une année :</label>
                    <input type="number" name="year" class="form-input" id="" placeholder="saisir année,ex:2023" required>
                    <button type="submit" class="btn btn-outline-primary">Afficher</button>
                </form>
            </div>
            <div>
                @if(isset($nb_ticket_tot))
                <div class="container-fluid mt-3">
                    <div class="d-flex justify-content-between">
                        <h5 class="my-3 main-title">Liste des Tickets de <b>{{$nom_inter}}</b> pour le Mois de ({{$startOfMonth}}) :
                             <button id="export-btn" class="btn btn-success ri-file-excel-2-fill" download><i class="fas fa-file-excel"></i>Exporter en EXCEL</button>
                        </h5>
                        <i><h6 class="my-3 main-title" style="font-family: serif"><u>
                            Il y a : {{$nb_ticket_tot}} tickets
                        </u></h6></i>
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
                @endif
            </div>
        </div>
        @endsection
</body>
</html>
