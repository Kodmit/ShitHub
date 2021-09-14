<?php


namespace App\Action\Task;


use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class DeleteTask
{
    private string $taskId;

    public function __construct(string $taskId)
    {
        if (false === Uuid::isValid($taskId)) {
            throw new \DomainException('Invalid task ID');
        }

        $this->taskId = $taskId;
    }

    public function getTaskId(): UuidInterface
    {
        return Uuid::fromString($this->taskId);
    }
}