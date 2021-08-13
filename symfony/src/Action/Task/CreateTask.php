<?php

namespace App\Action\Task;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class CreateTask
{
    private string $dailyId;
    private string $userId;
    private string $description;
    private bool $done;

    public function __construct(
        string $dailyId,
        string $userId,
        string $description,
        bool $done = false
    )
    {
        if (false === Uuid::isValid($userId) || false === Uuid::isValid($dailyId)) {
            throw new \DomainException('Invalid UUID');
        }

        $this->dailyId = $dailyId;
        $this->userId = $userId;
        $this->description = $description;
        $this->done = $done;
    }

    public function getDailyId(): UuidInterface
    {
        return Uuid::fromString($this->dailyId);
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