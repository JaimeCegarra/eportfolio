<?php

namespace App\Http\Controllers;

use App\Models\FamiliaProfesional;
use Illuminate\Http\Request;

class FamiliasProfesionalesController extends Controller
{


    public function getIndex()
    {
        $familias_profesionales = FamiliaProfesional::all();
        return view('familiasProfesionales.index')
            ->with('familiasProfesionales', $familias_profesionales);
    }

    public function getShow($id)
    {
        $familias_profesionales = FamiliaProfesional::findOrFail($id);
        return view('familiasProfesionales.show')
            ->with('familiaProfesional', $familias_profesionales)
            ->with('id', $id);
    }

    public function getCreate()
    {

        return view('familiasProfesionales.create');
    }

    public function getEdit($id)
    {
        $familias_profesionales = FamiliaProfesional::findOrFail($id);
        return view('familiasProfesionales.edit')
            ->with('familiaProfesional',  $familias_profesionales);
    }

    public function store(Request $request)
    {
        $familiaProfesional  = new FamiliaProfesional();
        $familiaProfesional ->codigo = $request->input('codigo');
        $familiaProfesional ->nombre = $request->input('nombre');
        $familiaProfesional ->save();

        return redirect()->route('familias.show', ['id' => $familiaProfesional->id]);
    }
    public function update(Request $request, $id)
    {
        $familiaProfesional = FamiliaProfesional::findOrFail($id);
        $familiaProfesional->codigo = $request->input('codigo');
        $familiaProfesional->nombre = $request->input('nombre');

        if ($request->hasFile('imagen')) {
            $path = $request->file('imagen')->store('imagenes', ['disk' => 'public']);
            $familiaProfesional->imagen = $path;
        }

        $familiaProfesional->save();

        return redirect()->route('familias.show', [$familiaProfesional->id]);
    }


}
