<?php


namespace App\Action\User;


use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class UpdateUser
{
    private string $userId;
    private string $username;

    public function __construct(string $userId, string $username)
    {
        if (false === Uuid::isValid($userId)) {
            throw new \DomainException('Invalid UUID');
        }

        $this->userId = $userId;
        $this->username = $username;
    }

    public function getUserId(): UuidInterface
    {
        return Uuid::fromString($this->userId);
    }

    public function getUsername(): string
    {
        return $this->username;
    }
}