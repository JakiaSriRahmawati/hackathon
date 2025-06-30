<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class FriendsController extends Controller
{
    // Lihat daftar teman (yang sudah accepted)
    public function index(Request $request)
    {
        $userId = Auth::id();

        $friends = Friend::where(function ($query) use ($userId) {
            $query->where('user_id', $userId)
                  ->orWhere('friend_id', $userId);
        })
        ->where('status', 'accepted')
        ->with(['requester', 'receiver'])
        ->get()
        ->map(function ($friend) use ($userId) {
            // Ambil data teman yang bukan dirinya sendiri
            return $friend->user_id == $userId
                ? $friend->receiver
                : $friend->requester;
        });
    
        return response()->json([
            'message' => 'Daftar teman berhasil ditampilkan',
            'friends' => $friends
        ]);
    }

    // Kirim permintaan teman
    public function store(Request $request)
    {
        try {
            $userId = $request->user()->id;

            $validator = Validator::make($request->all(), [
                'friend_id' => [
                    'required',
                    'integer',
                    'exists:users,id',
                    function ($attribute, $value, $fail) use ($userId) {
                        if ($value == $userId) {
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
            $existing = Friend::where('user_id', $request->user()->id)
                ->where('friend_id', $friendId)
                ->first();

            if ($existing) {
                return response()->json([
                    'message' => 'Friend request already sent or exists'
                ], 400);
            }

            // Simpan data permintaan pertemanan baru
            $friend = Friend::create([
                'user_id' => $request->user()->id,
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
    

    public function requesting_friends(Request $request)
    {
        $friends = Friend::where('friend_id', $request->user()->id)
            ->where('status', 'pending')
            ->with('requester') // ← ambil data pengirim (user_id)
            ->get()
            ->map(function ($friend) {
                return [
                    'id' => $friend->id,
                    'user_id' => $friend->user_id,
                    'requester_name' => $friend->requester->name,
                    'requester_email' => $friend->requester->email,
                    'status' => $friend->status,
                    'created_at' => $friend->created_at,
                ];
            });

        return response()->json([
            'message' => 'Daftar teman yang pending berhasil ditampilkan',
            'friends' => $friends
        ]);
    }


    public function accept_request(Request $request, string $id)
    {
        try {
            $friend = Friend::where('id', $id)
                ->where('friend_id', $request->user()->id)
                ->where('status', 'pending')
                ->firstOrFail();
    
            $friend->status = 'accepted';
            $friend->save();
    
            return response()->json([
                'message' => 'Permintaan teman diterima',
                'data' => $friend
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
