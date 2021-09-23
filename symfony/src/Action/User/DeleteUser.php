<?php


namespace App\Action\User;


use Ramsey\Uuid\Uuid;

class DeleteUser
{
    private string $userId;

    public function __construct(string $userId)
    {
        if (false === Uuid::isValid($userId)) {
            throw new \DomainException('Invalid UUID');
        }

        $this->userId = $userId;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }
}