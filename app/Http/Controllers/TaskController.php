<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use App\Models\Task;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = Task::latest()->paginate(5);
        date_default_timezone_set('UTC');
        $timezone_name = Settings::latest()->limit(1)->pluck('default_timezone')[0] ?? 'UTC';
        foreach ($tasks as $key=>$task){
            $tasks[$key]['date'] = Carbon::parse($tasks[$key]['date'])->setTimezone($timezone_name)->format('Y-m-d H:i');
        }
        return view('tasks.index',compact('tasks'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tasks.create');
    }
    public function save_timezone(Request $request)
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'task' => 'required',
            'date' => 'required',
            'timezone_difference' => 'required',
        ]);
        $input = $request->all();
        $timezone_name = Settings::latest()->limit(1)->pluck('default_timezone')[0] ?? 'UTC';
        date_default_timezone_set($timezone_name);
        $input['date'] = Carbon::parse($input['date'])->setTimezone($timezone_name)->format('Y-m-d H:i');
        Task::create($input);
        return redirect()->route('tasks.index')
                        ->with('success','Task created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        date_default_timezone_set('UTC');
        $timezone_name = Settings::latest()->limit(1)->pluck('default_timezone')[0] ?? 'UTC';
        $task['date'] = Carbon::parse($task['date'])->setTimezone($timezone_name)->format('Y-m-d H:i:s');
        return view('tasks.show',compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        date_default_timezone_set('UTC');
        $timezone_name = Settings::latest()->limit(1)->pluck('default_timezone')[0] ?? 'UTC';
        $date = Carbon::parse($task['date'])->setTimezone($timezone_name)->format('Y-m-dTH:i');
        if (str_contains($date, 'IS')) {
            $task['date'] = str_replace("IS","",$date);
        }else{
            $task['date'] = str_replace("PK","",$date);
        }
        return view('tasks.edit',compact('task'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        $request->validate([
            'task' => 'required',
            'date' => 'required',
        ]);
        $input = $request->all();
        $timezone_name = Settings::latest()->limit(1)->pluck('default_timezone')[0] ?? 'UTC';
        date_default_timezone_set($timezone_name);
        $input['date'] = Carbon::parse($input['date'])->setTimezone('UTC')->format('Y-m-d H:i:s');
        $task->update($input);
        return redirect()->route('tasks.index')
                        ->with('success','Task updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('tasks.index')
                        ->with('success','Task deleted successfully');
    }
}
