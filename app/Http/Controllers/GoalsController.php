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
    public function show(Request $request)
    {
        $goals = Goal::where('user_id', $request->user()->id)->get();

        return response()->json([
            'message' => 'Goals berhasil ditampilkan',
            'goals' => $goals
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        try {
            $goal = Goal::where('id', $id)
                        ->where('user_id', $request->user()->id)
                        ->firstOrFail();
           

            $goal->delete();

            return response()->json([
                'message' => 'goal berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menghapus todo',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
