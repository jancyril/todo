<?php

namespace Tests\Feature;

use Mockery;
use Tests\TestCase;
use App\Models\Task;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskControllerTest extends TestCase
{
    /** @test **/
    public function creatingNewTaskWillFailIfTitleIsEmpty()
    {
        $data = $this->make(['title' => '']);

        $this->post(route('tasks.store'), $data)
            ->assertSessionHasErrors();
    }

    /** @test **/
    public function creatingNewTaskShouldReturnAnErrorIfTaskIsNotCreated()
    {
        $task = Mockery::mock('App\Models\Task');
        $task->shouldReceive('create')->with(['title' => 'Some'])->andReturn(false);

        $this->post(route('tasks.store'))
            ->assertSee('Failed to create task, please try again.')
            ->assertStatus(409);
    }

    private function make(array $overrides = [])
    {
        return factory(Task::class)->make($overrides)->toArray();
    }

    public function tearDown()
    {
        Mockery::close();
    }
}
