<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Helpers\ErrorResponse;
use App\Helpers\SuccessResponse;
use App\Http\Requests\Task as Validation;

class TasksController extends Controller
{
    public function get(Request $request, Task $task)
    {
        return $task->dataTables($request->all());
    }

    public function show(Task $task)
    {
        try {
            return new SuccessResponse(
                'Successfully fetched task details',
                [
                    'title' => $task->title,
                    'description' => $task->description
                ]
            );
        } catch (\Throwable $e) {
            return new ErrorResponse('Failed to fetch update task, please try again.', [], 404);
        }
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

            return new ErrorResponse('Failed to create task, please try again.', [], 409);
        } catch (\Throwable $e) {
            return new ErrorResponse('Something went wrong while trying to create new task.');
        }
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
        try {
            return new SuccessResponse(
                'Successfully fetched task details',
                [
                    'title' => $task->title,
                    'description' => $task->description
                ]
            );
        } catch (\Throwable $e) {
            return new ErrorResponse('Failed to fetch update task, please try again.', [], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Task         $task
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Validation $validation, Task $task)
    {
        try {
            if ($task->update($request->all())) {
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
            if ($task->delete()) {
                return new SuccessResponse('Task has been successfully deleted.');
            }

            return new ErrorResponse('Failed to delete task, please try again.');
        } catch (\Throwable $e) {
            return new ErrorResponse($e->getMessage());
        }
    }

    public function updateStatus(Request $request, Task $task)
    {
        try {
            if ($task->update(['status' => $request->get('status')])) {
                return new SuccessResponse('Status has been successfully updated.');
            }

            return new ErrorResponse('Failed to update status, please try again.', [], 409);
        } catch (\Throwable $e) {
            return new ErrorResponse('Something went wrong while trying to update status.');
        }
    }
}
