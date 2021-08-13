<?php

namespace App\Entity;

use App\Repository\DailyRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=DailyRepository::class)
 * @ORM\Table(name="dailys")
 */
class Daily
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @Groups("daily")
     */
    private UuidInterface $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     * @Groups("daily")
     */
    private User $user;

    /**
     * @ORM\Column(type="text", nullable=false)
     * @Groups("daily")
     */
    private string $description;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime_immutable", nullable=false)
     * @Groups("daily")
     */
    private DateTimeImmutable $createdAt;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime_immutable", nullable=true)
     * @Groups("daily")
     */
    private DateTimeImmutable $updatedAt;

    public function __construct(User $user, string $description)
    {
        $this->id = Uuid::uuid4();
        $this->user = $user;
        $this->description = $description;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getDescription(): string
    {
        return $this->description;
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
