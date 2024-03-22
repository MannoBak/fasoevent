<?php

namespace App\Http\Controllers\private;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfilController extends Controller
{
    public function index(){
        return view('private.profil.index');
    }

    #Fonction de madification du profil
    public function editProfilAction(Request $request){

        {
            $user = Auth()->user();
    
            $user->nomcomplet = $request->nomcomplet;
            $user->nom = $request->nom;
            $user->prenom = $request->prenom;
            $user->telephone = $request->telephone;
            $user->siege = $request->siege;
            $user->email = $request->email;
            $user->adresse = $request->adresse;
            $user->activites = $request->activites;
            $user->preferences = $request->preferences;
    
            $user->save();
    
            return redirect()->back()->with('success', 'Profil edité avec succès');
        }
        return redirect()->back()->with('success', 'Profil édicté avec succès');
    }
}
