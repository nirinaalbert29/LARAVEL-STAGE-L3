<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Statistique global hebdomadaire</title>
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
            <div class="container">
                @if (isset($nb_ticket))
                <h5 class="my-3 main-title">Statistique pour tous les intervenants du Semaine dernier ({{$startOfMonth}} à {{$endOfMonth}}) :</h5>
                <div class="row stat-cards">
                <div class="col-md-6 col-xl-3">
                    <article class="stat-cards-item">
                      <div class="stat-cards-icon success">
                        <i data-feather="check-circle" aria-hidden="true"></i>
                      </div>
                      <div class="stat-cards-info">
                        <p class="stat-cards-info__num">TICKET OK : {{$nb_ticket}}</p>
                        <p class="stat-cards-info__title">Délai Total:{{$totalSumDelai}}</p>
                        <p class="stat-cards-info__progress">
                          <span class="stat-cards-info__profit success">
                            <i data-feather="trending-up" aria-hidden="true"></i>{{$pourcOK}} / des tickets
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
                        </p>
                      </div>
                    </article>
                  </div>
                  <div class="col-md-6 col-xl-3">
                    <article class="stat-cards-item">
                      <div class="stat-cards-icon success">
                        <i class="fas fa-spinner"></i>
                      </div>
                      <div class="stat-cards-info">
                        <p class="stat-cards-info__num">TICKET A Suivre : {{$nb_suivre}}</p>
                        <p class="stat-cards-info__title">Délai Total : {{$delai_suivre}}</p>
                        <p class="stat-cards-info__progress">
                          <span class="stat-cards-info__profit warning">
                            <i data-feather="trending-up" aria-hidden="true"></i>{{$pourcSuivre}}%
                          </span>
                        </p>
                      </div>
                    </article>
                  </div>
                  <div class="col-md-6 col-xl-3">
                    <article class="stat-cards-item">
                      <div class="stat-cards-icon purple">
                        <i class="fas fa-ban text-danger"></i>
                      </div>
                      <div class="stat-cards-info">
                        <p class="stat-cards-info__num">TICKET INACCESSIBLE : {{$nb_inaccessible}}</p>
                        <p class="stat-cards-info__title">Délai Total : {{$delai_inaccessible}}</p>
                        <p class="stat-cards-info__progress">
                          <span class="stat-cards-info__profit danger">
                            <i data-feather="trending-down" aria-hidden="true"></i>{{$pourcinaccessible}}%
                          </span>
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
                        <i data-feather="file" aria-hidden="true"></i>
                      </div>
                      <div class="stat-cards-info">
                        <p class="stat-cards-info__num">INTERFICHIER : {{$sumInterfileTimes}}</p>
                        <p class="stat-cards-info__progress">
                          <span class="stat-cards-info__profit danger">
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
                        <p class="stat-cards-info__num">PRODUCTION OK: {{$nb_prod}}</p>
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


            <h1 class="main-title my-3">Détail des tickets OK /Intervenants:</h1>
            @if(isset($nb_ticketOK_tri))
                <div class="row stat-cards">
                @foreach ($nb_ticketOK_tri as $tic)
                <div class="col-md-6 col-xl-3">
                  <article class="stat-cards-item">
                    <div class="stat-cards-icon success">
                      <i data-feather="check-circle" aria-hidden="true"></i>
                    </div>
                    <div class="stat-cards-info">
                      <p class="stat-cards-info__num">{{$tic->nom_i}}</p>
                      <p class="stat-cards-info__title">Ticket OK : </p>
                      <p class="stat-cards-info__progress">
                        <span class="stat-cards-info__profit success">
                          <i data-feather="trending-up" aria-hidden="true"></i>{{$tic->nb_oktri}} Ticket(s) OK
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
