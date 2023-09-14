<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Date;
use Tests\TestCase;

class TaskApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function test_tasks()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Task::factory()->count(5)->create(['user_id' => $user->id, 'status' => 'TODO']);

        $response = $this->get(route('tasks.index'));

        $response->assertStatus(200);

        $response->assertJsonCount(5, 'TODO');

    }

    /**
     * @return void
     */
    public function test_task_store()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->postJson(route('tasks.store'), [
            'title'       => 'Sample Task',
            'description' => 'Sample Description',
            'status'      => 'TODO',
            'due_date'    => Date::today()->format('Y-m-d'),
            'position'    => 0
        ]);

        $response->assertStatus(200);

        $response->assertJson(['id' => true]);

        $this->assertDatabaseHas('tasks', [
            'title'       => 'Sample Task',
            'description' => 'Sample Description',
            'status'      => 'TODO',
            'due_date'    => Date::today()->format('Y-m-d'),
            'position'    => 0
        ]);
    }

    /**
     * @return void
     */
    public function test_task_update()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $task = Task::factory()->create([
            'user_id' => $user->id,
            'status' => 'TODO',
        ]);

        $response = $this->put(route('tasks.update', ['id' => $task->id]), [
            'title' => 'Updated Title',
            'description' => 'Updated Description',
            'status' => 'IN-PROGRESS',
            'position' => 1,
            'due_date' => now()->addDays(1)->format('Y-m-d'),
        ]);

        $response->assertStatus(200);

        $updatedTask = $task->fresh();

        $this->assertEquals('Updated Title', $updatedTask->title);
        $this->assertEquals('Updated Description', $updatedTask->description);
        $this->assertEquals('IN-PROGRESS', $updatedTask->status);
        $this->assertEquals(now()->addDays(1)->format('Y-m-d'), $updatedTask->due_date);
    }

    /**
     * @return void
     */
    public function test_task_delete()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $task = Task::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->delete(route('tasks.destroy', ['id' => $task->id]));
        $response->assertStatus(200);

        $this->assertSoftDeleted('tasks', ['id' => $task->id]);
    }
}
