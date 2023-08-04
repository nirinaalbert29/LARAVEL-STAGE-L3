<?php

namespace App\Http\Controllers;

use Illuminate\Support\Carbon;
use App\Models\Action;
use App\Models\Categorie;
use App\Models\Ticket;
use DateTime;
use Illuminate\Http\Request;



class ticketController extends Controller
{

    public function index()
{
    $intervenant = session('intervenant');
    $id=$intervenant->first()->id;

    $currentMonth = Carbon::now()->month;

    if (!$intervenant) {
        echo 'tsy tonga ny intervenant, erreur';
    }

    $action = Action::all();
    $categorie = Categorie::all();
    $pauses= Ticket::join('actions', 'tickets.actions_id', '=', 'actions.id')
    ->select(
        'tickets.num_tic as num_tic',
        'tickets.type_projet as type_projet',
        'tickets.id as id',
        'tickets.created_at as created_at',
        'tickets.dateHeure_fin as dateHeure_fin',
        'tickets.delai as delai',
        'tickets.statut as statut',
        'tickets.observation as observation',
        'actions.nom_action as nom_action',
        'tickets.nom_pompe as nom_pompe',
        'tickets.lien_pompe as lien_pompe',
        'tickets.intervenants_id as intervenants_id'
    )
    ->where('intervenants_id', '=', $id)
    ->where('statut', 'LIKE', 'en cours%')
    ->whereIn('nom_action',[ 'Pause','Panne de courant'])
    ->whereRaw('MONTH(tickets.created_at) = ?', [$currentMonth])
    ->get();

    $reunions= Ticket::join('actions', 'tickets.actions_id', '=', 'actions.id')
    ->select(
        'tickets.num_tic as num_tic',
        'tickets.type_projet as type_projet',
        'tickets.id as id',
        'tickets.created_at as created_at',
        'tickets.dateHeure_fin as dateHeure_fin',
        'tickets.delai as delai',
        'tickets.statut as statut',
        'tickets.observation as observation',
        'actions.nom_action as nom_action',
        'tickets.nom_pompe as nom_pompe',
        'tickets.lien_pompe as lien_pompe',
        'tickets.intervenants_id as intervenants_id'
    )
    ->where('intervenants_id', '=', $id)
    ->where('statut', 'LIKE', 'en cours%')
    ->where('nom_action','Réunion')
    ->whereRaw('MONTH(tickets.created_at) = ?', [$currentMonth])
    ->get();

    $tickets = Ticket::join('actions', 'tickets.actions_id', '=', 'actions.id')
        ->join('categories', 'tickets.categories_id', '=', 'categories.id')
        ->select(
            'tickets.num_tic as num_tic',
            'tickets.type_projet as type_projet',
            'tickets.id as id',
            'tickets.created_at as created_at',
            'tickets.dateHeure_fin as dateHeure_fin',
            'tickets.delai as delai',
            'tickets.statut as statut',
            'tickets.observation as observation',
            'actions.nom_action as nom_action',
            'tickets.nom_pompe as nom_pompe',
            'tickets.lien_pompe as lien_pompe',
            'categories.nom_cat as nom_cat',
            'tickets.intervenants_id as intervenants_id'
        )
        ->where('intervenants_id', '=', $id)
        ->where('statut', 'LIKE', 'en cours%')
        ->whereNotIn('nom_action',[ 'Pause','Panne de courant','Réunion'])
        ->whereRaw('MONTH(tickets.created_at) = ?', [$currentMonth])
        ->get();

    return view('ticket.ticket', [
        'intervenant' => $intervenant,
        'tickets' => $tickets,
        'action' => $action,
        'categorie' => $categorie,
        'pauses' => $pauses,
        'reunions'=>$reunions
    ]);
}

public function store(Request $request)
{
    $intervenantId = $request->id_inter;

    // Obtenez le mois actuel (format 'm')
    $currentMonth = date('m');

    // Recherchez le dernier ticket pour le mois en cours et l'intervenant spécifié
    $lastTicketNum = Ticket::where('intervenants_id', $intervenantId)
                    ->whereMonth('created_at', $currentMonth)
                    ->max('num_tic');
    $lastTicket = Ticket::where('intervenants_id', $intervenantId)
                    ->whereMonth('created_at', $currentMonth)
                    ->Where('num_tic',$lastTicketNum)
                    ->first();

    if ($lastTicket) {
        // Récupérez le numéro de ticket du dernier ticket enregistré
        $lastTicketNumber = $lastTicketNum;

        // Vérifiez si le dernier ticket a été ajouté dans le même mois
        $isSameMonth = $lastTicket->created_at->isSameMonth(now());

        if ($isSameMonth) {
            // Incrémentez le numéro de ticket existant pour obtenir le nouveau numéro de ticket
            $newTicketNumber = $lastTicketNumber + 1;
        } else {
            // C'est un nouveau mois, attribuez le numéro de ticket 1
            $newTicketNumber = 1;
        }
    } else {
        // C'est le premier ticket pour ce mois et cet intervenant, attribuez le numéro de ticket 1
        $newTicketNumber = 1;
    }

    // Enregistrez le ticket avec le nouveau numéro de ticket
    $ticket = new Ticket();
    $ticket->intervenants_id = $intervenantId;
    $ticket->actions_id = $request->action;
    $ticket->type_projet = $request->type_projet;
    $ticket->categories_id = $request->categ;
    $ticket->nom_pompe = $request->nom_pompe;
    $ticket->lien_pompe = $request->lien_pompe;
    $ticket->num_tic = $newTicketNumber;
    $ticket->save();

    return redirect('ticket')->with('successAjout', "Ticket bien Enregistré avec succès");
}

public function inaccess(){
        $intervenant = session('intervenant');
        $id = $intervenant->first()->id;
        if (!$intervenant) {
            echo 'tsy tonga ny intervenant, erreur';
        }

        $currentMonth = Carbon::now()->month;

        $action = Action::all();
        $categorie = Categorie::all();

        $tickets = Ticket::join('actions', 'tickets.actions_id', '=', 'actions.id')
            ->join('categories', 'tickets.categories_id', '=', 'categories.id')
            ->select(
                'tickets.num_tic as num_tic',
                'tickets.type_projet as type_projet',
                'tickets.id as id',
                'tickets.created_at as created_at',
                'tickets.dateHeure_fin as dateHeure_fin',
                'tickets.delai as delai',
                'tickets.statut as statut',
                'tickets.observation as observation',
                'actions.nom_action as nom_action',
                'tickets.nom_pompe as nom_pompe',
                'tickets.lien_pompe as lien_pompe',
                'categories.nom_cat as nom_cat',
                'tickets.intervenants_id as intervenants_id'
            )
            ->where('intervenants_id', '=', $id)
            ->where('statut','LIKE','Inaccessible%')
            ->whereRaw('MONTH(tickets.created_at) = ?', [$currentMonth])
            ->get();
        return view('ticket.ticketinnaccess', [
            'intervenant' => $intervenant,
            'tickets' => $tickets,
            'action' => $action,
            'categorie' => $categorie
        ]);
}


public function update($id,Request $request){
    $ticket=Ticket::whereId($id)->first();
    $heureSysteme = new DateTime(); // Récupère l'heure système actuelle
    $dateTime1 = Carbon::parse($heureSysteme);
    $date_debut=$request->date_debut;
    $dateTime2 = Carbon::parse($date_debut);
    $diff = $dateTime1->diff($dateTime2);
    $difftemps = $diff->format('%H:%I:%S');
    //$ticket->statut=$request->statut;
    $ticket->observation=$request->observation;
    $ticket->dateHeure_fin=$heureSysteme;
    $ticket->delai=$difftemps;
    //$ex_stat=$ticket->statut;
    $new_stat=$request->statut;
    $num=$request->num_tic;
    $id_inter=$ticket->intervenants_id;
    $currentMonth = Carbon::now()->month;
    $nbr = Ticket::where('num_tic', $num)->whereRaw('MONTH(tickets.created_at) = ?', [$currentMonth])->where('intervenants_id',$id_inter)->count();
    //$nbr_suivre = Ticket::where('num_tic', $num)->whereIn('statut', ["A suivre", "Bloqué"])->count();

    if($new_stat == "OK" && $nbr>1){
        $ticket->statut="OK(En ".($nbr)."fois)";
        $ticket->save();
        return redirect('/ticketok')->with('successM',"Ticket Terminé avec Succè!");
    }
    elseif($new_stat == "OK" && $nbr==1){
        $ticket->statut="OK";
        $ticket->save();
        return redirect('/ticketok')->with('successM',"Ticket Terminé avec Succè!");
    }
    elseif($new_stat == "A Suivre" && $nbr==1){
        $ticket->statut="A Suivre";
        $ticket->save();
        return redirect('/ticketsuivre')->with('successM',"Ticket Terminé avec Succè!");
    }
    elseif($new_stat == "Bloqué" && $nbr==1){
        $ticket->statut="Bloqué";
        $ticket->save();
        return redirect('/ticketsuivre')->with('successM',"Ticket Terminé avec Succè!");
    }
    elseif($new_stat == "A Suivre" && $nbr>1){
        $ticket->statut="A Suivre(".($nbr)."èm fois)";
        $ticket->save();
        return redirect('/ticketsuivre')->with('successM',"Ticket Terminé avec Statut=A Suivre!");
    }
    elseif($new_stat == "Bloqué" && $nbr>1){
        $ticket->statut="Bloqué(".($nbr)."èm fois)";
        $ticket->save();
        return redirect('/ticketsuivre')->with('successM',"Ticket Terminé avec Statut=A Suivre!");
    }
    elseif($new_stat == "Inaccessible"){
        $ticket->statut="Inaccessible";
        $ticket->save();
        return redirect('/ticketInaccessible')->with('successM',"Ticket Terminé  avec Statut=Inaccessible!");
    }

    //return redirect('/ticket')->with('successM',"Ticket Modifié avec Succè!");
}


public  function ticketok(){
    $intervenant = session('intervenant');
    $id = $intervenant->first()->id;
    if (!$intervenant) {
        echo 'tsy tonga ny intervenant, erreur';
    }

    $currentMonth = Carbon::now()->month;

    $action = Action::all();
    $categorie = Categorie::all();

    $tickets = Ticket::join('actions', 'tickets.actions_id', '=', 'actions.id')
        ->join('categories', 'tickets.categories_id', '=', 'categories.id')
        ->select(
            'tickets.num_tic as num_tic',
            'tickets.type_projet as type_projet',
            'tickets.id as id',
            'tickets.created_at as created_at',
            'tickets.dateHeure_fin as dateHeure_fin',
            'tickets.delai as delai',
            'tickets.statut as statut',
            'tickets.observation as observation',
            'actions.nom_action as nom_action',
            'tickets.nom_pompe as nom_pompe',
            'tickets.lien_pompe as lien_pompe',
            'categories.nom_cat as nom_cat',
            'tickets.intervenants_id as intervenants_id'
        )
        ->where('intervenants_id', '=', $id)
        ->where('statut','LIKE','OK%')
        ->whereNotIn('nom_action',[ 'Pause','Panne de courant','Réunion'])
        ->whereRaw('MONTH(tickets.created_at) = ?', [$currentMonth])
        ->get();
    $pauses= Ticket::join('actions', 'tickets.actions_id', '=', 'actions.id')
        ->select(
            'tickets.num_tic as num_tic',
            'tickets.type_projet as type_projet',
            'tickets.id as id',
            'tickets.created_at as created_at',
            'tickets.dateHeure_fin as dateHeure_fin',
            'tickets.delai as delai',
            'tickets.statut as statut',
            'tickets.observation as observation',
            'actions.nom_action as nom_action',
            'tickets.nom_pompe as nom_pompe',
            'tickets.lien_pompe as lien_pompe',
            'tickets.intervenants_id as intervenants_id'
        )
        ->where('intervenants_id', '=', $id)
        ->where('statut','OK')
        ->whereIn('nom_action',[ 'Pause','Réunion','Panne de courant'])
        ->whereRaw('MONTH(tickets.created_at) = ?', [$currentMonth])
        ->get();
    return view('ticket.ticketok', [
        'intervenant' => $intervenant,
        'tickets' => $tickets,
        'action' => $action,
        'categorie' => $categorie,
        'pauses' => $pauses
    ]);
}
public function ticketsuivre()
{
    $intervenant = session('intervenant');

    if (!$intervenant) {
        echo 'tsy tonga ny intervenant, erreur';
    }

    $currentMonth = Carbon::now()->month;

    $action = Action::all();
    $categorie = Categorie::all();

    $tickets = Ticket::join('actions', 'tickets.actions_id', '=', 'actions.id')
        ->join('categories', 'tickets.categories_id', '=', 'categories.id')
        ->select(
            'tickets.num_tic as num_tic',
            'tickets.type_projet as type_projet',
            'tickets.id as id',
            'tickets.created_at as created_at',
            'tickets.dateHeure_fin as dateHeure_fin',
            'tickets.delai as delai',
            'tickets.statut as statut',
            'tickets.observation as observation',
            'actions.nom_action as nom_action',
            'tickets.nom_pompe as nom_pompe',
            'tickets.lien_pompe as lien_pompe',
            'categories.nom_cat as nom_cat',
            'tickets.intervenants_id as intervenants_id'
        )
        ->where('intervenants_id', '=', $intervenant->first()->id)
        ->where(function ($query) {
            $query->where('statut', 'LIKE', 'A suivre%')
                ->orWhere('statut', 'LIKE', 'Bloqué%');
            })
        ->whereRaw('MONTH(tickets.created_at) = ?', [$currentMonth])
        ->get();

    return view('ticket.ticketsuivre', [
        'intervenant' => $intervenant,
        'tickets' => $tickets,
        'action' => $action,
        'categorie' => $categorie
    ]);
}

public function ticketbloque()
{
    $intervenant = session('intervenant');

    if (!$intervenant) {
        echo 'tsy tonga ny intervenant, erreur';
    }

    $currentMonth = Carbon::now()->month;

    $action = Action::all();
    $categorie = Categorie::all();

    $tickets = Ticket::join('actions', 'tickets.actions_id', '=', 'actions.id')
        ->join('categories', 'tickets.categories_id', '=', 'categories.id')
        ->select(
            'tickets.num_tic as num_tic',
            'tickets.type_projet as type_projet',
            'tickets.id as id',
            'tickets.created_at as created_at',
            'tickets.dateHeure_fin as dateHeure_fin',
            'tickets.delai as delai',
            'tickets.statut as statut',
            'tickets.observation as observation',
            'actions.nom_action as nom_action',
            'tickets.nom_pompe as nom_pompe',
            'tickets.lien_pompe as lien_pompe',
            'categories.nom_cat as nom_cat',
            'tickets.intervenants_id as intervenants_id'
        )
        ->where('intervenants_id', '=', $intervenant->first()->id)
        ->where('statut','=','Bloqué')
        ->whereRaw('MONTH(tickets.created_at) = ?', [$currentMonth])
        ->get();

    return view('ticket.ticketbloque', [
        'intervenant' => $intervenant,
        'tickets' => $tickets,
        'action' => $action,
        'categorie' => $categorie
    ]);
}

public function global(){
    $intervenant = session('intervenant');
    $id=$intervenant->first()->id;
    if (!$intervenant) {
        echo 'tsy tonga ny intervenant, erreur';
    }

    $currentMonth = Carbon::now()->month;

    $action = Action::all();
    $categorie = Categorie::all();

    $tickets = Ticket::join('actions', 'tickets.actions_id', '=', 'actions.id')
        ->join('categories', 'tickets.categories_id', '=', 'categories.id')
        ->select(
            'tickets.num_tic as num_tic',
            'tickets.type_projet as type_projet',
            'tickets.id as id',
            'tickets.created_at as created_at',
            'tickets.dateHeure_fin as dateHeure_fin',
            'tickets.delai as delai',
            'tickets.statut as statut',
            'tickets.observation as observation',
            'actions.nom_action as nom_action',
            'tickets.nom_pompe as nom_pompe',
            'tickets.lien_pompe as lien_pompe',
            'categories.nom_cat as nom_cat',
            'tickets.intervenants_id as intervenants_id'
        )
        ->where('intervenants_id', '=', $id)
        ->whereNotIn('nom_action',[ 'Pause','Panne de courant','Réunion'])
        ->whereRaw('MONTH(tickets.created_at) = ?', [$currentMonth])
        ->get();

     $pauses= Ticket::join('actions', 'tickets.actions_id', '=', 'actions.id')
        ->select(
            'tickets.num_tic as num_tic',
            'tickets.type_projet as type_projet',
            'tickets.id as id',
            'tickets.created_at as created_at',
            'tickets.dateHeure_fin as dateHeure_fin',
            'tickets.delai as delai',
            'tickets.statut as statut',
            'tickets.observation as observation',
            'actions.nom_action as nom_action',
            'tickets.nom_pompe as nom_pompe',
            'tickets.lien_pompe as lien_pompe',
            'tickets.intervenants_id as intervenants_id'
        )
        ->where('intervenants_id', '=', $id)
        ->whereIn('nom_action',[ 'Pause','Réunion','Panne de courant'])
        ->whereRaw('MONTH(tickets.created_at) = ?', [$currentMonth])
        ->get();

    return view('ticket.ticketglobal', [
        'intervenant' => $intervenant,
        'tickets' => $tickets,
        'action' => $action,
        'categorie' => $categorie,
        'pauses' => $pauses
    ]);
}

public function termine($id){
    $ticket=Ticket::whereId($id)->first();
    $heureSysteme = new DateTime(); // Récupère l'heure système actuelle
    $dateTime1 = Carbon::parse($heureSysteme);
    $date_debut=$ticket->created_at;
    $dateTime2 = Carbon::parse($date_debut);
    $diff = $dateTime1->diff($dateTime2);
    $difftemps = $diff->format('%H:%I:%S');
    //$ticket->statut=$request->statut;
    $ticket->dateHeure_fin=$heureSysteme;
    $ticket->delai=$difftemps;
    $ticket->statut='OK';
    $ticket->save();
    return redirect('/ticketok')->with('successM',"Ticket Terminé avec Succès");
}

    public function reunionUpdate(Request $request, $id)
        {
            try {
                // Récupérer le ticket en fonction de l'ID passé dans l'URL
                $ticket = Ticket::findOrFail($id);

                // Récupérer l'observation depuis le corps de la requête
                $observation = $request->input('observation');

                $heureSysteme = new DateTime(); // Récupère l'heure système actuelle

                $dateTime1 = Carbon::parse($heureSysteme);
                $date_debut=$ticket->created_at;
                $dateTime2 = Carbon::parse($date_debut);
                $diff = $dateTime1->diff($dateTime2);
                $difftemps = $diff->format('%H:%I:%S');
                //$ticket->statut=$request->statut;
                $ticket->dateHeure_fin=$heureSysteme;
                $ticket->delai=$difftemps;
                $ticket->statut='OK';
                // Mettre à jour l'observation du ticket
                $ticket->observation = $observation;
                $ticket->save();

                return redirect('/ticketok')->with('successM',"Réunion terminer avec succès");

            } catch (\Exception $e) {
                // En cas d'erreur, répondre avec un message d'erreur au format JSON
                return response()->json(['error' => 'Une erreur est survenue lors de la mise à jour de la réunion.']);
            }
        }
}

