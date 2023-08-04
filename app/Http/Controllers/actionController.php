<?php

namespace App\Http\Controllers;

use App\Models\Action;
use Illuminate\Http\Request;

class actionController extends Controller
{
    public function index(){
        $actions=Action::get();
        $intervenant = session('intervenant'); // Récupérer l'intervenant à partir de la session
        return view('action.actionListe',['actions'=>$actions,'intervenant'=>$intervenant]);
    }
    public function form(){
        return view('action.actionForm');
    }
    public function store(Request $request){
        $action=new Action;
        $action->nom_action=$request->nom_action;
        $action->save();
        return redirect('/action-liste')->with('success',"Action bien Enregistré");
    }
    public function update(Request $request,$id){
        $action=Action::whereId($id)->first();
        $action->nom_action=$request->nom_action;
        $action->save();
        return redirect('/action-liste')->with('successModif',"Modification réussite!");
    }
    public function supprimer($id){
        $action=Action::whereId($id)->first();
        try {
            $action->delete();
            return redirect('/action-liste')->with('successDelete',"Suppression réussite!");
        } catch (\Illuminate\Database\QueryException $e) {
            $error_message = "Une erreur est survenue lors de la suppression de l'action,cet Action est insupprimable ";
            return redirect('/action-liste')->with('errorDelete', $error_message);
        }

    }
}
