<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`users`")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @Groups("user")
     */
    private UuidInterface $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups("user")
     */
    private string $username;

    /**
     * @ORM\Column(type="json")
     * @Groups("user")
     */
    private array $roles = [];

    /**
     * @ORM\Column(type="string")
     */
    private string $password;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime_immutable", nullable=false)
     * @Groups("user")
     */
    private DateTimeImmutable $createdAt;

    public function __construct($username)
    {
        $this->id = Uuid::uuid4();
        $this->username = $username;
    }

    public function update(string $username): void
    {
        $this->username = $username;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $hashedPassword): void
    {
        $this->password = $hashedPassword;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials(): void
    {
    }
}
