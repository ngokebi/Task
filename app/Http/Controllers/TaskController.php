<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Facade\FlareClient\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Image;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $events = Task::whereDate('start', '>=', $request->start)->whereDate('end', '<=', $request->end)->get(['id', 'title', 'description', 'user_id', 'admin_id', 'start', 'end']);
            return response()->json($events);
        }
        $users = User::get();
        $admins = Auth::guard('admin')->user()->id;
        return view('admin.pages.events', compact('users', 'admins'));
    }


    public function ajax(Request $request)
    {
        switch ($request->type) {
            case 'add':
                $event = Task::create([
                    'title' => $request->title,
                    'description' => $request->description,
                    //   'image' => $img,
                    'user_id' => $request->user_id,
                    'admin_id' => Auth::guard('admin')->user()->id,
                    'start' => $request->start,
                    'end' => $request->end,
                    'created_at' => Carbon::now()
                ]);
                return response()->json($event);
                break;
            case 'update':
                $event = Task::find($request->id)->update([
                    'title' => $request->title,
                    'description' => $request->description,
                    //   'image' => $img,
                    'user_id' => $request->user_id,
                    'admin_id' => Auth::guard('admin')->user()->id,
                    'start' => $request->start,
                    'end' => $request->end,
                    'updated_at' => Carbon::now()
                ]);
                return response()->json($event);
                break;
            case 'delete':
                $event = Task::find($request->id)->delete();
                return response()->json($event);
                break;
            default:
                break;
        }
    }
    public function create(Request $request)
    {

        $event = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            //   'image' => $img,
            'user_id' => $request->user_id,
            'admin_id' => Auth::guard('admin')->user()->id,
            'start' => $request->start,
            'end' => $request->end,
            'updated_at' => Carbon::now()
        ]);

        return response()->json($event);
    }

    public function update(Request $request)
    {
        $event  = Task::where('id', $request->id)->update([
            'title' => $request->title,
            'description' => $request->description,
            //   'image' => $img,
            'user_id' => $request->user_id,
            'admin_id' => Auth::guard('admin')->user()->id,
            'start' => $request->start,
            'end' => $request->end,
            'updated_at' => Carbon::now()
        ]);

        return response()->json($event);
    }

    public function Delete(Request $request)
    {
        $event = Task::where('id', $request->id)->delete();

        return response()->json($event);
    }
}
