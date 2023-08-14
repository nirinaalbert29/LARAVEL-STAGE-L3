<?php

namespace App\Http\Controllers;

use App\Models\Action;
use App\Models\Intervenant;
use App\Models\Ticket;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;

class exportController extends Controller
{
    public function index(){
        $admin = session('admin');
        $intervenant = Intervenant::all();
        return view('export.export',['intervenant'=>$intervenant,'admin'=>$admin]);
    }
    public function export(Request $request){
            $id=$request->inter;
            $month = (int)$request->month;
            $year = (int)$request->year;

            $intervenant = Intervenant::all();

            $nom_inter=Intervenant::whereId($id)->first()->nom_inter;

            $startOfMonth = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endOfMonth = Carbon::createFromDate($year, $month, 1)->endOfMonth();

            $tickets = Ticket::join('actions', 'tickets.actions_id', '=', 'actions.id')
        ->join('categories', 'tickets.categories_id', '=', 'categories.id')
        ->join('intervenants','tickets.intervenants_id','=','intervenants.id')
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
            'intervenants.nom_inter as intervenants_id'
        )
        ->where('intervenants_id', '=', $id)
        ->whereBetween('tickets.created_at', [$startOfMonth, $endOfMonth])
        ->get();
    $pauses= Ticket::join('actions', 'tickets.actions_id', '=', 'actions.id')
    ->join('intervenants','tickets.intervenants_id','=','intervenants.id')
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
            'intervenants.nom_inter as intervenants_id'
        )
        ->where('intervenants_id', '=', $id)
        ->whereBetween('tickets.created_at', [$startOfMonth, $endOfMonth])
        ->whereIn('nom_action',[ 'Pause','Réunion','Panne de courant'])
        ->get();

        $moisAnnee = $startOfMonth->format('m/Y');

        //NBR DE TICKET TOTAL POUR L'INTERVENANT SPECIFIE POUR CET SEMAINE
        $nb_tot=Ticket::where('intervenants_id', $id)
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
        ->count();
        if($nb_tot<1){
            return redirect('/export')->with('vide',"Aucune ticket de cet Mois");
        }
        else{
            $admin = session('admin');
            $maxTicketNum = Ticket::where('intervenants_id', $id)
                    ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                    ->max('num_tic'); 
            return view('export.export',[
                'admin'=>$admin,
                'tickets'=>$tickets,
                'pauses'=>$pauses,
                'nb_ticket_tot'=>$maxTicketNum,
                'nom_inter'=>$nom_inter,
                'startOfMonth'=>$moisAnnee,
                'intervenant'=>$intervenant
        ]);
        }
    }
}
