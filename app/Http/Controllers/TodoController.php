<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class TodoController extends Controller
{
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
        // return 123;
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            // 'user_id' => 'required|exists:users,id',
            'photo' => 'nullable|image|max:2048', 
            'caption' => 'nullable|string|max:255', 
        ]);

        // Simpan path foto jika ada
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('public/photos');
        }

        $todo = \App\Models\Todo::create([
            'title' => $request->title,
            'description' => $request->description,
            // 'user_id' => Auth::user()->id,
            'user_id' =>  1,
            'photo_path' => $photoPath,
            'caption' => $request->caption,
            'created_at' => \Carbon\Carbon::now()
        ]);

        return response()->json([
            'message' => 'Todo berhasil dibuat.',
            'todo' => $todo,
        ]);
    }


    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
