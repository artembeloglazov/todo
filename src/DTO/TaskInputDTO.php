<?php

namespace App\DTO;

use DateTimeImmutable;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class TaskInputDTO
{

    public function __construct(
        #[Assert\Length(max: 32)]
        public ?string $title = '',

        #[Assert\Length(max: 32)]
        public ?string $description = '',

        #[Assert\DateTime]
        public ?string $dateDue = null,
    )
    {
    }

    /**
     * @throws Exception
     */
    public static function fromRequest(Request $request): TaskInputDTO
    {
        return new self(
            title: $request->request->get('title'),
            description: $request->request->get('description'),
            dateDue: $request->request->get('dateDue')
                ? (new DateTimeImmutable($request->request->get('dateDue')))->format('Y-m-d H:i:s')
                : null,
        );
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getDateDue(): ?string
    {
        return $this->dateDue;
    }
}