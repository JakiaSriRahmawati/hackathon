<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use App\Models\goals;
use Illuminate\Http\Request;

class GoalsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $goal = Goal::create([
            'user_id' => $request->user()->id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
            'current_streak' => 0,
            'is_active' => true,
        ]);

        return response()->json([
            'message' => 'Goal berhasil dibuat',
            'data' => $goal
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(goals $goals)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(goals $goals)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, goals $goals)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(goals $goals)
    {
        //
    }
}
