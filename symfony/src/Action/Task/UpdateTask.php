<?php


namespace App\Action\Task;


use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class UpdateTask
{
    private string $taskId;
    private string $description;

    public function __construct(string $taskId, string $description)
    {
        if (false === Uuid::isValid($taskId)) {
            throw new \DomainException('Invalid task ID');
        }

        $this->taskId = $taskId;
        $this->description = $description;
    }

    public function getTaskId(): UuidInterface
    {
        return Uuid::fromString($this->taskId);
    }

    public function getDescription(): string
    {
        return $this->description;
    }
}