<?php

namespace App\Http\Controllers\public;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function inscriptionOption(){
        return view('public.auth.inscription-option');
    }

    #Fonction d'affichage du formulaire d'inscription promoteur
    public function inscriptionPromoteur(){
        return view('public.auth.inscription-promoteur');
    }

    #Fonction de gestion des inscriptions pour les promoteurs
    public function inscriptionPromoteurAction(Request $request){
        #dd($request->all(), $request->nomcomplet);  #Pour reccuper les donnees du formulaire d'inscription
        
        #Validation du formulaire avec les contraintes
        $validator = Validator::make(
            $request->all(),
            [
                'nomcomplet' => 'required',
                'email' => 'required|email|max:255|unique:users',
                'siege' => 'required',
                'activites' => 'required',
                'adresse' => 'required',
                'telephone' => 'required|min:8',
                'password' => 'required|min:4',
            ],
            [
                'nomcomplet.required' => 'Le champ nomcomplet est requis.',
                'email.required' => 'Le champ email est requis.',
                'email.email' => 'Veuillez entrer une adresse email valide.',
                'email.max' => 'L\'adresse email ne doit pas dépasser :max caractères.',
                'email.unique' => 'Cette adresse email est déjà utilisée.',
                'password.required' => 'Le champ mot de passe est requis.',
                'password.min' => 'Le mot de passe doit contenir au moins :min caractères.',
                'siege.required' => 'Le champ siege est requis.',
                'telephone.required' => 'Le champ telephone est requis.',
                'adresse.required' => 'Le champ adresse est requis.',
                'activites.required' => 'Le champ domaines d\'activites est requis.',
            ]
        );

        #Verifier tout n'est pas respecte, on reste sur la page
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $promoteur = User::create([  #creation d'un user
            'nomcomplet' => $request->nomcomplet,
            'email' => $request->email,
            'password' => $request->password,
            'siege' => $request->siege,
            'telephone' => $request->telephone,
            'activites' => $request->activites,
            'role' => "promoteur",
            'status' => "en_attente",
        ]);

        $promoteur->save();

        return redirect()->route('public.connexion')->with('success', 'Inscription réussie ! Veuillez vous connecter.');
    }


    public function inscriptionAbonne(){
        return view('public.auth.inscription-abonne');
    }

    public function connexion(){
        return view('public.auth.connexion');
    }
}
