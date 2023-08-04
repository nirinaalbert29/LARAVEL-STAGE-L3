<?php

namespace App\Http\Controllers;

use App\Mail\EnvoyerMessage;
use App\Models\Admin;
use App\Models\Intervenant;
use App\Models\Ticket;
use Carbon\Carbon;
use DateInterval;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class adminController extends Controller
{
    public function stat(){
        $intervenant = session('intervenant');
        return view('statistique.statglobalmensuel',['intervenant' => $intervenant]);
    }
    public function statis(){
        $intervenant = session('intervenant');
        return view('statistique.statglobalhebdo',['intervenant' => $intervenant]);
    }
    public function globalmensuel(Request $request){
        $month = (int)$request->month;
        $year = (int)$request->year;

        $startOfMonth = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endOfMonth = Carbon::createFromDate($year, $month, 1)->endOfMonth();


        // Récupérer le nombre de tickets avec un statut commnce par "OK" pour le mois de l'input group by inter
        $nb_ticketOK_tri = Ticket::join('intervenants','tickets.intervenants_id','=','intervenants.id')
        ->where('statut','LIKE','OK%')
        ->whereBetween('tickets.created_at', [$startOfMonth, $endOfMonth])
        ->select('intervenants.nom_inter as nom_i',DB::raw('count(*) as nb_oktri'))
        ->groupBy('nom_i')
        ->get();

        // Récupérer le nombre de tickets avec un statut commnce par "OK" pour le mois de l'input
        $nb_ticketOK = Ticket::where('statut','LIKE','OK%')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->count();

        $ticketOkEn = Ticket::where('statut', 'LIKE', 'OK(En%')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->get();

        //NBR TICKET TOTAL POUR LE MOIS DE L'INPUT POUR TOUS LEES INTERVENANTS
        $nb_tot=Ticket::whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->count();

        $delai_somme = 0;

        foreach ($ticketOkEn as $tic) {
            $delai_somme += (int) Ticket::where('num_tic', $tic->num_tic)
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->where('statut','A Continué')
                ->sum(DB::raw('TIME_TO_SEC(delai)'));
        }

        $delai_sommeok = (int) Ticket::where('statut','LIKE', 'OK%')
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


        $delai_suivre = (int) Ticket::where('statut','LIKE', 'A Suivre%')
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
        ->sum(DB::raw('TIME_TO_SEC(delai)'));

        $ticketSuivreEn = Ticket::where('statut', 'like', 'A Suivre(%')
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->get();
        $delai_somme_suivre = 0;
        foreach ($ticketSuivreEn as $tic) {
            $delai_somme_suivre += (int) Ticket::where('num_tic', $tic->num_tic)
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->where('statut','A Continué')
                ->sum(DB::raw('TIME_TO_SEC(delai)'));
        }
        $delai_suivreFor = $delai_somme_suivre + $delai_suivre;
        $hours20 = floor($delai_suivreFor / 3600);
        $minutes20 = floor(($delai_suivreFor % 3600) / 60);
        $seconds20 = $delai_suivreFor % 60;
        // Formater la durée en format HH:MM:SS
        $delai_suivreF = sprintf("%02d:%02d:%02d", $hours20, $minutes20, $seconds20);

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
        $nb_pause=Ticket::join('actions','tickets.actions_id','=','actions.id')
        ->where('actions.nom_action', 'pause')
        ->whereBetween('tickets.created_at', [$startOfMonth, $endOfMonth])
        ->count();

        //PRODUCTION
        $nb_prod=Ticket::join('actions','tickets.actions_id','=','actions.id')
        ->where('actions.nom_action', 'production')
        ->whereBetween('tickets.created_at', [$startOfMonth, $endOfMonth])
        ->count();

        $delai_prod = (int) Ticket::join('actions','tickets.actions_id','actions.id')
        ->where('actions.nom_action', 'production')
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


        //TICKET INACCESSIBLE
        $nb_inaccessible=Ticket::where('statut', '=', 'Inaccessible')
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
        ->count();
        $delai_inaccessible = (int) Ticket::where('statut', 'Inaccessible')
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
        ->sum(DB::raw('TIME_TO_SEC(delai)'));
        $hours3 = floor($delai_inaccessible / 3600);
        $minutes3 = floor(($delai_inaccessible % 3600) / 60);
        $seconds3 = $delai_inaccessible % 60;
        // Formater la durée en format HH:MM:SS
        $delai_inaccessibleF = sprintf("%02d:%02d:%02d", $hours3, $minutes3, $seconds3);
        $nb_inaccessible=Ticket::where('statut', '=', 'Inaccessible')
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
        ->count();

        //%TAGE OK
        if($nb_ticketOK>0){
            $pourcOK1=($nb_ticketOK/$nb_tot)*100;
            $pourcOK=number_format($pourcOK1,2);
        }
        else{
            $pourcOK=0.00;
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

        // Obtenir le mois et l'année au format "mm:aaaa"
        $moisAnnee = $startOfMonth->format('m/Y');

        //TESTER SI AUCUN TICKET DANS CET MOIS POUR TOUS LES INTERVENANTS
        if($nb_tot<1){
            return redirect('/stathebdo')->with('vide',"Aucune ticket dans cet Mois");
        }
        else{
            return view('statistique.statglobalmensuel', [
                'nb_ticketOK' => $nb_ticketOK,
                'nb_bloque' => $nb_bloque,
                'nb_inaccessible' => $nb_inaccessible,
                'nb_pause'=>$nb_pause,
                'nb_ticketTotal'=>$nb_tot,
                'nb_prod'=>$nb_prod,
                'delai_bloque'=> $delai_bloqueF,
                'delai_suivre'=> $delai_suivreF,
                'delai_inaccessible'=> $delai_inaccessibleF,
                'delai_totalok' => $totalDuration,
                'delai_pause'=>$delai_pauseF,
                'delai_prod'=>$delai_prodF,
                'pourcBloque'=> $pourcBloque,
                'pourcinaccessible'=> $pourcinaccessible,
                'pourcOK'=> $pourcOK,
                'pourcPause'=>$pourcPause,
                'pourcprod'=>$pourcprod,
                'startOfMonth'=> $moisAnnee,
                'nb_ticketOK_tri'=>$nb_ticketOK_tri
            ]);
        }
    }

    public function globalhebdo(Request $request){
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

         // Récupérer le nombre de tickets avec un statut commnce par "OK" pour le mois de l'input
        $nb_ticketOK = Ticket::where('statut','LIKE','OK%')
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
        ->count();

        // Récupérer le nombre de tickets avec un statut commnce par "OK" pour le mois de l'input group by inter
        $nb_ticketOK_tri = Ticket::join('intervenants','tickets.intervenants_id','=','intervenants.id')
        ->where('statut','LIKE','OK%')
        ->whereBetween('tickets.created_at', [$startOfMonth, $endOfMonth])
        ->select('intervenants.nom_inter as nom_i',DB::raw('count(*) as nb_oktri'))
        ->groupBy('nom_i')
        ->get();

        //NOMBRE TOTAL DE TOUS LES TICKETS
        $nb_tot=Ticket::whereBetween('created_at', [$startOfMonth, $endOfMonth])
        ->count();

        //TICKET DE STATUT COMMENCE PAR OK(EN
        $ticketOkEn = Ticket::where('statut', 'like', 'OK(En%')
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
        ->get();

        $delai_somme = 0;

        foreach ($ticketOkEn as $tic) {
        $delai_somme += (int) Ticket::where('num_tic', $tic->num_tic)
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->where('statut','A Continué')
            ->sum(DB::raw('TIME_TO_SEC(delai)'));
        }

        $delai_sommeok = (int) Ticket::where('statut','LIKE', 'OK%')
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
        ->sum(DB::raw('TIME_TO_SEC(delai)'));

        $delai_total = $delai_sommeok + $delai_somme;

        // Convertir la somme totale des délais en format HH:MM:SS
        $hours = floor($delai_total / 3600);
        $minutes = floor(($delai_total % 3600) / 60);
        $seconds = $delai_total % 60;

        // Formater la durée en format HH:MM:SS DE DELAI OK
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
        $delai_suivre = (int) Ticket::where('statut','LIKE', 'A Suivre%')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->sum(DB::raw('TIME_TO_SEC(delai)'));

        $ticketSuivreEn = Ticket::where('statut', 'like', 'A Suivre(%')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->get();
        $delai_somme_suivre = 0;
        foreach ($ticketSuivreEn as $tic) {
                $delai_somme_suivre += (int) Ticket::where('num_tic', $tic->num_tic)
                    ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                    ->where('statut','A Continué')
                    ->sum(DB::raw('TIME_TO_SEC(delai)'));
            }
        $delai_suivreFor = $delai_suivre + $delai_somme_suivre;
        $hours2 = floor($delai_suivreFor / 3600);
        $minutes2 = floor(($delai_suivreFor % 3600) / 60);
        $seconds2 = $delai_suivreFor % 60;
        // Formater la durée en format HH:MM:SS
        $delai_suivreF = sprintf("%02d:%02d:%02d", $hours2, $minutes2, $seconds2);

        //PAUSE
        $delai_pause = (int) Ticket::join('actions','tickets.actions_id','=','actions.id')
            ->where('actions.nom_action', 'pause')
            ->whereBetween('tickets.created_at', [$startOfMonth, $endOfMonth])
            ->sum(DB::raw('TIME_TO_SEC(delai)'));
        $hoursp = floor($delai_pause / 3600);
        $minutesp = floor(($delai_pause % 3600) / 60);
        $secondsp = $delai_pause % 60;
        // Formater la durée en format HH:MM:SS
        $delai_pauseF = sprintf("%02d:%02d:%02d", $hoursp, $minutesp, $secondsp);

        $nb_pause=Ticket::join('actions','tickets.actions_id','=','actions.id')
            ->where('actions.nom_action', 'pause')
            ->whereBetween('tickets.created_at', [$startOfMonth, $endOfMonth])
            ->count();

        //TICKET INACCESSIBLE
        $nb_inaccessible=Ticket::where('statut', '=', 'Inaccessible')
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
        ->count();
        $delai_inaccessible = (int) Ticket::where('statut', 'Inaccessible')
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
        ->sum(DB::raw('TIME_TO_SEC(delai)'));
        $hours3 = floor($delai_inaccessible / 3600);
        $minutes3 = floor(($delai_inaccessible % 3600) / 60);
        $seconds3 = $delai_inaccessible % 60;
        // Formater la durée en format HH:MM:SS
        $delai_inaccessibleF = sprintf("%02d:%02d:%02d", $hours3, $minutes3, $seconds3);
        $nb_inaccessible=Ticket::where('statut', '=', 'Inaccessible')
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
        ->count();


        //TICKET EN COURS
        $nb_encours=Ticket::where('statut', 'LIKE', 'En cours%')
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
        ->count();

        //%TAGE OK
        if($nb_ticketOK>0){
        $pourcOK1=($nb_ticketOK/$nb_tot)*100;
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

        // Obtenir la date au format "jj:mm:aaaa"
        $dateFormatteestartOfMonth = $startOfMonth->format('d/m/Y');
        $dateFormatteeendOfMonth = $endOfMonth->format('d/m/Y');

        //TESTER SI AUCUNE TICKET DANS CET MOIS POUR TOUS LES INTERVENANTS
        if($nb_tot<1){
            return redirect('/statglobalhebdo')->with('vide',"Aucune ticket de cet Mois");
        }
        else{
            return view('statistique.statglobalhebdo', [
                'nb_ticket' => $nb_ticketOK,
                'nb_bloque' => $nb_bloque,
                'nb_suivre' => $nb_suivre,
                'nb_inaccessible' => $nb_inaccessible,
                'nb_encours' => $nb_encours,
                'nb_pause'=>$nb_pause,
                'nb_prod'=>$nb_prod,
                'totalSumDelai' => $totalDuration,
                'delai_bloque'=> $delai_bloqueF,
                'delai_suivre'=> $delai_suivreF,
                'delai_inaccessible'=> $delai_inaccessibleF,
                'delai_pause'=>$delai_pauseF,
                'delai_prod'=> $delai_prodF,
                'pourcBloque'=> $pourcBloque,
                'pourcinaccessible'=> $pourcinaccessible,
                'pourcOK'=> $pourcOK,
                'pourcSuivre'=> $pourcSuivre,
                'pourcencours'=> $pourcencours,
                'pourcPause'=>$pourcPause,
                'pourcprod'=>$pourcprod,
                'startOfMonth'=> $dateFormatteestartOfMonth,
                'endOfMonth'=> $dateFormatteeendOfMonth,
                'weekNumber'=>$weekNumber,
                'nb_ticketOK_tri'=>$nb_ticketOK_tri
            ]);
        }

}
public function dash(){
    return view('admin.layout.admin-dashboard');
}

public function indexcreate(){
    return view('admin.login.create');
}
public function indexlogin(){
    return view('admin.login.login-admin');
}

public function create(Request $request){
    $admin=new Admin();
    $nom=$request->nom_admin;
    $email=$request->email_admin;
    $mdp=$request->mdp2;

    $admin->nom_admin=$nom;
    $admin->email_admin=$email;
    $admin->mdp_admin=$mdp;
    $admin->save();

    return redirect('/create-admin')->with('successCreate',"Votre Compte Admin a été Céer avec Succès");
}

public function connex(Request $request){
    $nom_admin=$request->nom_admin;
    $mdp=$request->mdp_admin;
    $admin =Admin::where('nom_admin',$nom_admin)
                    ->where('mdp_admin',$mdp)->first();
    if(!$admin){
        return redirect('/login-admin')->with('incorrect',"Votre compte est incorrect");
    }
    else{
        return redirect('/principale-admin');
    }
}

public function menu(){

    $currentMonth = Carbon::now()->month;

    $statistique = Ticket::join('intervenants', 'tickets.intervenants_id', '=', 'intervenants.id')
                        ->select('intervenants.nom_inter as nom', DB::raw('count(*) as nb_ok'))
                        ->where('statut', 'LIKE', 'OK%')
                        ->whereRaw('MONTH(tickets.created_at) = ?', [$currentMonth])
                        ->groupBy('nom')
                        ->get();
    $ticketOK=Ticket::whereRaw('MONTH(tickets.created_at) = ?', [$currentMonth])
                        ->where('statut','LIKE','OK%')
                        ->count();
    $ticketSuivr=Ticket::whereRaw('MONTH(tickets.created_at) = ?', [$currentMonth])
                        ->where('statut','LIKE','A Suivre%')
                        ->count();
    $ticketBloq=Ticket::whereRaw('MONTH(tickets.created_at) = ?', [$currentMonth])
                        ->where('statut','LIKE','Bloqué%')
                        ->count();
    $ticketInacces=Ticket::whereRaw('MONTH(tickets.created_at) = ?', [$currentMonth])
                        ->where('statut','LIKE','Inaccessible%')
                        ->count();
    return view('admin.menu.principale-admin',['statistique'=>$statistique,'ticketOK'=>$ticketOK,'ticketBloq'=>$ticketBloq,'ticketSuivr'=>$ticketSuivr,'ticketInacces'=>$ticketInacces]);
}

public function hebdo(){
    $intervenant = Intervenant::all();
    return view('admin.stat.hebdopers-admin',['intervenant' => $intervenant,]);
}
public function stathebdo(Request $request){
    $intervenant = Intervenant::all();
    $id=$request->inter;

    $nom_inter=Intervenant::whereId($id)->first()->nom_inter;

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
    ->where('intervenants_id',$id)
    ->where('statut','LIKE', 'OK%')
    ->whereBetween('tickets.created_at', [$startOfMonth, $endOfMonth])
    ->count();

    $delai_prod = (int) Ticket::join('actions','tickets.actions_id','actions.id')
    ->where('actions.nom_action', 'production')
    ->where('intervenants_id',$id)
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

    //TESTER SI AUCUNE TICKET DANS CET SEMAINE POUR L'INTERVENANT SPECIFIE
    if($nb_tot<1){
        return redirect('/stathebdo-admin')->with('vide',"Aucune ticket de cet Mois");
    }
    else{
        return view('admin.stat.hebdopers-admin', [
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
            'weekNumber'=>$weekNumber,
            'nom_inter'=>$nom_inter
        ]);
    }
}
public function index(){
    $intervenant = Intervenant::all();
    return view('admin.stat.mensuelpers-admin',['intervenant' => $intervenant,]);
}

public function statmensuel(Request $request){
        $intervenant = Intervenant::all();
        $id=$request->inter;

        $nom_inter=Intervenant::whereId($id)->first()->nom_inter;

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
        return redirect('/statmensuel-admin')->with('vide',"Aucune ticket de cet Mois");
    }
    else{
        return view('admin.stat.mensuelpers-admin', [
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
            'sumInterfileTimes'=>$sumInterfileTimes,
            'nom_inter'=>$nom_inter
        ]);
    }
}
public function mdpindex(){
    return view('admin.login.mdpoublie');
}
public function oublie(Request $request){
    $email = $request->mail;
        $compte=Admin::where('email_admin',$email)->first();

        if($compte !=null){
            try {
                $message2 = mt_rand(10000, 99999);
                $message="Votre code de validation est :".$message2;

                Mail::to($email)->send(new EnvoyerMessage($message));

                $mdp=$compte->mdp_admin;

                return view('admin.login.validationcode-admin',['code'=>$message2,'mdp'=>$mdp])->with('validmdp', "Prendre votre Email et saisir le code de validation");

            } catch (\Throwable $th) {
                return back()->with('erroremail', "Connectez vous sur un réseau Internet !");
            }
            }
        else{
            return back()->with('erroremail', "Votre addresse Email est incorrect,Veuillez  verifier puis réessayer S'il Vous Plaît!");
        }
    }

}
