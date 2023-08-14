<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="{{asset('bootstrap/css/bootstrap.min.css')}}">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="{{asset('bootstrap/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('chart.min.js')}}"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <title>Menu Principale</title>
    <style>
         /* Ajoutez la règle pour continuer l'animation en boucle */
         .animate__pulse {
            animation-iteration-count: infinite;
        }
        .animate__headShake {
            animation-iteration-count: infinite;
        }
    </style>
</head>
<body>
    @if (isset($intervenant))
        @extends("dashboard.dashboard")
        @section("contenu")
            {{-- <h2 class="main-title">Menu Principale</h2> --}}
            <div class="container-fluide">
                <div class="row">
                    <div class="col-9">
                        <div class="stat-cards-item">

                        <i><h5 class="main-title">Statistique Pour le mois Actuel</h5></i>
                            <canvas id="Chart10" class="animate__animated animate__bounce"></canvas>
                            <script>
                                var ctx=document.getElementById("Chart10").getContext("2d");
                                var labels = [];
                                var data = [];

                                @foreach ($statistique as $stat)
                                    labels.push("{{$stat->nom}}");
                                    data.push({{$stat->nb_ok}});
                                @endforeach

                                var chart=new Chart(ctx,{
                                  type: "bar",
                                  data: {
                                    labels: labels,
                                    datasets: [{
                                      label: "Nombre ticket OK",
                                      backgroundColor: ["#0066ff", "#7070db", "#999966","#3399ff","#666633"],
                                      borderColor: [ "green","yellow", "blue", "purple","blue"],
                                      data: 0,data,
                                    }],
                                  },
                                  options: {}
                                })
                            </script>
                        </div>
                    </div>

                    <div class="col-3" >
                        <article class="stat-cards-item mb-3 animate__animated animate__pulse" style="height: 22%;">
                            <div class="stat-cards-icon success">
                                <i data-feather="check-circle" aria-hidden="true"></i>
                            </div>
                            <div class="stat-cards-info">
                                <p class="stat-cards-info__num">{{$ticketOK}}</p>
                                <p class="stat-cards-info__progress">
                                    <span class="stat-cards-info__profit success">
                                        <i data-feather="trending-up" aria-hidden="true"></i>Ticket OK
                                    </span>
                                </p>
                            </div>
                        </article>

                        <article class="stat-cards-item my-3 animate__animated animate__headShake" style="height: 22%;">
                            <div class="stat-cards-icon primary">
                                <i class="fas fa-times-circle text-danger"></i>
                            </div>
                            <div class="stat-cards-info">
                              <p class="stat-cards-info__num">{{$ticketBloq}}</p>
                              <p class="stat-cards-info__progress">
                                <span class="stat-cards-info__profit danger">
                                  <i data-feather="trending-down" aria-hidden="true"></i>Ticket Bloqué
                                </span>
                              </p>
                            </div>
                          </article>
                          <article class="stat-cards-item my-3 animate__animated animate__pulse" style="height: 22%;">
                            <div class="stat-cards-icon primary">
                              <i class="fas fa-spinner"></i>
                            </div>
                            <div class="stat-cards-info">
                              <p class="stat-cards-info__num">{{$ticketSuivr}}</p>
                              <p class="stat-cards-info__progress">
                                <span class="stat-cards-info__profit primary">
                                  <i class="fas fa-clock text-primary"></i>Ticket A Suivre
                                </span>
                              </p>
                            </div>
                          </article>
                          <article class="stat-cards-item my-3 animate__animated animate__headShake" style="height: 25%; border: 2px solid purple;">
                            <div class="stat-cards-icon purple">
                                <i class="fas fa-ban text-danger"></i>
                            </div>
                            <div class="stat-cards-info">
                              <p class="stat-cards-info__num">{{$ticketInacces}}</p>
                              <p class="stat-cards-info__progress">
                                <span class="stat-cards-info__profit danger">
                                    <i class="fas fa-lock"></i> Ticket Inaccessible
                                </span>
                              </p>
                            </div>
                          </article>

                      </div>
                </div>

            </div>
        @endsection
    @endif
</body>
</html>
