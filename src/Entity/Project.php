<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Project
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string")
     */
    private string $description;

    /**
     * @param string $description
     * @param int|null $id
     */
    public function __construct(string $description, int $id = null)
    {
        $this->id = $id;
        $this->description = $description;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }


    public function jsonSerialize(): array
    {
        return array(
            'id' => $this->getId(),
            'description' => $this->getDescription()
        );
    }
}