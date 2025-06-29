<?php

namespace App\Http\Controllers\API;

use App\Models\Goal;
use App\Models\Todo;
use App\Models\User;
use App\Models\Friend;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function profile(Request $request)
    {
        try {
            $userId = $request->user()->id;

            $todo = Todo::where('user_id', $userId)->get();
            $total = $todo->count();
            $task_completed = $todo->where('is_done', 1)->count();

            $goals = Goal::where('user_id', $userId)->get();
            $goals_completed = $goals->where('is_done', 1)->count();
            $goals_total = $goals->count();

            $recent_activity = Todo::where('user_id', $userId)
                                    ->latest()
                                    ->take(5)
                                    ->get();
        
            $acceptedCount = Friend::where(function ($q) use ($userId) {
                $q->where('user_id', $userId)
                ->orWhere('friend_id', $userId);
            })
                ->where('status', 'accepted')
                ->count();

            $friendCount = Friend::where(function ($q) use ($userId) {
                $q->where('user_id', $userId)
                ->orWhere('friend_id', $userId);
            })
                ->count();      

            return response()->json([
                'message' => 'Berhasil mendapatkan data Profile',
                'data' => [
                    'task' => [
                        'completed' => $task_completed,
                        'total' => $total
                    ],
                    'goals' => [
                        'completed' => $goals_completed,
                        'total' => $goals_total
                    ],
                    'recent_activity' => $recent_activity,
                    'friends' => [
                        'accepted' => $acceptedCount,
                        'total' => $friendCount
                    ],
                    'user' => $request->user()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mendapatkan data Profile',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function profileById($id)
    {
        try {
            $user = User::findOrFail($id);

            $todo = Todo::where('user_id', $id)->get();
            $total = $todo->count();
            $task_completed = $todo->where('is_done', 1)->count();

            $goals = Goal::where('user_id', $id)->get();
            $goals_completed = $goals->where('is_done', 1)->count();
            $goals_total = $goals->count();

            $recent_activity = Todo::where('user_id', $id)
                                    ->latest()
                                    ->take(5)
                                    ->get();

            $acceptedCount = Friend::where(function ($q) use ($id) {
                    $q->where('user_id', $id)
                    ->orWhere('friend_id', $id);
                })
                ->where('status', 'accepted')
                ->count();

            $friendCount = Friend::where(function ($q) use ($id) {
                    $q->where('user_id', $id)
                    ->orWhere('friend_id', $id);
                })
                ->count();

            return response()->json([
                'message' => 'Berhasil mendapatkan data Profile',
                'data' => [
                    'task' => [
                        'completed' => $task_completed,
                        'total' => $total
                    ],
                    'goals' => [
                        'completed' => $goals_completed,
                        'total' => $goals_total
                    ],
                    'recent_activity' => $recent_activity,
                    'friends' => [
                        'accepted' => $acceptedCount,
                        'total' => $friendCount
                    ],
                    'user' => $user
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mendapatkan data Profile',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function search(Request $request)
    {
        $query = $request->query('query');

        $users = User::select('id', 'name', 'email', 'profile_picture')
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%')
                ->orWhere('username', 'like', '%' . $query . '%')
                ->orWhere('email', 'like', '%' . $query . '%');
            })
            ->get();

        return response()->json($users);
    }

}
