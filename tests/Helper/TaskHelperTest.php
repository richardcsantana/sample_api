<?php

namespace App\Tests\Helper;

use App\Entity\Project;
use App\Entity\Tag;
use App\Helper\TaskHelper;
use App\Repository\ProjectRepository;
use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class TaskHelperTest extends TestCase
{

    /**
     * @throws \JsonException
     */
    public function testCreateFromJson(): void
    {

        $json = '{
                    "description": "test",
                    "id": 1,
                    "project": {"description": "testProject", "id": 1},
                    "tags": [
                        {"description": "testTag", "id": 1}
                    ]
                 }';

        $tagRepositoryStub = $this->createMock(TagRepository::class);
        $projectRepositoryStub = $this->createMock(ProjectRepository::class);
        $tags = array(new Tag('testTag', 1));
        $project = new Project('testTag', 1);
        $tagRepositoryStub->method('getByIdIn')->willReturn($tags);
        $projectRepositoryStub->method('getById')->willReturn($project);


        $helper = new TaskHelper($tagRepositoryStub, $projectRepositoryStub);

        $result = $helper->createFromJson($json);

        $this->assertNotNull($result);
        $this->assertEquals('test', $result->getDescription());
        $this->assertEquals('1', $result->getId());
        $this->assertEquals(new ArrayCollection($tags), $result->getTags());
        $this->assertEquals($project, $result->getProject());

    }
}
