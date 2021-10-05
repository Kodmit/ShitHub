<?php


namespace App\Action\Task;


use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class ToggleTask
{
    private string $taskId;
    private bool $isDone;
    private ?\DateTimeImmutable $doneAt;

    public function __construct(string $taskId, bool $isDone, string $doneAt = null)
    {
        if (false === Uuid::isValid($taskId)) {
            throw new \DomainException('Invalid UUID');
        }

        if (null !== $doneAt) {
            $doneAt = new \DateTimeImmutable($doneAt);
        }

        $this->taskId = $taskId;
        $this->isDone = $isDone;
        $this->doneAt = $doneAt;
    }

    public function getTaskId(): UuidInterface
    {
        return Uuid::fromString($this->taskId);
    }

    public function isDone(): bool
    {
        return $this->isDone;
    }

    public function getDoneAt(): ?\DateTimeImmutable
    {
        return $this->doneAt;
    }
}