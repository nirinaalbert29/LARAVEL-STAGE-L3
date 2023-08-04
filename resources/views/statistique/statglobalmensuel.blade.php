<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Stat Global Mensuel</title>
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
            <div class="container my-3 main-nav--bg stat-cards-item">
                <h3 class="main-title">Statistique Mensuel Pours tous les Intervenants:</h3>
                <form action="/statglobalmensuel" method="POST">
                    @csrf
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
                @if(isset($nb_ticketTotal))
                <h5 class="my-3 main-title">Statistique du Mois de ({{$startOfMonth}})</h5>
                <div class="row stat-cards">
                    <div class="col-md-6 col-xl-3">
                        <article class="stat-cards-item">
                          <div class="stat-cards-icon success">
                            <i data-feather="check-circle" aria-hidden="true"></i>
                          </div>
                          <div class="stat-cards-info">
                            <p class="stat-cards-info__num">TICKET OK : {{$nb_ticketOK}}</p>
                            <p class="stat-cards-info__title">Délai Total : {{$delai_totalok}}</p>
                            <p class="stat-cards-info__progress">
                              <span class="stat-cards-info__profit success">
                                <i data-feather="trending-up" aria-hidden="true"></i>{{$pourcOK}}% / des tickets
                              </span>
                            </p>
                          </div>
                        </article>
                    </div>
                  <div class="col-md-6 col-xl-3">
                    <article class="stat-cards-item">
                      <div class="stat-cards-icon purple">
                        <i class="fas fa-times-circle text-danger"></i>
                      </div>
                      <div class="stat-cards-info">
                        <p class="stat-cards-info__num">TICKET BLOQUE : {{$nb_bloque}}</p>
                        <p class="stat-cards-info__title">Délai Total : {{$delai_bloque}}</p>
                        <p class="stat-cards-info__progress">
                          <span class="stat-cards-info__profit danger">
                            <i data-feather="trending-down" aria-hidden="true"></i>{{$pourcBloque}}%
                          </span>
                          Fin de mois
                        </p>
                      </div>
                    </article>
                  </div>
                  <div class="col-md-6 col-xl-3">
                    <article class="stat-cards-item">
                      <div class="stat-cards-icon purple">
                        <i class="fas fa-pause"></i>
                      </div>
                      <div class="stat-cards-info">
                        <p class="stat-cards-info__num">PAUSE : {{$nb_pause}}</p>
                        <p class="stat-cards-info__title">Délai Total : {{$delai_pause}}</p>
                        <p class="stat-cards-info__progress">
                          <span class="stat-cards-info__profit danger">
                            <i data-feather="trending-down" aria-hidden="true"></i>{{$pourcPause}}%
                          </span>
                        </p>
                      </div>
                    </article>
                  </div>
                  <div class="col-md-6 col-xl-3">
                    <article class="stat-cards-item">
                      <div class="stat-cards-icon purple">
                        <i class="fas fa-industry"></i>
                      </div>
                      <div class="stat-cards-info">
                        <p class="stat-cards-info__num">PRODUCTION : {{$nb_prod}}</p>
                        <p class="stat-cards-info__title">Délai Total : {{$delai_prod}}</p>
                        <p class="stat-cards-info__progress">
                          <span class="stat-cards-info__profit danger">
                            <i data-feather="trending-down" aria-hidden="true"></i>{{$pourcprod}}%
                          </span>
                        </p>
                      </div>
                    </article>
                  </div>

                </div>
                @endif
            </div>


            @if(isset($nb_ticketOK_tri))
            <h1 class="main-title my-3">Détail des tickets OK /Intervenant:</h1>
                <div class="row stat-cards">
                @foreach ($nb_ticketOK_tri as $tic)
                <div class="col-md-6 col-xl-3">
                  <article class="stat-cards-item">
                    <div class="stat-cards-icon success">
                      <i data-feather="check-circle" aria-hidden="true"></i>
                    </div>
                    <div class="stat-cards-info">
                      <p class="stat-cards-info__num">{{$tic->nom_i}}</p>
                      <p class="stat-cards-info__title">Nombre Ticket(s) OK</p>
                      <p class="stat-cards-info__progress">
                        <span class="stat-cards-info__profit success">
                          <i data-feather="trending-up" aria-hidden="true"></i>{{$tic->nb_oktri}}
                        </span>
                      </p>
                    </div>
                  </article>
                </div>
                @endforeach
                </div>
            @endif
        </div>
        @endsection
</body>
</html>
