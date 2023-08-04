<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class menuController extends Controller
{
    public function menu(){
        $intervenant = session('intervenant');
        $id=$intervenant->first()->id;

        $currentMonth = Carbon::now()->month;

        $statistique = Ticket::join('intervenants', 'tickets.intervenants_id', '=', 'intervenants.id')
                            ->select('intervenants.nom_inter as nom', DB::raw('count(*) as nb_ok'))
                            ->where('statut', 'LIKE', 'OK%')
                            ->whereRaw('MONTH(tickets.created_at) = ?', [$currentMonth])
                            ->groupBy('nom')
                            ->get();
        $ticketOK=Ticket::whereRaw('MONTH(tickets.created_at) = ?', [$currentMonth])
                            ->where('intervenants_id',$id)
                            ->where('statut','LIKE','OK%')
                            ->count();
        $ticketSuivr=Ticket::whereRaw('MONTH(tickets.created_at) = ?', [$currentMonth])
                            ->where('intervenants_id',$id)
                            ->where('statut','LIKE','A Suivre%')
                            ->count();
        $ticketBloq=Ticket::whereRaw('MONTH(tickets.created_at) = ?', [$currentMonth])
                            ->where('intervenants_id',$id)
                            ->where('statut','LIKE','Bloqué%')
                            ->count();
        $ticketInacces=Ticket::whereRaw('MONTH(tickets.created_at) = ?', [$currentMonth])
                            ->where('intervenants_id',$id)
                            ->where('statut','LIKE','Inaccessible%')
                            ->count();
        return view('menu.principale',['intervenant'=>$intervenant,'statistique'=>$statistique,'ticketOK'=>$ticketOK,'ticketBloq'=>$ticketBloq,'ticketSuivr'=>$ticketSuivr,'ticketInacces'=>$ticketInacces]);
    }
}
