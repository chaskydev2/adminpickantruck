<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    public function index(Request $request)
    {
        $query = Membership::with('user')->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tier')) {
            $query->where('tier', $request->tier);
        }

        $memberships = $query->paginate(15)->appends($request->only(['status', 'tier']));

        return view('memberships.index', compact('memberships'));
    }

    public function show(Membership $membership)
    {
        $membership->load('user');
        return view('memberships.show', compact('membership'));
    }

    public function activate(Membership $membership)
    {
        $membership->update([
            'status'     => Membership::STATUS_ACTIVE,
            'start_date' => $membership->start_date ?? now()->toDateString(),
        ]);

        return back()->with('success', 'Membresía activada correctamente.');
    }

    public function cancel(Membership $membership)
    {
        $membership->update(['status' => Membership::STATUS_CANCELLED]);

        return back()->with('success', 'Membresía cancelada.');
    }
}
