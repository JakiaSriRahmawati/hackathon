<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class FriendsController extends Controller
{
    // Lihat daftar teman (yang sudah accepted)
    public function index()
    {
        $userId = auth()->id();

        $friends = Friend::where(function ($query) use ($userId) {
            $query->where('user_id', $userId)
                  ->orWhere('friend_id', $userId);
        })->where('status', 'accepted')->get();

        return response()->json($friends);
    }

    // Kirim permintaan teman
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'friend_id' => [
                    'required',
                    'integer',
                    'exists:users,id',
                    function ($attribute, $value, $fail) {
                        if ($value == auth()->id()) {
                            $fail('You cannot send a friend request to yourself.');
                        }
                    },
                ],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $friendId = $request->input('friend_id');

            // Cek apakah permintaan sudah pernah dikirim
            $existing = Friend::where('user_id', auth()->id())
                ->where('friend_id', $friendId)
                ->first();

            if ($existing) {
                return response()->json([
                    'message' => 'Friend request already sent or exists'
                ], 400);
            }

            // Simpan data permintaan pertemanan baru
            $friend = Friend::create([
                'user_id' => auth()->id(),
                'friend_id' => $friendId,
                'status' => 'pending',
            ]);

            return response()->json([
                'message' => 'Friend request sent',
                'data' => $friend
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    // Tampilkan detail
    public function show(Friend $friend)
    {
        return response()->json($friend);
    }

    // Terima / Tolak permintaan
    public function update(Request $request, Friend $friend)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(friend $friends)
    {
        //
    }
}
