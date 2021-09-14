<?php


namespace App\Action\Task;


use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class ToggleTask
{
    private string $taskId;
    private bool $isDone;

    public function __construct(string $taskId, bool $isDone)
    {
        if (false === Uuid::isValid($taskId)) {
            throw new \DomainException('Invalid UUID');
        }

        $this->taskId = $taskId;
        $this->isDone = $isDone;
    }

    public function getTaskId(): UuidInterface
    {
        return Uuid::fromString($this->taskId);
    }

    public function isDone(): bool
    {
        return $this->isDone;
    }
}