<?php

namespace App\Action\Daily;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class CreateDaily
{
    private string $userId;
    private string $description;

    public function __construct(string $userId, string $description)
    {
        if (false === Uuid::isValid($userId)) {
            throw new \DomainException('Invalid UUID');
        }

        $this->userId = $userId;
        $this->description = $description;
    }

    public function getUserId(): UuidInterface
    {
        return Uuid::fromString($this->userId);
    }

    public function getDescription(): string
    {
        return $this->description;
    }
}