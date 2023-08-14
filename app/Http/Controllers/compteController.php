<?php

namespace App\Http\Controllers;

use App\Mail\EnvoyerMessage;
use App\Models\Compte;
use App\Models\Intervenant;
use App\Models\Ticket;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class compteController extends Controller
{
    public function index(){
        $intervenants=Intervenant::all();
        return view('compte.newcompte',['intervenants'=>$intervenants]);
    }
    public function create(Request $request){
        $email=$request->interv;
        $mdp2=$request->mdp2;
        $compte=new Compte();
        $intervervenant=Intervenant::where('email_inter',$email)->first();
        if($intervervenant==null){
            return redirect('/newcompte')->with('incorrect',"Votre addresse email est incorrect, veuillez saisir l\'email déjà enregistrer par l'Admin");
        }
        else{

            $compte->intervenants_id=$intervervenant->id;
            $compte->mdp=$mdp2;
            $compte->save();
            return redirect('/newcompte')->with('successCreate',"Votre compte est crée avec succè");
        }
    }
    public function connection(){
        $intervenants=Intervenant::all();
        return view('compte.login',['intervenants'=>$intervenants]);
    }

    public function login(Request $request){
        $intervenan=Intervenant::where('nom_inter',$request->interv)->first();
        $mdp=$request->mdp;
        if($intervenan==null){
            return redirect('/')->with('incorrect',"Le nom de l'intervenant est incorrect,veuillez verifier ou créer nouveau compte");
        }
        else{
            $interv=$intervenan->id;
            $compte =Compte::where('intervenants_id',$interv)
                            ->where('mdp',$mdp)->first();
            if($compte==null){
                return redirect('/')->with('incorrect',"Votre mot de passe est incorrect");
            }
            else{
                $intervenant=Intervenant::whereId($interv)->get();
                session(['intervenant' => $intervenant]); // Sauvegarder l'intervenant dans la session

                return redirect('/principale');
            }
        }

    }
    public function mdpindex(){
        return view('compte.motdepasseoublie');
    }
    public function oublie(Request $request){
        $email = $request->mail;
        $intervenant=Intervenant::where('email_inter',$email)->first();

        if($intervenant !=null){
            $compte=Compte::where('intervenants_id',$intervenant->id)->first();

            if($compte !=null){
                try {
                    $message2 = mt_rand(10000, 99999);
                    $message="Votre code de validation est :".$message2;

                    Mail::to($email)->send(new EnvoyerMessage($message));

                    $mdp=$compte->mdp;

                    return view('compte.validationmdp',['code'=>$message2,'mdp'=>$mdp])->with('validmdp', "Prendre votre Email et saisir le code de validation");

                } catch (\Throwable $th) {
                    return back()->with('erroremail', "Connectez vous sur un réseau Internet !");
                }
                }
            else{
                return back()->with('erroremail', "Votre addresse Email est incorrect,Vous n'avez pas Encore de Compte pour votre adresse Email!");
            }
        }
        else{
            return back()->with('erroremail', "Votre addresse Email est incorrect,Veuillez  verifier S'il Vous Plaît!");
        }
    }
    public function changemdp(){
        $intervenant = session('intervenant');
        $id=$intervenant->first()->id;
        $compte=Compte::where('intervenants_id',$id)->first();
        return view('compte.changemdp_inter',['intervenant'=>$intervenant,'compte'=>$compte]);
    }
    public function update_mdp_inter($id,Request $request){
        $compte=Compte::where('intervenants_id',$id)->where('mdp',$request->mdp_actuel)->first();
        $compte->mdp=$request->mdp_i;
        $compte->save();
        return back()->with('successmodifCompte',"La modification de compte de l'intervenant est réussite");
    }
}
