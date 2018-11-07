<?php

namespace App\Activity;

class Log
{
    public function newTask(string $message, array $value)
    {
        activity()
            ->withProperties($value)
            ->log($message);
    }

    public function updatedTask(string $message, array $newValue, array $oldValue)
    {
        activity()
            ->withProperties([
                'new_value' => $newValue,
                'old_value' => $oldValue,
            ])
            ->log($message);
    }

    public function deletedTask(string $message, array $value)
    {
        activity()
            ->withProperties($value)
            ->log($message);
    }

    public function updatedStatus(string $message, array $newValue, array $oldValue)
    {
        activity()
            ->withProperties([
                'new_value' => $newValue,
                'old_value' => $oldValue,
            ])
            ->log($message);
    }
}
