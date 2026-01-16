<?php

namespace App\Http\Controllers;

use App\Models\TruckType;
use Illuminate\Http\Request;

class TipoCamionController extends Controller
{
    public function index()
    {
        $truckTypes = TruckType::all();
        return view('tiposcamion.index', compact('truckTypes'));
    }

    public function create()
    {
        return view('tiposcamion.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:truck_types,name',
            'description' => 'required',
        ]);

        TruckType::create([
            'name' => $request->name,
            'description' => $request->description,
            'active' => 1,
        ]);

        return redirect()
            ->route('tiposcamion.index')
            ->with('success', 'Tipo de camión creado correctamente');
    }

    public function edit(TruckType $tiposcamion)
    {
        return view('tiposcamion.edit', compact('tiposcamion'));
    }

    public function update(Request $request, TruckType $tiposcamion)
    {
        $request->validate([
            'name' => 'required|unique:truck_types,name,' . $tiposcamion->id,
            'description' => 'required',
        ]);

        $tiposcamion->update($request->only('name', 'description'));

        return redirect()
            ->route('tiposcamion.index')
            ->with('success', 'Tipo de camión actualizado correctamente');
    }

    public function destroy(TruckType $tiposcamion)
    {
        $tiposcamion->delete();

        return redirect()
            ->route('tiposcamion.index')
            ->with('success', 'Tipo de camión eliminado correctamente');
    }
}
