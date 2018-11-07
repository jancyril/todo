<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Activity\Log;
use Illuminate\Http\Request;
use App\Helpers\ErrorResponse;
use App\Helpers\SuccessResponse;
use App\Http\Requests\Task as Validation;

class TasksController extends Controller
{
    private $log;
    private $request;

    public function __construct(Request $request, Log $log)
    {
        $this->request = $request;
        $this->log = $log;
    }

    public function get(Task $task)
    {
        return $task->dataTables($this->request->all());
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
    public function store(Validation $validation)
    {
        try {
            if ($newTask = Task::create($this->request->all())) {
                $message = 'New task has been successfully created.';

                $this->log->newTask($message, $newTask->toArray());

                return new SuccessResponse($message);
            }

            return new ErrorResponse('Failed to create new task, please try again.', [], 409);
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
            return new ErrorResponse('Something went wrong while trying to fetch task details.');
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
    public function update(Validation $validation, Task $task)
    {
        try {
            $oldTaskValue = $task->toArray();

            if ($task->update($this->request->all())) {
                $message = 'Task has been successfully updated.';

                $this->log->updatedTask($message, $task->toArray(), $oldTaskValue);

                return new SuccessResponse($message);
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
            $oldTaskValue = $task->toArray();

            if ($task->delete()) {
                $message = 'Task has been successfully deleted.';

                $this->log->deletedTask($message, $oldTaskValue);

                return new SuccessResponse($message);
            }

            return new ErrorResponse('Failed to delete task, please try again.');
        } catch (\Throwable $e) {
            return new ErrorResponse($e->getMessage());
        }
    }

    public function updateStatus(Task $task)
    {
        try {
            $oldStatus = $task->only(['id', 'status']);

            if ($task->update(['status' => $this->request->get('status')])) {
                $message = 'Status has been successfully updated.';

                $this->log->updatedStatus($message, $task->only(['id', 'status']), $oldStatus);

                return new SuccessResponse($message);
            }

            return new ErrorResponse('Failed to update status, please try again.', [], 409);
        } catch (\Throwable $e) {
            return new ErrorResponse($e->getMessage());
        }
    }
}
