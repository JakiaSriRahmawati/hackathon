<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;
use Log;

class TodoController extends Controller
{
    public function index(Request $request)
    {
        $todos = Todo::with('user')
            ->select('id', 'user_id', 'title', 'created_at', 'photo_path')
            ->get()
            ->map(function ($item) {
                $item->type = 'todo';
                if ($item->photo_path) {
                    $item->photo_path = url($item->photo_path); // atau asset()
                }
                return $item;
            })
            ->values();

        $goals = Goal::with('user')
            ->select('id', 'user_id', 'title', 'created_at')
            ->get()
            ->map(function ($item) {
                $item->type = 'goal';
                return $item;
            })
            ->values();

        $merged = $todos->concat($goals)
            ->sortByDesc(fn($item) => $item->created_at ?? now())
            ->values();

        $page = $request->get('page', 1);
        $perPage = 10;

        $paginated = new LengthAwarePaginator(
            $merged->forPage($page, $perPage),
            $merged->count(),
            $perPage,
            $page,
            ['path' => url()->current()]
        );

        return response()->json([
            'pagination' => $paginated,
            'debug' => [
                'todo_count' => $todos->count(),
                'goal_count' => $goals->count(),
                'merged_count' => $merged->count()
            ]
        ]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'photo_path' => 'nullable|string',
            'caption' => 'nullable|string|max:255',
        ]);

        $todo = \App\Models\Todo::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => $request->user()->id,
            'caption' => $request->caption,
            'created_at' => \Carbon\Carbon::now()
        ]);

        return response()->json([
            'message' => 'Todo berhasil dibuat.',
            'todo' => $todo,
        ]);
    }

    public function show(Request $request)
    {

        $todos = Todo::with('user')
            ->where('user_id', $request->user()->id)
            ->get()
            ->map(function ($item) {
                $item->type = 'todo';
                if ($item->photo_path) {
                    $item->photo_path = url($item->photo_path); // atau asset()
                }
                return $item;
            })
            ->values();

        return response()->json([
            'message' => 'Todo berhasil ditampilkan',
            'todos' => $todos
        ]);
    }


    public function edit(Request $request, string $id)
    {
        if (!$request->hasFile('photo')) {
            return response()->json([
                'message' => 'File photo tidak ditemukan',
                'received_keys' => array_keys($request->all())
            ], 400);
        }
        try {
            $request->validate([
                'photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            $todo = Todo::where('id', $id)
                ->where('user_id', $request->user()->id)
                ->firstOrFail();
            // Simpan file
            $path = $request->file('photo')->store('todo_photos', 'public');

            $todo->update([
                'photo_path' => Storage::url($path),
                'is_done' => true,
            ]);

            return response()->json([
                'message' => 'Todo berhasil diupdate',
                'data' => $todo
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal update Todo',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(Request $request, string $id)
    {
        try {
            $todo = Todo::where('id', $id)
                        ->where('user_id', $request->user()->id)
                        ->firstOrFail();

            // Hapus file jika ada
            if ($todo->photo_path) {
                $path = str_replace('/storage/', 'public/', $todo->photo_path);
                Storage::delete($path);
            }

            $todo->delete();

            return response()->json([
                'message' => 'Todo berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menghapus todo',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
