<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Helpers\ErrorResponse;
use App\Helpers\SuccessResponse;
use App\Http\Requests\Task as Validation;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Validation $validation)
    {
        try {
            if (Task::create($request->all())) {
                return new SuccessResponse('New task has been successfully created.');
            }

            return new ErrorResponse('Failed to create task, please try again.');
        } catch (\Throwable $e) {
            return new ErrorResponse('Something went wrong while trying to create new task.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Task $task
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Task $task
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Task         $task
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Validation $validation, Request $request, Task $task)
    {
        try {
            if (Task::update($request->all())) {
                return new SuccessResponse('Task has been successfully updated.');
            }

            return new ErrorResponse('Failed to update task, please try again.');
        } catch (\Throwable $e) {
            return new ErrorResponse('Something went wrong while trying to update task.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Task $task
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        try {
            if (Task::destroy($request->all())) {
                return new SuccessResponse('Task has been successfully deleted.');
            }

            return new ErrorResponse('Failed to delete task, please try again.');
        } catch (\Throwable $e) {
            return new ErrorResponse('Something went wrong while trying to delete task.');
        }
    }
}
