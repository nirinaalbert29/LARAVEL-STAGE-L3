<?php

namespace App\Http\Controllers;

use App\Models\Intervenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class IntervenantController extends Controller
{
    function index(){
        $intervenants=Intervenant::get();
        $intervenant = session('intervenant'); // Récupérer l'intervenant à partir de la session
        return view('intervenant.intervenatListe',['intervenants'=>$intervenants,'intervenant'=>$intervenant]);
    }
    function affiche(){
        return view('intervenant.intervenantForm');
    }

    function ajout(Request $request){

        $validator = Validator::make($request->all(), [
        'email_i' => 'required|email|unique:intervenants,email_inter', // Assurez-vous que la colonne est correctement nommée
        // Ajoutez ici d'autres règles de validation pour les autres champs
    ], [
        'email_i.required' => 'L\'adresse e-mail est obligatoire.',
        'email_i.email' => 'L\'adresse e-mail doit être une adresse e-mail valide.',
        'email_i.unique' => 'L\'adresse e-mail existe déjà dans la base de données.',
        // Ajoutez ici d'autres messages personnalisés pour les autres règles de validation
    ]);

    // Vérification supplémentaire pour s'assurer que l'adresse email appartient au domaine "gmail.com"

    if ($validator->fails()) {
        return redirect('intervenants')
            ->withErrors($validator)
            ->withInput();
    }

        try{
            $intervenat=new Intervenant;
            $intervenat->nom_inter=$request->nom_i;
            $intervenat->prenom_inter=$request->prenom_i;
            $intervenat->email_inter=$request->email_i;
            if ($request->hasFile('photo_i')) {
                $file=$request->file('photo_i');
                $extention=$file->getClientOriginalExtension();
                $filename=time().'.'.$extention;
                $file->move('photos/',$filename);
                $intervenat->photo_inter=$filename;
                $intervenat->save();
                return redirect('intervenants')->with('successAjout','Ajout réussite!');
            }
            else{
                return redirect('intervenants')->with('errorFile', "Erreur de fichier , Réssayer dans un autre fichier");
            }
        } catch (\Throwable $e) {
            // En cas d'erreur, capturez l'exception et redirigez avec un message d'erreur
            $errorMessage = "Erreur de fichier , Réssayer de fichier de taille <8Mo";
            return redirect('intervenants')->with('errorFile', $errorMessage);
        }
    }
    function ajout_login(Request $request){
        $intervenat=new Intervenant;
        $intervenat->nom_inter=$request->nom_i;
        $intervenat->prenom_inter=$request->prenom_i;
        $intervenat->email_inter=$request->email_i;
        if ($request->hasFile('photo_i')) {
            $file=$request->file('photo_i');
            $extention=$file->getClientOriginalExtension();
            $filename=time().'.'.$extention;
            $file->move('photos/',$filename);
            $intervenat->photo_inter=$filename;
            $intervenat->save();
            return redirect('newcompte')->with('successAjout','Ajout Intervenant réussite!');
        }
        else{
            return redirect('intervenants')->with('errorFile', "Erreur de fichier , Réssayer dans un autre fichier");
        }
    }
    function update($id, Request $request)
{
    $intervenant = Intervenant::where('id', $id)->first();

    $intervenant->nom_inter = $request->nom_i;
    $intervenant->prenom_inter = $request->prenom_i;
    $intervenant->email_inter = $request->email_i;

    if ($request->hasFile('photo_i')) {
        $file = $request->file('photo_i');
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '.' . $extension;
        $file->move('photos/', $filename);
        $intervenant->photo_inter = $filename;
    }

    try {
        $intervenant->save();
        return redirect('/intervenants')->with('success', "Modification réussie !");
    } catch (\Exception $e) {
        return redirect('intervenants')->with('errorFile', "Erreur de fichier, Réessayez avec un autre fichier.");
    }
}

    function updatecompte($id,Request $request){
        $intervenant=Intervenant::where('id',$id)->first();
        $intervenant->nom_inter=$request->nom_i;
        $intervenant->prenom_inter=$request->prenom_i;
        $intervenant->email_inter=$request->email_i;
        if ($request->hasFile('photo_i')) {
            $file=$request->file('photo_i');
            $extention=$file->getClientOriginalExtension();
            $filename=time().'.'.$extention;
            $file->move('photos/',$filename);
            $intervenant->photo_inter=$filename;
        }
        try{
            $intervenant->save();
            $intervenant=Intervenant::whereId($id)->get();
            session(['intervenant' => $intervenant]); // Sauvegarder l'intervenant dans la session
            return back()->with('success',"la modification de compte a été réussite!");
        }catch (\Exception $e) {
            return back()->with('errorFile', "Erreur de fichier , Réssayer dans un autre fichier");
        }
    }
    function supprimer($id){
        $intervenant=Intervenant::whereId($id)->first();
        try {
            $intervenant->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            $error_message = "Une erreur est survenue lors de la suppression de l'intervenant,l'intervenant qui a déjà fait un ticket ne peut pas supprimer! ";
            return redirect('/intervenants')->with('errorDelete', $error_message);
        }
        return redirect('/intervenants')->with('successDelete', "Suppression Intervenant succes");
    }
}

