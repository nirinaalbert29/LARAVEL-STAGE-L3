<?php

namespace App\Http\Controllers;

use App\Models\Action;
use App\Models\Categorie;
use App\Models\Pompefunebre;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;

class suiteController extends Controller
{
    public function continue($id){
        $ticket=Ticket::whereId($id)->first();
        $tic=new Ticket();
        $num=$ticket->num_tic;
        $id_inter=$ticket->intervenants_id;
        $currentMonth = Carbon::now()->month;
        $nbr = Ticket::where('num_tic', $num)->where('intervenants_id',$id_inter)->whereRaw('MONTH(tickets.created_at) = ?', [$currentMonth])->count();
        $tic->categories_id=$ticket->categories_id;
        $tic->actions_id=$ticket->actions_id;
        $tic->type_projet=$ticket->type_projet;
        $tic->intervenants_id=$ticket->intervenants_id;
        $tic->nom_pompe=$ticket->nom_pompe;
        $tic->num_tic=$ticket->num_tic;
        $tic->lien_pompe=$ticket->lien_pompe;
        $tic->statut="En cours(".($nbr+1)."èm fois)";
        $tic->save();
        $ticket->statut="A Continué";
        $ticket->save();
        return redirect('ticket')->with('successContinue',"ticket continé avec Succé");
    }
    function suite(){
        $intervenant = session('intervenant');
        $pompe = Pompefunebre::all();
        $action = Action::all();
        $categorie = Categorie::all();
        if (!$intervenant) {
            echo 'tsy tonga ny intervenant, erreur';
        }

        $currentMonth = Carbon::now()->month;

        $suites = Ticket::join('actions', 'tickets.actions_id', '=', 'actions.id')
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
        ->where('statut', 'LIKE', 'En cours%')
        ->whereRaw('MONTH(tickets.created_at) = ?', [$currentMonth])
        ->get();
        return view('ticket.ticket', [
            'intervenant' => $intervenant,
            'suites' => $suites,
            'pompe' => $pompe,
            'action' => $action,
            'categorie' => $categorie
        ]);
    }
}
