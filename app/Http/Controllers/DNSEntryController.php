<?php

namespace App\Http\Controllers;

use App\Models\DNSEntry;
use Illuminate\Http\Request;

class DNSEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return DNSEntry::all();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

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

        return DNSEntry::create($validated);
    }

    /**
     * Display the specified resource.
     */
    public function show(DNSEntry $dNSEntry)
    {
        return $dNSEntry;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DNSEntry $dNSEntry)
    {
        return $dNSEntry;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DNSEntry $dNSEntry)
    {
        $validated = $request->validate([
            'host' => 'sometimes|required|string',
            'ip' => 'sometimes|required|ip',
            'class' => 'sometimes|required|string',
            'type' => 'sometimes|required|string',
            'expires_at' => 'nullable|date',
        ]);

        $dNSEntry->update($validated);

        return $dNSEntry;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DNSEntry $dNSEntry)
    {
        $dNSEntry->delete();

        return response()->noContent();
    }
}
