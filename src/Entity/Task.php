<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
#[ORM\Index(columns: ['title'], name: 'task__title__ind')]
#[ORM\HasLifecycleCallbacks]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255)]
    private string $title;

    #[ORM\Column(type: Types::TEXT)]
    private string $description;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $dateStart = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $dateDue = null;

    #[ORM\ManyToOne(inversedBy: 'tasks')]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $assigned = null;

    #[ORM\ManyToOne(inversedBy: 'tasks')]
    private ?Calendar $calendar = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDateDue(): ?DateTimeInterface
    {
        return $this->dateDue;
    }

    public function setDateDue(DateTimeInterface $dateDue): static
    {
        $this->dateDue = $dateDue;

        return $this;
    }

    public function getAssigned(): ?User
    {
        return $this->assigned;
    }

    public function setAssigned(?User $assigned): static
    {
        $this->assigned = $assigned;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'dateDue' => $this->getDateDue()?->format('Y-m-d H:i:s'),
            'user' => $this->getAssigned()?->toArray()
        ];
    }

    public function setId(int $id): Task
    {
        $this->id = $id;
        return $this;
    }

    public function getDateStart(): ?DateTimeInterface
    {
        return $this->dateStart;
    }

    public function setDateStart(?DateTimeInterface $dateStart): static
    {
        $this->dateStart = $dateStart;

        return $this;
    }

    public function getCalendar(): ?Calendar
    {
        return $this->calendar;
    }

    public function setCalendar(?Calendar $calendar): static
    {
        $this->calendar = $calendar;

        return $this;
    }
}
