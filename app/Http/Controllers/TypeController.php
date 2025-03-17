<?php

namespace App\Http\Controllers;

use App\Models\TruckType;
use App\Models\CargoType;
use Illuminate\Http\Request;

class TypeController extends Controller
{
    public function index()
    {
        $truckTypes = TruckType::all();
        $cargoTypes = CargoType::all();
        return view('types', compact('truckTypes', 'cargoTypes'));
    }

    public function store(Request $request)
    {
        if ($request->type == 'truck') {
            TruckType::create($request->all());
        } elseif ($request->type == 'cargo') {
            CargoType::create($request->all());
        }

        return redirect()->route('types.index');
    }

    public function update(Request $request, $id)
    {
        if ($request->type == 'truck') {
            $type = TruckType::findOrFail($id);
        } elseif ($request->type == 'cargo') {
            $type = CargoType::findOrFail($id);
        }

        $type->update($request->all());
        return redirect()->route('types.index');
    }

    public function destroy(Request $request, $id)
    {
        if ($request->type == 'truck') {
            TruckType::destroy($id);
        } elseif ($request->type == 'cargo') {
            CargoType::destroy($id);
        }

        return redirect()->route('types.index');
    }
}
