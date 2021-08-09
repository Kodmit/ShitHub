<?php

namespace App\Action\User;

class CreateUser
{
    /**
     * @Assert\Length(min=2, max=50)
     */
    private string $username;

    /**
     * @Assert\Length(min=8, max=255)
     * @Assert\NotCompromisedPassword
     */
    private string $plainPassword;

    public function __construct(string $username, string $plainPassword)
    {
        $this->username = $username;
        $this->plainPassword = $plainPassword;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPlainPassword(): string
    {
        return $this->plainPassword;
    }
}