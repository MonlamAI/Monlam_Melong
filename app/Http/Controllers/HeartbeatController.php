<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserHeartbeat;
use Illuminate\Support\Carbon;

class HeartbeatController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        UserHeartbeat::create([
            'user_id' => $user->id,
            'occurred_at' => now(),
        ]);

        return response()->json(['status' => 'ok']);
    }
}
