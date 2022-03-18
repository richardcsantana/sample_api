<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Entity()
 */
class Task implements JsonSerializable
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
     * @ORM\ManyToMany(targetEntity="Tag"))
     * @ORM\JoinTable(name="tasks_tags",
     *     joinColumns={@ORM\JoinColumn(name="task_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id")})
     */
    private Collection $tags;

    /**
     * @ORM\ManyToOne(targetEntity="Project")
     */
    private Project $project;

    /**
     * @param string $description
     * @param ArrayCollection $tags
     * @param Project $project
     * @param int|null $id
     */
    public function __construct(string $description, Collection $tags, Project $project, int $id = null)
    {
        $this->id = $id;
        $this->description = $description;
        $this->tags = $tags;
        $this->project = $project;
    }

    /**
     * @return int
     */
    public function getId(): int
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


    /**
     * @return Collection
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    /**
     * @return Project
     */
    public function getProject(): Project
    {
        return $this->project;
    }

    public function jsonSerialize(): array
    {
        $tags = array();
        foreach ($this->getTags() as $tag) {
            $tags[] = $tag->jsonSerialize();
        }
        return array(
            'id' => $this->getId(),
            'description' => $this->getDescription(),
            'tags' => $tags,
            'project' => $this->getProject()->jsonSerialize()
        );
    }
}