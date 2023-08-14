<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Mot de passe Oublie</title>
    <link rel="stylesheet" href="{{asset('bootstrap/css/bootstrap.min.css')}}">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="{{asset('bootstrap/js/bootstrap.min.js')}}"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.5/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.5/dist/sweetalert2.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="shortcut icon" href="./logo_open.jpg" type="image/x-icon">
<!-- Custom styles -->
<link rel="stylesheet" href="./css/style.min.css">
<style>
    body {
            background: linear-gradient(100deg, #34204e, #880260, #1e1d2c);
        }
</style>
</head>
<body>
    @if (session()->has('erroremail'))
            <script>
                Swal.fire(
                'Erreur!',
                '{{ session('erroremail') }}',
                'error'
                )
            </script>
        @endif
        <div class="layer"></div>
<main class="page-center">
    <div class="row">
        <div class="col-4">
        <img src="/mot-passe-oublie.avif" alt="" style="width: 100%">
        </div>
        <div class="col-6">
            <article class="sign-up">

                <form class="sign-up-form form" action="/mdpoublie" method="POST">
                    @csrf
                    <div class="d-flex justify-content-center">
                        <img src="/logo_open.jpg" class="rounded-circle"
                                alt="logo" srcset="" width="100">
                    </div>

                    <div class="form-group">
                        <label for="password">Saisir Votre Email:</label>
                        <div class="input-group">
                            <input type="email" class="form-control form-input" placeholder="Saisir mot email..." name="mail" required>
                        </div>
                    </div>
                <button class="form-btn primary-default-btn transparent-btn">Valider</button><br>
                <a href="/"> <p> << Retour au Login.. </p></a>
                </form>
            </article>
        </div>
    </div>
</main>
</body>
</html>
