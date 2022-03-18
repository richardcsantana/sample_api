<?php

namespace App\Tests\Entity;

use App\Entity\Project;
use App\Entity\Tag;
use App\Entity\Task;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{

    public function testJsonSerialize(): void
    {
        $tags = array(new Tag('testTag', 1));
        $task = new Task('test', new ArrayCollection($tags), new Project("testProject", 1), 1);

        $result = $task->jsonSerialize();

        $this->assertNotNull($result);
        $this->assertNotEmpty($result);
        $this->assertEquals('test', $result['description']);
        $this->assertArrayHasKey('tags', $result);
        $this->assertNotEmpty($result['tags']);
        $this->assertArrayHasKey('project', $result);
        $this->assertNotEmpty($result['project']);
        $this->assertEquals(1, $result['id']);
    }
}
