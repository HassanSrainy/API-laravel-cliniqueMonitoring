<?php

namespace App\Http\Controllers;

use App\Models\Floor;
use App\Models\Clinique;
use Illuminate\Http\Request;

class FloorController extends Controller
{
    /**
     * Afficher tous les étages
     */
    public function index()
    {
        $floors = Floor::with('clinique', 'services.capteurs')->get();
        return response()->json($floors, 200);
    }

    /**
     * Créer un nouvel étage
     */
    public function store(Request $request)
{
    $floorsNames = ['Ground Floor', 'First Floor', 'Second Floor'];

    $validated = $request->validate([
        'clinique_id' => 'required|exists:cliniques,id',
        'nom' => ['required', 'string', 'max:255', Rule::in($floorsNames)],
    ]);

    $floor = Floor::create($validated);

    return response()->json([
        'message' => 'Étage créé avec succès',
        'data' => $floor
    ], 201);
}

    /**
     * Afficher un étage spécifique
     */
    public function show($id)
    {
        $floor = Floor::with('clinique', 'services.capteurs')->find($id);

        if (!$floor) {
            return response()->json(['message' => 'Étage introuvable'], 404);
        }

        return response()->json($floor, 200);
    }

    /**
     * Mettre à jour un étage
     */
    public function update(Request $request, $id)
    {
        $floor = Floor::find($id);

        if (!$floor) {
            return response()->json(['message' => 'Étage introuvable'], 404);
        }

        $validated = $request->validate([
            'clinique_id' => 'sometimes|exists:cliniques,id',
            'nom' => 'sometimes|string|max:255',
            'numero' => 'sometimes|integer',
        ]);

        $floor->update($validated);

        return response()->json([
            'message' => 'Étage mis à jour avec succès',
            'data' => $floor
        ], 200);
    }

    /**
     * Supprimer un étage
     */
    public function destroy($id)
    {
        $floor = Floor::find($id);

        if (!$floor) {
            return response()->json(['message' => 'Étage introuvable'], 404);
        }

        $floor->delete();

        return response()->json(['message' => 'Étage supprimé avec succès'], 200);
    }
}
