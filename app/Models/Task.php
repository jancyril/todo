<?php

/**
 * @author Jan Cyril Segubience <jancyril@segubience.com>
 */
namespace App\Models;

use App\Traits\DataTables;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class Task extends Model
{
    use DataTables;

    protected $fillable = [
        'title', 'status', 'description'
    ];

    protected function map(Collection $tasks)
    {
        return $tasks->map(function ($task) {
            return [
                'id' => $task->id,
                'title' => $task->title,
                'status' => $task->status,
            ];
        })->all();
    }
}
