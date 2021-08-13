<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=TaskRepository::class)
 * @ORM\Table(name="tasks")
 */
class Task
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @Groups("task")
     */
    private UuidInterface $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Daily")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     * @Groups("task")
     */
    private Daily $daily;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("task")
     */
    private User $user;

    /**
     * @ORM\Column(type="text", nullable=false)
     * @Groups("task")
     */
    private string $description;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     * @Groups("task")
     */
    private bool $done;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     * @Groups("task")
     */
    private bool $cancelled;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=false)
     * @Groups("task")
     */
    private DateTimeImmutable $createdAt;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime_immutable", nullable=true)
     * @Groups("task")
     */
    private DateTimeImmutable $updatedAt;

    public function __construct(
        Daily $daily,
        User $user,
        string $description,
        bool $isDone = false
    )
    {
        $this->id = Uuid::uuid4();
        $this->daily = $daily;
        $this->user = $user;
        $this->description = $description;
        $this->done = $isDone;

        $this->cancelled = false;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getDaily(): Daily
    {
        return $this->daily;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function isDone(): bool
    {
        return $this->done;
    }

    public function isCancelled(): bool
    {
        return $this->cancelled;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }
}