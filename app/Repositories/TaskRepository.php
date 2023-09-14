<?php

namespace App\Repositories;

use App\Models\Task;
use Illuminate\Support\Facades\Auth;

/**
 * Task Repository
 */
class TaskRepository
{
    /**
     * @param array $task
     * @return mixed
     */
    public function create(array $task)
    {
        return Task::create($task);
    }

    /**
     * @return mixed
     */
    public function all()
    {
        return Task::orderBy('position', 'ASC')->where('user_id', Auth::id())->get();
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function find(int $id)
    {
        return Task::where('user_id', Auth::id())->find($id);
    }

    /**
     * @param $task
     * @param array $parameters
     * @return mixed
     */
    public function update($task, array $parameters)
    {
        return $task->where(['id' => $task->id, 'user_id' => Auth::id()])->update($parameters);
    }

    /**
     * @param string $status
     * @param int $position
     * @return void
     */
    public function incrementPosition(string $status, int $position)
    {
        Task::where('user_id', Auth::id())
            ->where('status', $status)
            ->where('position', '>=', $position)
            ->increment('position');
    }

    /**
     * @param string $status
     * @param int $position
     * @return void
     */
    public function decrementPosition(string $status, int $position)
    {
        Task::where('user_id', Auth::id())
            ->where('status', $status)
            ->where('position', '>=', $position)
            ->decrement('position');
    }

    /**
     * @param string $status
     * @param int $firstPosition
     * @param int $secondPosition
     * @return void
     */
    public function incrementPositionInBetween(string $status, int $firstPosition, int $secondPosition)
    {
       Task::where('user_id', Auth::id())
           ->where('status', $status)
           ->whereBetween('position', [$firstPosition, $secondPosition])
           ->increment('position');
    }

    /**
     * @param string $status
     * @param int $firstPosition
     * @param int $secondPosition
     * @return void
     */
    public function decrementPositionInBetween(string $status, int $firstPosition, int $secondPosition)
    {
       Task::where('user_id', Auth::id())
           ->where('status', $status)
           ->whereBetween('position', [$firstPosition, $secondPosition])
           ->decrement('position');
    }

    /**
     * @param int $id
     * @return void
     */
    public function delete(int $id)
    {
        Task::where('user_id', Auth::id())
            ->where('id', $id)
            ->delete();
    }
}
