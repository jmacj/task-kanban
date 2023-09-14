<?php

namespace App\Services;

use App\Repositories\TaskRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

/**
 * TaskService
 */
class TaskService
{
    /**
     * @var TaskRepository
     */
    private $taskRepository;

    /**
     * @param TaskRepository $taskRepository
     */
    public function __construct(TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    /**
     * @param array $task
     * @return mixed
     */
    public function saveTask(array $task)
    {
        $task['user_id'] = Auth::id();
        return $this->taskRepository->create($task);
    }

    /**
     * @return Collection
     */
    public function getAllTasks(): Collection
    {
        return collect($this->taskRepository->all())->groupBy('status');
    }

    /**
     * @param string $id
     * @param array $parameters
     * @return false|mixed
     */
    public function updateTask(string $id, array $parameters)
    {
        $task = $this->taskRepository->find($id);

        $this->updatePosition($task, $parameters);

        if ($task) {
            return $this->taskRepository->update($task, $parameters);
        }

        return false;
    }

    /**
     * @param $task
     * @param array $parameters
     * @return void
     */
    private function updatePosition($task, array $parameters) {
        $oldStatus = $task->status;
        $oldPosition = $task->position;
        $newStatus = $parameters['status'];
        $newPosition = $parameters['position'];

        if ($newStatus !== $oldStatus) {
            $this->taskRepository->decrementPosition($oldStatus, $oldPosition);
            $this->taskRepository->incrementPosition($newStatus, $newPosition);
        } else {
            if ($newPosition > $oldPosition) {
                $this->taskRepository->decrementPositionInBetween($oldStatus, $oldPosition + 1, $newPosition);
            } else {
                $this->taskRepository->incrementPositionInBetween($oldStatus, $newPosition, $oldPosition - 1);
            }
        }
    }

    /**
     * @param string $id
     * @return bool
     */
    public function deleteTask(string $id): bool
    {
        $task = $this->taskRepository->find($id);
        $status = $task->status;
        $position = $task->position;
        if ($task) {
            if ($task->delete($id)) {
                $this->taskRepository->decrementPosition($status, $position);
                return true;
            }
            return false;
        }

        return false;
    }
}
