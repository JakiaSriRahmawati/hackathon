<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Todo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function profile(Request $request)
    {
        $todo = Todo::where('user_id', $request->user()->id)->get();
        return response()->json([
            'message' => 'Berhasil mendapatkan data Profile',
            'data' => [
                'todo' => $todo,
                'user' => $request->user()
            ]
        ]);
    }

    public function profileById(Request $request, $id)
    {
        try{
            $user = User::findOrFail($id);
            return response()->json([
                'message' => 'Berhasil mendapatkan data Profile',
                'data' => $user
            ]);
        }catch(\Exception $e){
            return response()->json([
                'message' => 'Gagal mendapatkan data Profile',
                'data' => $e
            ]);
        }
    }

    // Fungsi upload profile picture
    public function uploadProfilePicture(Request $request)
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = $request->user();

        // Hapus gambar lama jika ada
        if ($user->profile_picture) {
            Storage::disk('public')->delete($user->profile_picture);
        }

        // Simpan gambar baru
        $path = $request->file('profile_picture')->store('profile_pictures', 'public');

        $user->profile_picture = $path;
        $user->save();

        return response()->json([
            'message' => 'Profile picture uploaded successfully.',
            'profile_picture_url' => asset('storage/' . $path),
        ]);
    }
}
