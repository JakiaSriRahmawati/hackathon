<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Todo;
use App\Models\User;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;

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
}
