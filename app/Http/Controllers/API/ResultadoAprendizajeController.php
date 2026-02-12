<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ResultadoAprendizajeResource;
use App\Models\ModuloFormativo;
use App\Models\ResultadoAprendizaje;
use Illuminate\Http\Request;

class ResultadoAprendizajeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, ModuloFormativo $moduloFormativo)
    {
        $search = $request->query('search');

        return ResultadoAprendizajeResource::collection(
            ResultadoAprendizaje::where('modulo_formativo_id', $moduloFormativo->id)
                ->where(function ($query) use ($search) {
                    $query->where('nombre', 'like', "%{$search}%")
                        ->orWhere('codigo', 'like', "%{$search}%");
                })
                ->orderBy($request->sort ?? 'id', $request->order ?? 'asc')
                ->paginate($request->per_page)
        );
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ModuloFormativo $moduloFormativo)
    {
        $request->validate([

            'descripcion' => 'required|string',
            'codigo' => 'required|string|max:255|unique:resultados_aprendizaje,codigo',
            'peso_porcentaje' => 'required|numeric|between:0,100',
            'orden' => 'required|integer|min:1',
        ]);

        $resultado = json_decode($request->getContent(), true);
        $resultado['modulo_formativo_id'] = $moduloFormativo->id;

        $resultado = ResultadoAprendizaje::create($resultado);

        return new ResultadoAprendizajeResource($resultado);
    }

    /**
     * Display the specified resource.
     */
    public function show(ResultadoAprendizaje $resultadoAprendizaje, ModuloFormativo $moduloFormativo)
    {
        abort_if($resultadoAprendizaje->modulo_formativo_id != $moduloFormativo->id, 404);

        return new ResultadoAprendizajeResource($resultadoAprendizaje);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ModuloFormativo $moduloFormativo, ResultadoAprendizaje $resultadoAprendizaje)
    {
        $resultadoAprendizajeData = json_decode($request->getContent(), true);
        $resultadoAprendizaje->update($resultadoAprendizajeData);
        $resultadoAprendizaje->modulo_formativo_id = $moduloFormativo->id;
        $resultadoAprendizaje->save();

        return new ResultadoAprendizajeResource($resultadoAprendizaje);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ResultadoAprendizaje $resultadoAprendizaje)
    {
        try {
            $resultadoAprendizaje->delete();
            return response()->json([ 'message' => 'Resultado de aprendizaje eliminado correctamente' ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error: ' . $e->getMessage()
            ], 400);
        }
    }
}
