<?php

namespace App\Action\Task;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class CreateTask
{
    private string $userId;
    private string $description;
    private bool $done;

    public function __construct(
        string $userId,
        string $description,
        bool $done = false
    )
    {
        if (false === Uuid::isValid($userId)) {
            throw new \DomainException('Invalid UUID');
        }

        $this->userId = $userId;
        $this->description = $description;
        $this->done = $done;
    }

    public function getUserId(): UuidInterface
    {
        return Uuid::fromString($this->userId);
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function isDone(): bool
    {
        return $this->done;
    }
}