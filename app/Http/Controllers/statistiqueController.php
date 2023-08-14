<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use DateInterval;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class statistiqueController extends Controller
{
    public function index(){
        $intervenant = session('intervenant');
        $id=$intervenant->first()->id;
        return view('statistique.statmensuel',['intervenant' => $intervenant,]);
    }

    public function stat(Request $request){
            $intervenant = session('intervenant');
            $id=$intervenant->first()->id;
            $month = (int)$request->month;
            $year = (int)$request->year;

            $startOfMonth = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endOfMonth = Carbon::createFromDate($year, $month, 1)->endOfMonth();

            // Récupérer le nombre de tickets avec un statut commençant par "OK" pour le mois specifie
        $nb_ticket = Ticket::where('statut', 'LIKE', 'OK%')
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
        ->where('intervenants_id',$id)
        ->count();

        $ticketOkEn = Ticket::where('statut', 'like', 'OK(En%')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->where('intervenants_id', $id)
            ->get();

        $nb_tot=Ticket::where('intervenants_id', $id)
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
        ->where('statut','!=','A Continué')
        ->count();

        $delai_somme = 0;

        foreach ($ticketOkEn as $tic) {
            $delai_somme += (int) Ticket::where('num_tic', $tic->num_tic)
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->where('intervenants_id', $id)
                ->sum(DB::raw('TIME_TO_SEC(delai)'));
        }

        $delai_sommeok = (int) Ticket::where('statut', 'OK')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->where('intervenants_id', $id)
            ->sum(DB::raw('TIME_TO_SEC(delai)'));
        $delai_total = $delai_sommeok + $delai_somme;
        // Convertir la somme totale des délais en format HH:MM:SS
        $hours = floor($delai_total / 3600);
        $minutes = floor(($delai_total % 3600) / 60);
        $seconds = $delai_total % 60;
        // Formater la durée en format HH:MM:SS
        $totalDuration = sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);


        //TICKET BLOQUE
        $delai_bloque = (int) Ticket::where('statut','LIKE', 'Bloqué%')
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
        ->where('intervenants_id', $id)
        ->sum(DB::raw('TIME_TO_SEC(delai)'));
        $ticketBloqueEn = Ticket::where('statut', 'like', 'Bloqué(%')
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->where('intervenants_id', $id)
            ->get();
        $delai_somme_bloq = 0;
        foreach ($ticketBloqueEn as $tic) {
                $delai_somme_bloq += (int) Ticket::where('num_tic', $tic->num_tic)
                    ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                    ->where('intervenants_id', $id)
                    ->where('statut','A Continué')
                    ->sum(DB::raw('TIME_TO_SEC(delai)'));
            }
        $delai_bloqueFor = $delai_bloque + $delai_somme_bloq;
        // Convertir la somme totale des délais en format HH:MM:SS
        $hours1 = floor($delai_bloqueFor / 3600);
        $minutes1 = floor(($delai_bloqueFor % 3600) / 60);
        $seconds1 = $delai_bloqueFor % 60;
        // Formater la durée en format HH:MM:SS
        $delai_bloqueF = sprintf("%02d:%02d:%02d", $hours1, $minutes1, $seconds1);
        $nb_bloque=Ticket::where('statut', 'LIKE', 'Bloqué%')
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
        ->where('intervenants_id', $id)
        ->count();

            //TICKET A SUIVRE
        $nb_suivre=Ticket::where('statut', 'LIKE', 'A Suivre%')
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
        ->where('intervenants_id', $id)
        ->count();
        $delai_suivre = (int) Ticket::where('statut','LIKE', 'A Suivre%')
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
        ->where('intervenants_id', $id)
        ->sum(DB::raw('TIME_TO_SEC(delai)'));
        $hours2 = floor($delai_suivre / 3600);
        $minutes2 = floor(($delai_suivre % 3600) / 60);
        $seconds2 = $delai_suivre % 60;
        // Formater la durée en format HH:MM:SS
        $delai_suivreF = sprintf("%02d:%02d:%02d", $hours2, $minutes2, $seconds2);

            //TICKET INACCESSIBLE
        $nb_inaccessible=Ticket::where('statut', '=', 'Inaccessible')
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
        ->where('intervenants_id', $id)
        ->count();
        $delai_inaccessible = (int) Ticket::where('statut', 'Inaccessible')
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
        ->where('intervenants_id', $id)
        ->sum(DB::raw('TIME_TO_SEC(delai)'));
        $hours3 = floor($delai_inaccessible / 3600);
        $minutes3 = floor(($delai_inaccessible % 3600) / 60);
        $seconds3 = $delai_inaccessible % 60;
        // Formater la durée en format HH:MM:SS
        $delai_inaccessibleF = sprintf("%02d:%02d:%02d", $hours3, $minutes3, $seconds3);
        $nb_inaccessible=Ticket::where('statut', '=', 'Inaccessible')
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
        ->where('intervenants_id', $id)
        ->count();

        //TICKET EN COURS
        $nb_encours=Ticket::where('statut', 'LIKE', 'En cours%')
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
        ->where('intervenants_id', $id)
        ->count();

        //PAUSE
        $delai_pause = (int) Ticket::join('actions','tickets.actions_id','actions.id')
        ->where('actions.nom_action', 'Pause')
        ->whereBetween('tickets.created_at', [$startOfMonth, $endOfMonth])
        ->where('intervenants_id', $id)
        ->sum(DB::raw('TIME_TO_SEC(delai)'));
        $hoursp = floor($delai_pause / 3600);
        $minutesp = floor(($delai_pause % 3600) / 60);
        $secondsp = $delai_pause % 60;
        // Formater la durée en format HH:MM:SS
        $delai_pauseF = sprintf("%02d:%02d:%02d", $hoursp, $minutesp, $secondsp);

        $nb_pause=Ticket::join('actions','tickets.actions_id','actions.id')
        ->where('actions.nom_action', 'Pause')
        ->whereBetween('tickets.created_at', [$startOfMonth, $endOfMonth])
        ->where('intervenants_id', $id)
        ->count();

        //%TAGE OK
        if($nb_ticket>0){
            $pourcOK1=($nb_ticket/$nb_tot)*100;
            $pourcOK=number_format($pourcOK1,2);
        }
        else{
            $pourcOK=0.00;
        }

        //%TAGE A SUIVRE
        if($nb_suivre>0){
            $pourcSuivre1=($nb_suivre/$nb_tot)*100;
            $pourcSuivre=number_format($pourcSuivre1,2);
        }
        else{
            $pourcSuivre=0.00;
        }

        //%TAGE BLOQUE
        if($nb_bloque>0){
            $pourcBloque1=($nb_bloque/$nb_tot)*100;
            $pourcBloque=number_format($pourcBloque1,2);
        }
        else{
            $pourcBloque=0.00;
        }

        //%TAGE INACCESSIBLE
        if($nb_inaccessible>0){
            $pourcinaccessible1=($nb_inaccessible/$nb_tot)*100;
            $pourcinaccessible=number_format($pourcinaccessible1,2);
        }
        else{
            $pourcinaccessible=0.00;
        }

        //%TAGE EN COURS
        if($nb_encours>0){
            $pourcencours1=($nb_encours/$nb_tot)*100;
            $pourcencours=number_format($pourcencours1,2);
        }
        else{
            $pourcencours=0.00;
        }

        //%TAGE PAUSE
        if($nb_pause>0){
            $pourcPause1=($nb_pause/$nb_tot)*100;
            $pourcPause=number_format($pourcPause1,2);
        }
        else{
            $pourcPause=0.00;
        }

        //PRODUCTION
    $nb_prod=Ticket::join('actions','tickets.actions_id','=','actions.id')
    ->where('actions.nom_action', 'production')
    ->where('statut','LIKE', 'OK%')
    ->where('intervenants_id',$id)
    ->whereBetween('tickets.created_at', [$startOfMonth, $endOfMonth])
    ->count();

    $delai_prod = (int) Ticket::join('actions','tickets.actions_id','actions.id')
    ->where('actions.nom_action', 'production')
    ->where('statut','LIKE', 'OK%')
    ->where('intervenants_id',$id)
    ->whereBetween('tickets.created_at', [$startOfMonth, $endOfMonth])
    ->sum(DB::raw('TIME_TO_SEC(delai)'));
    $hourspro = floor($delai_prod / 3600);
    $minutespro = floor(($delai_prod % 3600) / 60);
    $secondspro = $delai_prod % 60;
    // Formater la durée en format HH:MM:SS
    $delai_prodF = sprintf("%02d:%02d:%02d", $hourspro, $minutespro, $secondspro);

    //%TAGE
    if($nb_prod>0){
        $nb_prod1=($nb_prod/$nb_tot)*100;
        $pourcprod=number_format($nb_prod1,2);
    }
    else{
        $pourcprod=0.00;
    }

        // Obtenir le mois et l'année au format "mm:aaaa"
        $moisAnnee = $startOfMonth->format('m/Y');

        //TEMPS INTERFICHIER
        $sumInterfileTimes = Ticket::sumInterfichierTempsMensuel($id,$startOfMonth,$endOfMonth);

        //TESTER SI AUCUN TICKET DANS CET MOIS
        if($nb_tot<1){
            return redirect('/statmensuel')->with('vide',"Aucune ticket de cet Mois");
        }
        else{
            return view('statistique.statmensuel', [
                'nb_prod' => $nb_prod,
                'delai_prod' => $delai_prodF,
                'pourcprod' => $pourcprod,
                'intervenant' => $intervenant,
                'nb_ticket' => $nb_ticket,
                'totalSumDelai' => $totalDuration,
                'nb_bloque' => $nb_bloque,
                'nb_suivre' => $nb_suivre,
                'nb_inaccessible' => $nb_inaccessible,
                'nb_encours' => $nb_encours,
                'delai_bloque'=> $delai_bloqueF,
                'delai_suivre'=> $delai_suivreF,
                'delai_inaccessible'=> $delai_inaccessibleF,
                'pourcBloque'=> $pourcBloque,
                'pourcinaccessible'=> $pourcinaccessible,
                'pourcOK'=> $pourcOK,
                'pourcSuivre'=> $pourcSuivre,
                'pourcencours'=> $pourcencours,
                'startOfMonth' => $moisAnnee,
                'delai_pause'=>$delai_pauseF,
                'nb_pause'=>$nb_pause,
                'pourcPause'=>$pourcPause,
                'nb_ticket_tot'=>$nb_tot,
                'sumInterfileTimes'=>$sumInterfileTimes
            ]);
        }
    }

    public function hebdo(){
        $intervenant = session('intervenant');
        return view('statistique.stathebdo',['intervenant' => $intervenant,]);
    }
    public function stathebdo(Request $request){
        $intervenant = session('intervenant');
        $id=$intervenant->first()->id;

        // Numéro de la semaine et année souhaitée
        $weekNumber = $request->num_semaine;
        $year = $request->ans;

        // Création d'un objet DateTime pour le premier jour de la semaine spécifiée
        $startDate = new DateTime();
        $startDate->setISODate($year, $weekNumber, 1); // Premier jour de la semaine

        // Création d'un objet DateTime pour le dernier jour de la semaine spécifiée
        $endDate = new DateTime();
        $endDate->setISODate($year, $weekNumber, 7); // Dernier jour de la semaine

        // Soustraire 2 jours à la date de fin
        $endDate->sub(new DateInterval('P2D'));

        // Formatage des dates en chaînes de caractères
        $startDateStr = $startDate->format('Y-m-d');
        $endDateStr = $endDate->format('Y-m-d');

        // Conversion des chaînes de caractères en objets DateTime
        $startOfMonth = DateTime::createFromFormat('Y-m-d', $startDateStr);
        $endOfMonth = DateTime::createFromFormat('Y-m-d', $endDateStr);

        // Récupérer le nombre de tickets avec un statut commençant par "OK" pour le semaine
        $nb_ticket = Ticket::where('statut', 'LIKE', 'OK%')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->where('intervenants_id',$id)
            ->count();

        $ticketOkEn = Ticket::where('statut', 'like', 'OK(En%')
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->where('intervenants_id', $id)
            ->get();

        //NBR DE TICKET TOTAL POUR L'INTERVENANT SPECIFIE POUR CET SEMAINE
        $nb_tot=Ticket::where('intervenants_id', $id)
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
        ->where('statut','!=','A Continué')
        ->count();

        $delai_somme = 0;

        foreach ($ticketOkEn as $tic) {
            $delai_somme += (int) Ticket::where('num_tic', $tic->num_tic)
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->where('intervenants_id', $id)
                ->sum(DB::raw('TIME_TO_SEC(delai)'));
        }

        $delai_sommeok = (int) Ticket::where('statut', 'OK')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->where('intervenants_id', $id)
            ->sum(DB::raw('TIME_TO_SEC(delai)'));

        $delai_total = $delai_sommeok + $delai_somme;

        // Convertir la somme totale des délais en format HH:MM:SS
        $hours = floor($delai_total / 3600);
        $minutes = floor(($delai_total % 3600) / 60);
        $seconds = $delai_total % 60;

        // Formater la durée en format HH:MM:SS
        $totalDuration = sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);


        //TICKET BLOQUE
        $delai_bloque = (int) Ticket::where('statut','LIKE', 'Bloqué%')
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
        ->where('intervenants_id', $id)
        ->sum(DB::raw('TIME_TO_SEC(delai)'));

        $ticketBloqueEn = Ticket::where('statut', 'like', 'Bloqué(%')
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->where('intervenants_id', $id)
            ->get();
        $delai_somme_bloq = 0;
        foreach ($ticketBloqueEn as $tic) {
                $delai_somme_bloq += (int) Ticket::where('num_tic', $tic->num_tic)
                    ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                    ->where('intervenants_id', $id)
                    ->where('statut','A Continué')
                    ->sum(DB::raw('TIME_TO_SEC(delai)'));
            }
        $delai_bloqueFor = $delai_bloque + $delai_somme_bloq;
        // Convertir la somme totale des délais en format HH:MM:SS
        $hours1 = floor($delai_bloqueFor / 3600);
        $minutes1 = floor(($delai_bloqueFor % 3600) / 60);
        $seconds1 = $delai_bloqueFor % 60;
        // Formater la durée en format HH:MM:SS
        $delai_bloqueF = sprintf("%02d:%02d:%02d", $hours1, $minutes1, $seconds1);

        $nb_bloque=Ticket::where('statut', 'LIKE', 'Bloqué%')
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
        ->where('intervenants_id', $id)
        ->count();


        //TICKET A SUIVRE
        $nb_suivre=Ticket::where('statut', 'LIKE', 'A Suivre%')
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
        ->where('intervenants_id', $id)
        ->count();
        $ticketSuivreEn = Ticket::where('statut', 'like', 'A Suivre(%')
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->where('intervenants_id', $id)
            ->get();
        $delai_somme_suivre = 0;
        foreach ($ticketSuivreEn as $tic) {
            $delai_somme_suivre += (int) Ticket::where('num_tic', $tic->num_tic)
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->where('intervenants_id', $id)
                ->where('statut', 'A Continué')
                ->sum(DB::raw('TIME_TO_SEC(delai)'));
        }
        $delai_suivre = (int) Ticket::where('statut','LIKE', 'A Suivre%')
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
        ->where('intervenants_id', $id)
        ->sum(DB::raw('TIME_TO_SEC(delai)'));

        $delai_suivreFor = $delai_suivre + $delai_somme_suivre;
        $hours2 = floor($delai_suivreFor / 3600);
        $minutes2 = floor(($delai_suivreFor % 3600) / 60);
        $seconds2 = $delai_suivreFor % 60;
        // Formater la durée en format HH:MM:SS
        $delai_suivreF = sprintf("%02d:%02d:%02d", $hours2, $minutes2, $seconds2);


        //PAUSE
        $delai_pause = (int) Ticket::join('actions','tickets.actions_id','=','actions.id')
        ->where('actions.nom_action', 'Pause')
        ->whereBetween('tickets.created_at', [$startOfMonth, $endOfMonth])
        ->where('intervenants_id', $id)
        ->sum(DB::raw('TIME_TO_SEC(delai)'));
        $hoursp = floor($delai_pause / 3600);
        $minutesp = floor(($delai_pause % 3600) / 60);
        $secondsp = $delai_pause % 60;
        // Formater la durée en format HH:MM:SS
        $delai_pauseF = sprintf("%02d:%02d:%02d", $hoursp, $minutesp, $secondsp);
        $nb_pause=Ticket::join('actions','tickets.actions_id','=','actions.id')
        ->where('actions.nom_action', 'Pause')
        ->whereBetween('tickets.created_at', [$startOfMonth, $endOfMonth])
        ->where('intervenants_id', $id)
        ->count();


        //TICKET INACCESSIBLE
        $delai_inaccessible = (int) Ticket::where('statut', 'Inaccessible')
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
        ->where('intervenants_id', $id)
        ->sum(DB::raw('TIME_TO_SEC(delai)'));
        $hours3 = floor($delai_inaccessible / 3600);
        $minutes3 = floor(($delai_inaccessible % 3600) / 60);
        $seconds3 = $delai_inaccessible % 60;
        // Formater la durée en format HH:MM:SS
        $delai_inaccessibleF = sprintf("%02d:%02d:%02d", $hours3, $minutes3, $seconds3);
        $nb_inaccessible=Ticket::where('statut', 'Inaccessible')
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
        ->where('intervenants_id', $id)
        ->count();


        //TICKET EN COURS
        $nb_encours=Ticket::where('statut', 'LIKE', 'En cours%')
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
        ->where('intervenants_id', $id)
        ->count();

        //%TAGE OK
        if($nb_ticket>0){
            $pourcOK1=($nb_ticket/$nb_tot)*100;
            $pourcOK=number_format($pourcOK1,2);
        }
        else{
            $pourcOK=0.00;
        }

        //%TAGE A SUIVRE
        if($nb_suivre>0){
            $pourcSuivre1=($nb_suivre/$nb_tot)*100;
            $pourcSuivre=number_format($pourcSuivre1,2);
        }
        else{
            $pourcSuivre=0.00;
        }

        //%TAGE PAUSE
        if($nb_pause>0){
            $pourcPause1=($nb_pause/$nb_tot)*100;
            $pourcPause=number_format($pourcPause1,2);
        }
        else{
            $pourcPause=0.00;
        }

        //%TAGE BLOQUE
        if($nb_bloque>0){
            $pourcBloque1=($nb_bloque/$nb_tot)*100;
            $pourcBloque=number_format($pourcBloque1,2);
        }
        else{
            $pourcBloque=0.00;
        }

        //%TAGE INACCESSIBLE
        if($nb_inaccessible>0){
            $pourcinaccessible1=($nb_inaccessible/$nb_tot)*100;
            $pourcinaccessible=number_format($pourcinaccessible1,2);
        }
        else{
            $pourcinaccessible=0.00;
        }

        //%TAGE EN COURS
        if($nb_encours>0){
            $pourcencours1=($nb_encours/$nb_tot)*100;
            $pourcencours=number_format($pourcencours1,2);
        }
        else{
            $pourcencours=0.00;
        }

         //PRODUCTION
    $nb_prod=Ticket::join('actions','tickets.actions_id','=','actions.id')
    ->where('actions.nom_action', 'production')
    ->where('statut','LIKE', 'OK%')
    ->where('intervenants_id',$id)
    ->whereBetween('tickets.created_at', [$startOfMonth, $endOfMonth])
    ->count();

    $delai_prod = (int) Ticket::join('actions','tickets.actions_id','actions.id')
    ->where('actions.nom_action', 'production')
    ->where('statut','LIKE', 'OK%')
    ->where('intervenants_id',$id)
    ->whereBetween('tickets.created_at', [$startOfMonth, $endOfMonth])
    ->sum(DB::raw('TIME_TO_SEC(delai)'));
    $hourspro = floor($delai_prod / 3600);
    $minutespro = floor(($delai_prod % 3600) / 60);
    $secondspro = $delai_prod % 60;
    // Formater la durée en format HH:MM:SS
    $delai_prodF = sprintf("%02d:%02d:%02d", $hourspro, $minutespro, $secondspro);

    //%TAGE
    if($nb_prod>0){
        $nb_prod1=($nb_prod/$nb_tot)*100;
        $pourcprod=number_format($nb_prod1,2);
    }
    else{
        $pourcprod=0.00;
    }


        // Obtenir la date au format "jj:mm:aaaa"
        $dateFormatteestartOfMonth = $startOfMonth->format('d/m/Y');
        $dateFormatteeendOfMonth = $endOfMonth->format('d/m/Y');

        //TEMPS INTERFICHIER
        $sumInterfileTimes = Ticket::sumInterfichierTempsMensuel($id,$startOfMonth,$endOfMonth);

        //TESTER SI AUCUNE TICKET DANS CET SEMAINE POUR L'INTERVENANT SPECIFIE
        if($nb_tot<1){
            return redirect('/stathebdo')->with('vide',"Aucune ticket de cet Mois");
        }
        else{
            return view('statistique.stathebdo', [
                'nb_prod' => $nb_prod,
                'delai_prod' => $delai_prodF,
                'pourcprod' => $pourcprod,
                'intervenant' => $intervenant,
                'nb_ticket' => $nb_ticket,
                'totalSumDelai' => $totalDuration,
                'nb_bloque' => $nb_bloque,
                'nb_suivre' => $nb_suivre,
                'nb_inaccessible' => $nb_inaccessible,
                'nb_encours' => $nb_encours,
                'delai_bloque'=> $delai_bloqueF,
                'delai_suivre'=> $delai_suivreF,
                'delai_inaccessible'=> $delai_inaccessibleF,
                'pourcBloque'=> $pourcBloque,
                'pourcinaccessible'=> $pourcinaccessible,
                'pourcOK'=> $pourcOK,
                'pourcSuivre'=> $pourcSuivre,
                'pourcencours'=> $pourcencours,
                'startOfMonth'=> $dateFormatteestartOfMonth,
                'endOfMonth'=> $dateFormatteeendOfMonth,
                'delai_pause'=>$delai_pauseF,
                'nb_pause'=>$nb_pause,
                'pourcPause'=>$pourcPause,
                'sumInterfileTimes'=>$sumInterfileTimes,
                'weekNumber'=>$weekNumber
            ]);
        }

    }

    public function hebdoAujourd(){
        $intervenant = session('intervenant');
        $id=$intervenant->first()->id;
        // Date actuelle
        $endOfMonth = new DateTime();
        $endOfMonth->modify('-1 days');

        // Modifier la date pour obtenir la date de 1 semaine avant
        $startOfMonth = clone $endOfMonth;
        $startOfMonth->modify('-6 days');

        // Récupérer le nombre de tickets avec un statut commençant par "OK" pour la semaine précédente
        $nb_ticket = Ticket::where('statut', 'LIKE', 'OK%')
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
        ->where('intervenants_id', $id)
        ->count();

        $ticketOkEn = Ticket::where('statut', 'like', 'OK(En%')
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->where('intervenants_id', $id)
            ->get();

        //NBR DE TICKET TOTAL POUR L'INTERVENANT SPECIFIE POUR CET SEMAINE
        $nb_tot=Ticket::where('intervenants_id', $id)
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
        ->count();

        $delai_somme = 0;

        foreach ($ticketOkEn as $tic) {
            $delai_somme += (int) Ticket::where('num_tic', $tic->num_tic)
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->where('intervenants_id', $id)
                ->sum(DB::raw('TIME_TO_SEC(delai)'));
        }

        $delai_sommeok = (int) Ticket::where('statut', 'OK')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->where('intervenants_id', $id)
            ->sum(DB::raw('TIME_TO_SEC(delai)'));

        $delai_total = $delai_sommeok + $delai_somme;

        // Convertir la somme totale des délais en format HH:MM:SS
        $hours = floor($delai_total / 3600);
        $minutes = floor(($delai_total % 3600) / 60);
        $seconds = $delai_total % 60;

        // Formater la durée en format HH:MM:SS
        $totalDuration = sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);


        //TICKET BLOQUE
        $delai_bloque = (int) Ticket::where('statut','LIKE', 'Bloqué%')
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
        ->where('intervenants_id', $id)
        ->sum(DB::raw('TIME_TO_SEC(delai)'));

        $ticketBloqueEn = Ticket::where('statut', 'like', 'Bloqué(%')
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->where('intervenants_id', $id)
            ->get();
        $delai_somme_bloq = 0;
        foreach ($ticketBloqueEn as $tic) {
                $delai_somme_bloq += (int) Ticket::where('num_tic', $tic->num_tic)
                    ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                    ->where('intervenants_id', $id)
                    ->where('statut','A Continué')
                    ->sum(DB::raw('TIME_TO_SEC(delai)'));
            }
        $delai_bloqueFor = $delai_bloque + $delai_somme_bloq;
        // Convertir la somme totale des délais en format HH:MM:SS
        $hours1 = floor($delai_bloqueFor / 3600);
        $minutes1 = floor(($delai_bloqueFor % 3600) / 60);
        $seconds1 = $delai_bloqueFor % 60;
        // Formater la durée en format HH:MM:SS
        $delai_bloqueF = sprintf("%02d:%02d:%02d", $hours1, $minutes1, $seconds1);

        $nb_bloque=Ticket::where('statut', 'LIKE', 'Bloqué%')
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
        ->where('intervenants_id', $id)
        ->count();


        //TICKET A SUIVRE
        $nb_suivre=Ticket::where('statut', 'LIKE', 'A Suivre%')
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
        ->where('intervenants_id', $id)
        ->count();
        $ticketSuivreEn = Ticket::where('statut', 'like', 'A Suivre(%')
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->where('intervenants_id', $id)
            ->get();
        $delai_somme_suivre = 0;
        foreach ($ticketSuivreEn as $tic) {
            $delai_somme_suivre += (int) Ticket::where('num_tic', $tic->num_tic)
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->where('intervenants_id', $id)
                ->where('statut', 'A Continué')
                ->sum(DB::raw('TIME_TO_SEC(delai)'));
        }
        $delai_suivre = (int) Ticket::where('statut','LIKE', 'A Suivre%')
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
        ->where('intervenants_id', $id)
        ->sum(DB::raw('TIME_TO_SEC(delai)'));

        $delai_suivreFor = $delai_suivre + $delai_somme_suivre;
        $hours2 = floor($delai_suivreFor / 3600);
        $minutes2 = floor(($delai_suivreFor % 3600) / 60);
        $seconds2 = $delai_suivreFor % 60;
        // Formater la durée en format HH:MM:SS
        $delai_suivreF = sprintf("%02d:%02d:%02d", $hours2, $minutes2, $seconds2);


        //PAUSE
        $delai_pause = (int) Ticket::join('actions','tickets.actions_id','actions.id')
        ->where('actions.nom_action', 'Pause')
        ->whereBetween('tickets.created_at', [$startOfMonth, $endOfMonth])
        ->where('intervenants_id', $id)
        ->sum(DB::raw('TIME_TO_SEC(delai)'));
        $hoursp = floor($delai_pause / 3600);
        $minutesp = floor(($delai_pause % 3600) / 60);
        $secondsp = $delai_pause % 60;
        // Formater la durée en format HH:MM:SS
        $delai_pauseF = sprintf("%02d:%02d:%02d", $hoursp, $minutesp, $secondsp);
        $nb_pause=Ticket::join('actions','tickets.actions_id','actions.id')
        ->where('actions.nom_action', 'Pause')
        ->whereBetween('tickets.created_at', [$startOfMonth, $endOfMonth])
        ->where('intervenants_id', $id)
        ->count();


        //TICKET INACCESSIBLE
        $delai_inaccessible = (int) Ticket::where('statut', 'Inaccessible')
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
        ->where('intervenants_id', $id)
        ->sum(DB::raw('TIME_TO_SEC(delai)'));
        $hours3 = floor($delai_inaccessible / 3600);
        $minutes3 = floor(($delai_inaccessible % 3600) / 60);
        $seconds3 = $delai_inaccessible % 60;
        // Formater la durée en format HH:MM:SS
        $delai_inaccessibleF = sprintf("%02d:%02d:%02d", $hours3, $minutes3, $seconds3);
        $nb_inaccessible=Ticket::where('statut', 'Inaccessible')
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
        ->where('intervenants_id', $id)
        ->count();


        //TICKET EN COURS
        $nb_encours=Ticket::where('statut', 'LIKE', 'En cours%')
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
        ->where('intervenants_id', $id)
        ->count();

        //%TAGE OK
        if($nb_ticket>0){
            $pourcOK1=($nb_ticket/$nb_tot)*100;
            $pourcOK=number_format($pourcOK1,2);
        }
        else{
            $pourcOK=0.00;
        }

        //%TAGE A SUIVRE
        if($nb_suivre>0){
            $pourcSuivre1=($nb_suivre/$nb_tot)*100;
            $pourcSuivre=number_format($pourcSuivre1,2);
        }
        else{
            $pourcSuivre=0.00;
        }

        //%TAGE PAUSE
        if($nb_pause>0){
            $pourcPause1=($nb_pause/$nb_tot)*100;
            $pourcPause=number_format($pourcPause1,2);
        }
        else{
            $pourcPause=0.00;
        }

        //%TAGE BLOQUE
        if($nb_bloque>0){
            $pourcBloque1=($nb_bloque/$nb_tot)*100;
            $pourcBloque=number_format($pourcBloque1,2);
        }
        else{
            $pourcBloque=0.00;
        }

        //%TAGE INACCESSIBLE
        if($nb_inaccessible>0){
            $pourcinaccessible1=($nb_inaccessible/$nb_tot)*100;
            $pourcinaccessible=number_format($pourcinaccessible1,2);
        }
        else{
            $pourcinaccessible=0.00;
        }

        //%TAGE EN COURS
        if($nb_encours>0){
            $pourcencours1=($nb_encours/$nb_tot)*100;
            $pourcencours=number_format($pourcencours1,2);
        }
        else{
            $pourcencours=0.00;
        }


        //PRODUCTION
        $nb_prod=Ticket::join('actions','tickets.actions_id','=','actions.id')
        ->where('actions.nom_action', 'production')
        ->where('intervenants_id', $id)
        ->where('statut','LIKE', 'OK%')
        ->whereBetween('tickets.created_at', [$startOfMonth, $endOfMonth])
        ->count();

        $delai_prod = (int) Ticket::join('actions','tickets.actions_id','actions.id')
        ->where('actions.nom_action', 'production')
        ->where('intervenants_id', $id)
        ->where('statut','LIKE', 'OK%')
        ->whereBetween('tickets.created_at', [$startOfMonth, $endOfMonth])
        ->sum(DB::raw('TIME_TO_SEC(delai)'));
        $hourspro = floor($delai_prod / 3600);
        $minutespro = floor(($delai_prod % 3600) / 60);
        $secondspro = $delai_prod % 60;
        // Formater la durée en format HH:MM:SS
        $delai_prodF = sprintf("%02d:%02d:%02d", $hourspro, $minutespro, $secondspro);

        //%TAGE
        if($nb_prod>0){
            $nb_prod1=($nb_prod/$nb_tot)*100;
            $pourcprod=number_format($nb_prod1,2);
        }
        else{
            $pourcprod=0.00;
        }

        // Obtenir la date au format "jj:mm:aaaa"
        $dateFormatteestartOfMonth = $startOfMonth->format('d/m/Y');
        $dateFormatteeendOfMonth = $endOfMonth->format('d/m/Y');

        //TEMPS INTERFICHIER
        $sumInterfileTimes = Ticket::sumInterfichierTempsMensuel($id,$startOfMonth,$endOfMonth);

            return view('statistique.stathebdoAjourd', [
                'nb_prod' => $nb_prod,
                'delai_prod' => $delai_prodF,
                'pourcprod' => $pourcprod,
                'intervenant' => $intervenant,
                'nb_ticket' => $nb_ticket,
                'totalSumDelai' => $totalDuration,
                'nb_bloque' => $nb_bloque,
                'nb_suivre' => $nb_suivre,
                'nb_inaccessible' => $nb_inaccessible,
                'nb_encours' => $nb_encours,
                'delai_bloque'=> $delai_bloqueF,
                'delai_suivre'=> $delai_suivreF,
                'delai_inaccessible'=> $delai_inaccessibleF,
                'pourcBloque'=> $pourcBloque,
                'pourcinaccessible'=> $pourcinaccessible,
                'pourcOK'=> $pourcOK,
                'pourcSuivre'=> $pourcSuivre,
                'pourcencours'=> $pourcencours,
                'startOfMonth'=> $dateFormatteestartOfMonth,
                'endOfMonth'=> $dateFormatteeendOfMonth,
                'delai_pause'=>$delai_pauseF,
                'nb_pause'=>$nb_pause,
                'pourcPause'=>$pourcPause,
                'sumInterfileTimes'=>$sumInterfileTimes
            ]);
    }

    public function globalAujourd(){
        $intervenant = session('intervenant');
        // Date actuelle
        $endOfMonth = new DateTime();
        $endOfMonth->modify('-1 days');

        // Modifier la date pour obtenir la date de 1 semaine avant
        $startOfMonth = clone $endOfMonth;
        $startOfMonth->modify('-6 days');

        // Récupérer le nombre de tickets avec un statut commençant par "OK" pour la semaine précédente
        $nb_ticket = Ticket::where('statut', 'LIKE', 'OK%')
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
        ->count();

        $ticketOkEn = Ticket::where('statut', 'like', 'OK(En%')
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->get();

        //NBR DE TICKET TOTAL POUR L'INTERVENANT SPECIFIE POUR CET SEMAINE
        $nb_tot=Ticket::whereBetween('created_at', [$startOfMonth, $endOfMonth])
        ->count();

        $delai_somme = 0;

        foreach ($ticketOkEn as $tic) {
            $delai_somme += (int) Ticket::where('num_tic', $tic->num_tic)
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->sum(DB::raw('TIME_TO_SEC(delai)'));
        }

        $delai_sommeok = (int) Ticket::where('statut', 'OK')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->sum(DB::raw('TIME_TO_SEC(delai)'));

        $delai_total = $delai_sommeok + $delai_somme;

        // Convertir la somme totale des délais en format HH:MM:SS
        $hours = floor($delai_total / 3600);
        $minutes = floor(($delai_total % 3600) / 60);
        $seconds = $delai_total % 60;

        // Formater la durée en format HH:MM:SS
        $totalDuration = sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);


        //TICKET BLOQUE
        $delai_bloque = (int) Ticket::where('statut','LIKE', 'Bloqué%')
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
        ->sum(DB::raw('TIME_TO_SEC(delai)'));

        $ticketBloqueEn = Ticket::where('statut', 'like', 'Bloqué(%')
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->get();
        $delai_somme_bloq = 0;
        foreach ($ticketBloqueEn as $tic) {
                $delai_somme_bloq += (int) Ticket::where('num_tic', $tic->num_tic)
                    ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                    ->where('statut','A Continué')
                    ->sum(DB::raw('TIME_TO_SEC(delai)'));
            }
        $delai_bloqueFor = $delai_bloque + $delai_somme_bloq;
        // Convertir la somme totale des délais en format HH:MM:SS
        $hours1 = floor($delai_bloqueFor / 3600);
        $minutes1 = floor(($delai_bloqueFor % 3600) / 60);
        $seconds1 = $delai_bloqueFor % 60;
        // Formater la durée en format HH:MM:SS
        $delai_bloqueF = sprintf("%02d:%02d:%02d", $hours1, $minutes1, $seconds1);

        $nb_bloque=Ticket::where('statut', 'LIKE', 'Bloqué%')
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
        ->count();


        //TICKET A SUIVRE
        $nb_suivre=Ticket::where('statut', 'LIKE', 'A Suivre%')
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
        ->count();
        $ticketSuivreEn = Ticket::where('statut', 'like', 'A Suivre(%')
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->get();
        $delai_somme_suivre = 0;
        foreach ($ticketSuivreEn as $tic) {
            $delai_somme_suivre += (int) Ticket::where('num_tic', $tic->num_tic)
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->where('statut', 'A Continué')
                ->sum(DB::raw('TIME_TO_SEC(delai)'));
        }
        $delai_suivre = (int) Ticket::where('statut','LIKE', 'A Suivre%')
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
        ->sum(DB::raw('TIME_TO_SEC(delai)'));

        $delai_suivreFor = $delai_suivre + $delai_somme_suivre;
        $hours2 = floor($delai_suivreFor / 3600);
        $minutes2 = floor(($delai_suivreFor % 3600) / 60);
        $seconds2 = $delai_suivreFor % 60;
        // Formater la durée en format HH:MM:SS
        $delai_suivreF = sprintf("%02d:%02d:%02d", $hours2, $minutes2, $seconds2);


        //PAUSE
        $delai_pause = (int) Ticket::join('actions','tickets.actions_id','actions.id')
        ->where('actions.nom_action', 'Pause')
        ->whereBetween('tickets.created_at', [$startOfMonth, $endOfMonth])
        ->sum(DB::raw('TIME_TO_SEC(delai)'));
        $hoursp = floor($delai_pause / 3600);
        $minutesp = floor(($delai_pause % 3600) / 60);
        $secondsp = $delai_pause % 60;
        // Formater la durée en format HH:MM:SS
        $delai_pauseF = sprintf("%02d:%02d:%02d", $hoursp, $minutesp, $secondsp);

        $nb_pause=Ticket::join('actions','tickets.actions_id','actions.id')
        ->where('actions.nom_action', 'Pause')
        ->whereBetween('tickets.created_at', [$startOfMonth, $endOfMonth])
        ->count();


        //TICKET INACCESSIBLE
        $delai_inaccessible = (int) Ticket::where('statut', 'Inaccessible')
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
        ->sum(DB::raw('TIME_TO_SEC(delai)'));
        $hours3 = floor($delai_inaccessible / 3600);
        $minutes3 = floor(($delai_inaccessible % 3600) / 60);
        $seconds3 = $delai_inaccessible % 60;
        // Formater la durée en format HH:MM:SS
        $delai_inaccessibleF = sprintf("%02d:%02d:%02d", $hours3, $minutes3, $seconds3);
        $nb_inaccessible=Ticket::where('statut', 'Inaccessible')
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
        ->count();


        //TICKET EN COURS
        $nb_encours=Ticket::where('statut', 'LIKE', 'En cours%')
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
        ->count();

        //%TAGE OK
        if($nb_ticket>0){
            $pourcOK1=($nb_ticket/$nb_tot)*100;
            $pourcOK=number_format($pourcOK1,2);
        }
        else{
            $pourcOK=0.00;
        }

        //%TAGE A SUIVRE
        if($nb_suivre>0){
            $pourcSuivre1=($nb_suivre/$nb_tot)*100;
            $pourcSuivre=number_format($pourcSuivre1,2);
        }
        else{
            $pourcSuivre=0.00;
        }

        //%TAGE PAUSE
        if($nb_pause>0){
            $pourcPause1=($nb_pause/$nb_tot)*100;
            $pourcPause=number_format($pourcPause1,2);
        }
        else{
            $pourcPause=0.00;
        }

        //%TAGE BLOQUE
        if($nb_bloque>0){
            $pourcBloque1=($nb_bloque/$nb_tot)*100;
            $pourcBloque=number_format($pourcBloque1,2);
        }
        else{
            $pourcBloque=0.00;
        }

        //%TAGE INACCESSIBLE
        if($nb_inaccessible>0){
            $pourcinaccessible1=($nb_inaccessible/$nb_tot)*100;
            $pourcinaccessible=number_format($pourcinaccessible1,2);
        }
        else{
            $pourcinaccessible=0.00;
        }

        //%TAGE EN COURS
        if($nb_encours>0){
            $pourcencours1=($nb_encours/$nb_tot)*100;
            $pourcencours=number_format($pourcencours1,2);
        }
        else{
            $pourcencours=0.00;
        }


        //PRODUCTION
        $nb_prod=Ticket::join('actions','tickets.actions_id','=','actions.id')
        ->where('actions.nom_action', 'production')
        ->where('statut','LIKE', 'OK%')
        ->whereBetween('tickets.created_at', [$startOfMonth, $endOfMonth])
        ->count();

        $delai_prod = (int) Ticket::join('actions','tickets.actions_id','actions.id')
        ->where('actions.nom_action', 'production')
        ->where('statut','LIKE', 'OK%')
        ->whereBetween('tickets.created_at', [$startOfMonth, $endOfMonth])
        ->sum(DB::raw('TIME_TO_SEC(delai)'));
        $hourspro = floor($delai_prod / 3600);
        $minutespro = floor(($delai_prod % 3600) / 60);
        $secondspro = $delai_prod % 60;
        // Formater la durée en format HH:MM:SS
        $delai_prodF = sprintf("%02d:%02d:%02d", $hourspro, $minutespro, $secondspro);

        //%TAGE
        if($nb_prod>0){
            $nb_prod1=($nb_prod/$nb_tot)*100;
            $pourcprod=number_format($nb_prod1,2);
        }
        else{
            $pourcprod=0.00;
        }

        // Récupérer le nombre de tickets avec un statut commnce par "OK" pour le mois de l'input group by inter
        $nb_ticketOK_tri = Ticket::join('intervenants','tickets.intervenants_id','=','intervenants.id')
        ->where('statut','LIKE','OK%')
        ->whereBetween('tickets.created_at', [$startOfMonth, $endOfMonth])
        ->select('intervenants.nom_inter as nom_i',DB::raw('count(*) as nb_oktri'))
        ->groupBy('nom_i')
        ->get();


        // Obtenir la date au format "jj:mm:aaaa"
        $dateFormatteestartOfMonth = $startOfMonth->format('d/m/Y');
        $dateFormatteeendOfMonth = $endOfMonth->format('d/m/Y');

        //TEMPS INTERFICHIER
        $sumInterfileTimes = Ticket::sumInterfichierTempsMensuelGlobal($startOfMonth,$endOfMonth);
        $admin = session('admin');
            return view('statistique.statglobalAjourd', [
                'admin'=>$admin,
                'nb_prod' => $nb_prod,
                'delai_prod' => $delai_prodF,
                'pourcprod' => $pourcprod,
                'intervenant' => $intervenant,
                'nb_ticket' => $nb_ticket,
                'totalSumDelai' => $totalDuration,
                'nb_bloque' => $nb_bloque,
                'nb_suivre' => $nb_suivre,
                'nb_inaccessible' => $nb_inaccessible,
                'nb_encours' => $nb_encours,
                'delai_bloque'=> $delai_bloqueF,
                'delai_suivre'=> $delai_suivreF,
                'delai_inaccessible'=> $delai_inaccessibleF,
                'pourcBloque'=> $pourcBloque,
                'pourcinaccessible'=> $pourcinaccessible,
                'pourcOK'=> $pourcOK,
                'pourcSuivre'=> $pourcSuivre,
                'pourcencours'=> $pourcencours,
                'startOfMonth'=> $dateFormatteestartOfMonth,
                'endOfMonth'=> $dateFormatteeendOfMonth,
                'delai_pause'=>$delai_pauseF,
                'nb_pause'=>$nb_pause,
                'pourcPause'=>$pourcPause,
                'sumInterfileTimes'=>$sumInterfileTimes,
                'nb_ticketOK_tri'=>$nb_ticketOK_tri
            ]);
    }
}
