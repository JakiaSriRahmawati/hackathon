<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function index()
    {
        $todos = Todo::all(); // ambil semua data dari tabel todo
        return response()->json($todos);
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
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'photo_path' => 'nullable|string', // diganti dari 'photo'
            'caption' => 'nullable|string|max:255',
        ]);

        // Simpan path foto jika ada
        $photoPath = null;
        if ($request->photo_path) {
            $base64Image = $request->photo_path;

            // Pastikan formatnya benar
            if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $type)) {
                $image = substr($base64Image, strpos($base64Image, ',') + 1);
                $image = base64_decode($image);

                if ($image === false) {
                    return response()->json(['message' => 'Gagal decode base64'], 400);
                }

                $extension = strtolower($type[1]); // jpg, png, gif, etc

                if (!in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
                    return response()->json(['message' => 'Format gambar tidak didukung'], 400);
                }

                $fileName = 'photo_' . time() . '.' . $extension;
                $photoPath = 'photos/' . $fileName;

                // Simpan ke storage/app/public/photos
                \Storage::disk('public')->put($photoPath, $image);
            } else {
                return response()->json(['message' => 'Format base64 tidak valid'], 400);
            }
        }

        $todo = \App\Models\Todo::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => 1,
            'photo_path' => $photoPath, // tetap pakai field ini untuk disimpan di DB
            'caption' => $request->caption,
            'created_at' => \Carbon\Carbon::now()
        ]);

        return response()->json([
            'message' => 'Todo berhasil dibuat.',
            'todo' => $todo,
        ]);
    }


    // public function store(Request $request)
    // {
    //     // return 123;
    //     $request->validate([
    //         'title' => 'required|string|max:255',
    //         'description' => 'nullable|string',
    //         // 'user_id' => 'required|exists:users,id',
    //         'photo' => 'nullable|image|max:2048', 
    //         'caption' => 'nullable|string|max:255', 
    //     ]);

    //     // Simpan path foto jika ada
    //     $photoPath = null;
    //     if ($request->hasFile('photo')) {
    //         $photoPath = $request->file('photo')->store('public/photos');
    //     }

    //     $todo = \App\Models\Todo::create([
    //         'title' => $request->title,
    //         'description' => $request->description,
    //         // 'user_id' => Auth::user()->id,
    //         'user_id' =>  1,
    //         'photo_path' => $photoPath,
    //         'caption' => $request->caption,
    //         'created_at' => \Carbon\Carbon::now()
    //     ]);

    //     return response()->json([
    //         'message' => 'Todo berhasil dibuat.',
    //         'todo' => $todo,
    //     ]);
    // }




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
