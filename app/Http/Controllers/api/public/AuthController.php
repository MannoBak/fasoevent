<?php

namespace App\Http\Controllers\api\public;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //Gestion de l'inscription promoteur
    public function inscriptionPromoteur(Request $request){
        try {
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
    
            $user = User::where('email', $request->email)->first();
    
            if($user){
                return response()->json([
                    'status'=> 'Failed',
                    'message'=> 'Cet email existe déjà',                
                ], 400);
            }
    
            if ($validator->fails()){
                return response()->json([
                    'status'=> 'Failed',
                    'message'=> 'Erreur de validation de formulaire',                
                ], 422);
            }
    
            $promoteur = User::create([  #creation d'un user
                'nomcomplet' => $request->nomcomplet,
                'email' => $request->email,
                'password' => $request->password,
                'siege' => $request->siege,
                'adresse' => $request->adresse,
                'telephone' => $request->telephone,
                'activites' => $request->activites,
                'role' => "promoteur",
                'status' => "en_attente",
            ]);
    
            $promoteur->save();
    
            $data['promoteur'] = $promoteur;
    
            return response()->json([
                'status'=> 'Success',
                'message'=> 'Inscription réussi !!!',                
                'data'=> $data,                
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'Failed',
                'message' => $th->getMessage()
            ], 500);
        }
    }

    //Inscription Abonne
    public function inscriptionAbonne(Request $request){
        try {
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
    
            $user = User::where('email', $request->email)->first();
    
            if($user){
                return response()->json([
                    'status'=> 'Failed',
                    'message'=> 'Cet email existe déjà',                
                ], 400);
            }
    
            if ($validator->fails()){
                return response()->json([
                    'status'=> 'Failed',
                    'message'=> 'Erreur de validation de formulaire',                
                ], 422);
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
    
            $data['abonne'] = $abonne;
    
            return response()->json([
                'status'=> 'Success',
                'message'=> 'Inscription réussi !!!',                
                'data'=> $data,                
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'Failed',
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function connexion(Request $request){
        try {
            $validate = Validator::make($request->all(), [
                'email' => 'required|string|email',
                'password' => 'required|string'
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Erreur de validation du formulaire!',
                    'data' => $validate->errors(),
                ], 422);
            }
            
            // On verifie si l'email existe ou les donné ne sont pas correct
            $user = User::where('email', $request->email)->first();
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Email ou mot de passe incorrect !'
                ], 401);
            }

            $data['token'] = $user->createToken($request->email)->plainTextToken;
            $data['user'] = $user;

            $response = [
                'status' => 'success',
                'message' => 'Connexion reussis !!!.',
                'data' => $data,
            ];

            return response()->json($response, 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'Failed',
                'message' => $th->getMessage()
            ], 500);
        }
    }

    //Deconnection
    public function deconnection(Request $request)
    {
        auth()->user()->tokens()->delete();
        return response()->json([
            'status' => 'success',
            'message' => "Vous etes deconnecté avec succès !"
        ], 200);
    }
}
