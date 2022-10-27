<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user()->id;
        if ($request->ajax()) {
            $events = Task::whereDate('start', '>=', $request->start)->whereDate('end', '<=', $request->end)->where('user_id', $user)->get(['id', 'title', 'description', 'user_id', 'admin_id', 'start', 'end']);
            return response()->json($events);
        }

        return view('dashboard');
    }
}
