<?php

namespace App\Http\Controllers\public;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

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

     #Fonction de gestion des inscriptions pour les abonnes
    public function inscriptionAbonneAction(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nom' => 'required',
                'prenom' => 'required',
                'email' => 'required|email|max:255|unique:users',
                'password' => 'required',
                'telephone' => 'required',
                'adresse' => 'required',
                'preferences' => 'required',
            ],
            [
                'nom.required' => 'Le champ nom et prénom est requis.',
                'prenom.required' => 'Le champ nomcomplet et prénom est requis.',
                'email.required' => 'Le champ email est requis.',
                'email.email' => 'Veuillez entrer une adresse email valide.',
                'email.max' => 'L\'adresse email ne doit pas dépasser :max caractères.',
                'email.unique' => 'Cette adresse email est déjà utilisée.',
                'password.required' => 'Le champ mot de passe est requis.',
                'password.min' => 'Le mot de passe doit contenir au moins :min caractères.',
                'telephone.required' => 'Le champ telephone est requis.',
                'preferences.required' => 'Le champ preferences est requis.',
                'adresse.required' => 'Le champ adresse est requis.',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $abonne = User::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'password' => $request->password,
            'email' => $request->email,
            'adresse' => $request->adresse,
            'telephone' => $request->telephone,
            'preferences' => $request->preferences,
            'role' => "abonne",
        ]);

        $abonne->save();

        return redirect()->route('public.connexion')->with('success', 'Inscription réussie ! Connectez-vous maintenant.');
    }

    public function inscriptionAbonne(){
        return view('public.auth.inscription-abonne');
    }

    public function connexion(){
        return view('public.auth.connexion');
    }

    public function connexionAction(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|email',
                'password' => 'required',
            ],
            [
                'email.required' => 'Le champ email est requis.',
                'email.email' => 'Veuillez entrer une adresse email valide.',
                'password.required' => 'Le champ mot de passe est requis.',
            ]
        );

        //On retourne toutes les erreurs
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        

        // Vérifier si un utilisateur avec cet email existe
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return redirect()->back()
                ->withErrors(['login' => "Cet email n'a pas de compte"])
                ->withInput();
        }

        //On le connecte ici
        if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            return redirect()->back()
                ->withErrors(['login' => 'Mot de passe est incorrect'])
                ->withInput();
        }

        $request->session()->regenerate();

        //On voie sur quel page on dois le redirigé
        $user = Auth::user();
        $redirectRoute = '';

        if ($user->role == 'admin') {
            $redirectRoute = 'private.admintableaudebord';
        } elseif ($user->role == 'promoteur') {
            $redirectRoute = 'private.promoteurtableaudebord';
        } elseif ($user->role == 'abonne') {
            $redirectRoute = 'private.abonnetableaudebord';
        }

        if (!empty($redirectRoute)) {
            return redirect()->route($redirectRoute)->withMessage("Connexion réussie !");
        }
    }
}
