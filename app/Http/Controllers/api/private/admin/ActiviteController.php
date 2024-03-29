<?php

namespace App\Http\Controllers\api\private\admin;

use App\Http\Controllers\Controller;
use App\Models\Activite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ActiviteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $activites = Activite::orderBy('created_at', 'DESC')->get();

            $data['activites'] = $activites;

            return response()->json([
                'status' => 'success',
                'message' => "Liste des activités",
                'data' => $data
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'Failed',
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), [
                'nom' => 'required|string'
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Erreur de validation du formulaire!',
                    'data' => $validate->errors(),
                ], 422);
            }
            $activite = Activite::create([
                'nom' => $request->nom
            ]);

            $data['activite'] = $activite;

            return response()->json([
                'status' => 'success',
                'message' => "Activité créée avec succès",
                'data' => $data,
            ], 201);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'Failed',
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
            $activite = Activite::find($id);

            if (is_null($activite)) {
                return response()->json([
                    'status' => 'failed',
                    'message' => "Domaine d'activité introuvable",
                ], 404);
            }

            $data['activite'] = $activite;

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
    public function edit(string $id)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $validate = Validator::make($request->all(), [
                'nom' => 'required|string'
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Erreur de validation du formulaire!',
                    'data' => $validate->errors(),
                ], 422);
            }

            $activite = Activite::find($id);

            if (is_null($activite)) {
                return response()->json([
                    'status' => 'failed',
                    'message' => "Domaine d'activité introuvable",
                ], 404);
            }

            $activite->update($request->all());

            $response = [
                'status' => 'success',
                'message' => 'Product is updated successfully.',
                'data' => $this->activites(),
            ];

            return response()->json($response, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Erreur serveur ! ' . $th->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Récupérer l'activité spécifiée par son ID
            $activite = Activite::find($id);

            if (is_null($activite)) {
                return response()->json([
                    'status' => 'failed',
                    'message' => "Domaine d'activité introuvable",
                ], 404);
            }

            Activite::destroy($id);
            return response()->json([
                'status' => 'success',
                'message' => "Domain d'activité supprimé avec succès.",
                'data' => $this->activites()
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Erreur serveur ! ' . $th->getMessage()
            ], 500);
        }
    }

    public function activites()
    {
        $domaine_activites = Activite::orderBy('created_at', 'desc')->get();
        // $domaine_activites = DB::select('SELECT * FROM activites ORDER BY created_at DESC');

        return $domaine_activites;
    }
}