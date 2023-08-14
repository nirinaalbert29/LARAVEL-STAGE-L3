<?php

namespace App\Http\Controllers;

use App\Imports\CategorieImport;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class categorieController extends Controller
{
    public function form(){
        $categories=Categorie::all();
        $admin = session('admin');
        $intervenant = session('intervenant'); // Récupérer l'intervenant à partir de la session
        return view('categorie.categorie',['categories'=>$categories,'intervenant'=>$intervenant,'admin'=>$admin]);
    }
    public function store(Request $request){
        $categorie=new Categorie;
        $categorie->code_cat=$request->code_cat;
        $categorie->nom_cat=$request->nom_cat;
        $categorie->save();
        return redirect('/categorie')->with('successAjout',"Ajout Succè");
    }
    public function update($id,Request $request){
        $categorie = Categorie::whereId($id)->first();
        $categorie->code_cat=$request->code_cat;
        $categorie->nom_cat=$request->nom_cat;
        $categorie->save();
        return redirect('/categorie')->with('successModif',"Modification réussite");
    }
    public function supprimer($id){
        $categorie=Categorie::whereId($id)->first();
        try {
            $categorie->delete();
            return redirect('/categorie')->with('successDelete',"Suppression réussite!");
        } catch (\Illuminate\Database\QueryException $e) {
            $error_message = "Une erreur est survenue lors de la suppression de categorie,cet Categorie est insupprimable";
            return redirect('/categorie')->with('errorDelete', $error_message);
        }

    }

    public function importExcel(Request $request)
{
    try {
        $file = $request->file('excel_file');

        Excel::import(new CategorieImport, $file);

        return redirect('/categorie')->with('import', "importation Réussite");
    } catch (\Throwable $th) {
        return redirect('/categorie')->with('errorImport', "Le fichier doit contenir 2 colonnes, l'en tête de 1er doit nommer 'code_cat' et le 2ème doit 'nom_cat'");
    }

}
}
