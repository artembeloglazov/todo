<?php

namespace App\Entity;

use App\Repository\CalendarRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JetBrains\PhpStorm\Pure;

#[ORM\Entity(repositoryClass: CalendarRepository::class)]
class Calendar {
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column]
	private ?int $id = null;

	#[ORM\Column]
	private ?bool $isExport = null;

	#[ORM\Column(length: 255, nullable: true)]
	private ?string $fileName = null;

	#[ORM\OneToMany(mappedBy: 'calendar', targetEntity: Task::class)]
	private Collection $tasks;

	#[ORM\Column(length: 255)]
	private ?string $name = null;

	#[ORM\Column(name: 'created_at', type: 'datetime', nullable: false)]
	#[Gedmo\Timestampable(on: 'create')]
	private DateTime $createdAt;

	#[ORM\Column(name: 'updated_at', type: 'datetime', nullable: false)]
	#[Gedmo\Timestampable(on: 'update')]
	private DateTime $updatedAt;

	#[Pure] public function __construct() {
		$this->tasks = new ArrayCollection();
	}

	public function getId(): ?int {
		return $this->id;
	}

	public function isIsExport(): ?bool {
		return $this->isExport;
	}

	public function setIsExport(bool $isExport): static {
		$this->isExport = $isExport;

		return $this;
	}

	public function getFileName(): ?string {
		return $this->fileName;
	}

	public function setFileName(?string $fileName): static {
		$this->fileName = $fileName;

		return $this;
	}

	/**
	 * @return Collection<int, Task>
	 */
	public function getTasks(): Collection {
		return $this->tasks;
	}

	public function addTask(Task $task): static {
		if (!$this->tasks->contains($task)) {
			$this->tasks->add($task);
			$task->setCalendar($this);
		}

		return $this;
	}

	public function removeTask(Task $task): static {
		if ($this->tasks->removeElement($task)) {
			// set the owning side to null (unless already changed)
			if ($task->getCalendar() === $this) {
				$task->setCalendar(null);
			}
		}

		return $this;
	}

	public function getName(): ?string {
		return $this->name;
	}

	public function setName(string $name): static {
		$this->name = $name;

		return $this;
	}

	public function __toString(): string {
		return $this->getName();
	}

	public function getCreatedAt(): DateTime {
		return $this->createdAt;
	}

	public function setCreatedAt(): static {
		$this->createdAt = DateTime::createFromFormat('U', (string)time());

		return $this;
	}

	public function getUpdatedAt(): DateTime {
		return $this->updatedAt;
	}

	public function setUpdatedAt(): static {
		$this->updatedAt = DateTime::createFromFormat('U', (string)time());

		return $this;
	}
}
