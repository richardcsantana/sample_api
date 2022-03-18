<?php

namespace App\Helper;

use App\Entity\Task;
use App\Repository\ProjectRepository;
use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use JsonException;

class TaskHelper
{
    private TagRepository $tagRepository;
    private ProjectRepository $projectRepository;

    /**
     * @param TagRepository $tagRepository
     * @param ProjectRepository $projectRepository
     */
    public function __construct(TagRepository $tagRepository, ProjectRepository $projectRepository)
    {
        $this->tagRepository = $tagRepository;
        $this->projectRepository = $projectRepository;
    }


    /**
     * @throws JsonException
     */
    public function createFromJson(string $json): Task
    {
        $jsonObject = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        $tagsIds = array();
        foreach($jsonObject['tags'] as $tag){
            $tagsIds[] = $tag['id'];
        }
        $tags = new ArrayCollection($this->tagRepository->getByIdIn($tagsIds));
        $project = $this->projectRepository->getById($jsonObject['project']['id']);
        if (array_key_exists("id", $jsonObject)) {
            return new Task($jsonObject['description'], $tags, $project, $jsonObject['id']);
        }
        return new Task($jsonObject['description'], $tags, $project);
    }
}