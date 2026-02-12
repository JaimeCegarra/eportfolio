<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CicloFormativoResource;
use App\Models\CicloFormativo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CicloFormativoController extends Controller
{
    public function index(Request $request, $familiaId)
    {
        $search = $request->query('search');

        return CicloFormativoResource::collection(
            CicloFormativo::where('familia_profesional_id', $familiaId)
                ->where(function ($query) use ($search) {
                    $query->where('nombre', 'like', "%{$search}%")
                        ->orWhere('codigo', 'like', "%{$search}%");
                })
                ->orderBy($request->sort ?? 'id', $request->order ?? 'asc')
                ->paginate($request->per_page)
        );
    }

    public function store(Request $request, $familiaId)
    {

        $request->validate([

            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|max:255|unique:ciclos_formativos,codigo',
            'grado' => 'required|in:basico,medio,superior',
            'descripcion' => 'nullable|string',
        ]);

        Gate::authorize('create', CicloFormativo::class);

        $ciclo = CicloFormativo::create([
        'familia_profesional_id' => $familiaId,
        'nombre' => $request->nombre,
        'codigo' => $request->codigo,
        'grado' => $request->grado,
        'descripcion' => $request->descripcion,
    ]);

        return new CicloFormativoResource($ciclo);



    }

    public function show($familiaId, CicloFormativo $cicloFormativo)
    {
        if ($cicloFormativo->familia_profesional_id != $familiaId) {
            abort(404);
        }

        return new CicloFormativoResource($cicloFormativo);
    }

    public function update(Request $request, $familiaId, CicloFormativo $cicloFormativo)
    {
        Gate::authorize('update', $cicloFormativo);

        if ($cicloFormativo->familia_profesional_id != $familiaId) {
            abort(404);
        }

        $data = json_decode($request->getContent(), true);
        $data['familia_profesional_id'] = $familiaId;

        $cicloFormativo->update($data);

        return new CicloFormativoResource($cicloFormativo);
    }

    public function destroy($familiaId, CicloFormativo $cicloFormativo)
    {

        Gate::authorize('delete', $cicloFormativo);

        if ($cicloFormativo->familia_profesional_id != $familiaId) {
            abort(404);
        }

        try {
            $cicloFormativo->delete();
            return response()->json(['message' => 'CicloFormativo eliminado correctamente'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error: ' . $e->getMessage()
            ], 400);
        }
    }
}
