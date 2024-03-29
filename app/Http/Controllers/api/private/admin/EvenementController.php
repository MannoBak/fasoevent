<?php

namespace App\Http\Controllers\api\private\admin;

use App\Http\Controllers\Controller;
use App\Models\Evenement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\str;

class EvenementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $data['evenements'] = $this->evenements();

            return response()->json([
                'status' => 'success',
                'message', "Liste des evenements.",
                'data' => $data
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Erreur serveur ! ' . $th->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Valider les données de la requête
            $validate = Validator::make($request->all(), [
                'nom' => 'required|string',
                'description' => 'required|string',
                'datedebut' => 'required',
                'datefin' => 'required',
                'activite_id' => 'required',
                'promoteur_id' => 'required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Erreur de validation du formulaire!',
                    'data' => $validate->errors(),
                ], 422);
            }

            // Créer une nouvelle activité
            $evenement = new Evenement();
            $evenement->nom = $request->nom;
            $evenement->description = $request->description;
            $evenement->datedebut = $request->datedebut;
            $evenement->datefin = $request->datefin;
            $evenement->lieu = $request->lieu;
            $evenement->is_active = false;
            $evenement->activite_id = $request->activite_id;
            $evenement->promoteur_id = $request->promoteur_id;

            if ($request->photo != null) {

                $photo_64 = $request->photo; //your base64 encoded data
                // $extension = explode('/', explode(':', substr($pdf_64, 0, strpos($pdf_64, ';')))[1])[1];   // .jpg .png .pdf
                $replace = substr($photo_64, 0, strpos($photo_64, ',') + 1);
                $file = str_replace($replace, '', $photo_64);
                $myImage = str_replace(' ', '+', $file);
                $filename = uniqid() . '.png';

                Storage::disk('public')->put('image/evements/' . $filename, base64_decode($myImage));
                $path = 'image/evements/' . $filename;

                $evenement->photo = $path;
            }

            $evenement->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Evenement crée avec succès.',
                'data' => $this->evenements()
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            // Récupérer l'activité spécifiée par son ID
            $evenement = Evenement::find($id);

            if (is_null($evenement)) {
                return response()->json([
                    'status' => 'failed',
                    'message' => "Evenement introuvable",
                ], 404);
            }

            $data['evenement'] = $evenement;

            return response()->json([
                'status' => 'success',
                'data' => $data
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Erreur serveur ! ' . $th->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, string $id)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    { {
            try {
                // Valider les données de la requête
                $validate = Validator::make($request->all(), [
                    'nom' => 'required|string',
                    'description' => 'required|string',
                    'datedebut' => 'required',
                    'datefin' => 'required',
                    'activite_id' => 'required',
                    'promoteur_id' => 'required',
                ]);

                if ($validate->fails()) {
                    return response()->json([
                        'status' => 'failed',
                        'message' => 'Erreur de validation du formulaire!',
                        'data' => $validate->errors(),
                    ], 422);
                }

                // dd($request->all());

                // Créer une nouvelle activité
                $evenement = Evenement::find($id);
                $evenement->nom = $request->nom;
                $evenement->description = $request->description;
                $evenement->datedebut = $request->datedebut;
                $evenement->datefin = $request->datefin;
                $evenement->lieu = $request->lieu;
                $evenement->isActive = false;
                $evenement->activite_id = $request->activite_id;
                $evenement->promoteur_id = $request->promoteur_id;

                if ($evenement->photo != null) {
                    Storage::disk('public')->delete($evenement->photo);
                }

                $photo_64 = $request->photo; //your base64 encoded data
                // $extension = explode('/', explode(':', substr($pdf_64, 0, strpos($pdf_64, ';')))[1])[1];   // .jpg .png .pdf
                $replace = substr($photo_64, 0, strpos($photo_64, ',') + 1);
                $file = str_replace($replace, '', $photo_64);
                $myImage = str_replace(' ', '+', $file);
                $filename = uniqid() . '.png';

                Storage::disk('public')->put('image/evements/' . $filename, base64_decode($myImage));
                $path = 'image/evements/' . $filename;

                $evenement->photo = $path;

                $evenement->save();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Evenement crée avec succès.',
                    'data' => $this->evenements()
                ], 201);
            } catch (\Throwable $th) {
                return response()->json([
                    'status' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Récupérer l'evenement spécifiée par son ID
            $evenement = Evenement::find($id);

            if (is_null($evenement)) {
                return response()->json([
                    'status' => 'failed',
                    'message' => "Evenement introuvable",
                ], 404);
            }

            Evenement::destroy($id);

            return response()->json([
                'status' => 'success',
                'message' => "Evenement supprimer avec succès.",
                'data' => $this->evenements()
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Erreur serveur ! ' . $th->getMessage()
            ], 500);
        }
    }

    public function evenements()
    {
        $evenements = Evenement::with(['promoteur', 'activite'])->orderBy('created_at', 'desc')->get();
        return $evenements;
    }
}
