<?php

namespace App\Http\Controllers;

use App\Models\Administrator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdministratorController extends Controller
{
    public function index()
    {
        $administrators = Administrator::all();
        return view('administrators.index', compact('administrators'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:administrators',
            'password' => 'required|min:8',
            'role' => 'required|in:admin,editor'
        ]);

        $validated['password'] = Hash::make($validated['password']);
        Administrator::create($validated);

        return redirect()->route('administrators.index');
    }

    public function update(Request $request, Administrator $administrator)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:administrators,email,'.$administrator->id,
            'role' => 'required|in:admin,editor'
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        }

        $administrator->update($validated);
        return redirect()->route('administrators.index');
    }

    public function destroy(Administrator $administrator)
    {
        $administrator->delete();
        return redirect()->route('administrators.index');
    }
}
