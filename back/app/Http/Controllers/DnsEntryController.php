<?php

namespace App\Http\Controllers;

use App\Models\DnsEntry;
use Illuminate\Http\Request;

class DnsEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $dnsEntries = $user->dnsEntries()->get();

        return response()->json($dnsEntries);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'host' => 'required|string',
            'ip' => 'required|ip',
            'class' => 'required|string',
            'type' => 'required|string',
            'expires_at' => 'nullable|date',
        ]);

        $entry = auth()->user()->dnsEntries()->create($validated);

        return response()->json($entry, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(DnsEntry $dnsEntry)
    {
        if ($dnsEntry->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($dnsEntry);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DnsEntry $dnsEntry) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DnsEntry $dnsEntry)
    {
        if ($dnsEntry->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'host' => 'sometimes|required|string',
            'ip' => 'sometimes|required|ip',
            'class' => 'sometimes|required|string',
            'type' => 'sometimes|required|string',
            'expires_at' => 'nullable|date',
        ]);

        $dnsEntry->update($validated);

        return response()->json($dnsEntry);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DnsEntry $dnsEntry)
    {
        if ($dnsEntry->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $dnsEntry->delete();

        return response()->noContent();
    }
}
