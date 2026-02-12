<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ModuloFormativoResource;
use App\Models\ModuloFormativo;
use Illuminate\Http\Request;

class ModuloFormativoController extends Controller
{
    public function index(Request $request, $cicloId)
    {
        $search = $request->query('search');

        return ModuloFormativoResource::collection(
            ModuloFormativo::where('ciclo_formativo_id', $cicloId)
                ->where(function ($query) use ($search) {
                    $query->where('nombre', 'like', "%{$search}%")
                        ->orWhere('codigo', 'like', "%{$search}%");
                })
                ->orderBy($request->sort ?? 'id', $request->order ?? 'asc')
                ->paginate($request->per_page)
        );
    }

    public function store(Request $request, $cicloId)
    {
            $request->validate([
                
                'nombre' => 'required|string|max:255',
                'codigo' => 'required|string|max:255|unique:modulos_formativos,codigo',
                'horas_totales' => 'required|integer',
                'curso_escolar' => 'required|string|max:255',
                'centro' => 'required|string|max:255',
                'docente_id' => 'nullable|integer|unique',
                'descripcion' => 'nullable|string',
            ]);

        $data = json_decode($request->getContent(), true);
        $data['ciclo_formativo_id'] = $cicloId;

        $modulo = ModuloFormativo::create($data);

        return new ModuloFormativoResource($modulo);
    }

    public function show($cicloId, ModuloFormativo $moduloFormativo)
    {

        return new ModuloFormativoResource($moduloFormativo);
    }

    public function update(Request $request, $cicloId, ModuloFormativo $moduloFormativo)
    {

        $data = json_decode($request->getContent(), true);
        $data['ciclo_formativo_id'] = $cicloId;

        $moduloFormativo->update($data);

        return new ModuloFormativoResource($moduloFormativo);
    }

    public function destroy($cicloId, ModuloFormativo $moduloFormativo)
    {

        $moduloFormativo->delete();

        return response()->json(['message' => 'ModuloFormativo eliminado correctamente'], 200);
    }
}
