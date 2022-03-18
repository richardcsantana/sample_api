<?php

namespace App\Tests\Entity;

use App\Entity\Project;
use App\Entity\Tag;
use App\Entity\Task;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class ProjectTest extends TestCase
{

    public function testJsonSerialize(): void
    {

        $project = new Project('test', 1);

        $result = $project->jsonSerialize();

        $this->assertNotNull($result);
        $this->assertNotEmpty($result);
        $this->assertEquals('test', $result['description']);
        $this->assertEquals(1, $result['id']);
    }
}
