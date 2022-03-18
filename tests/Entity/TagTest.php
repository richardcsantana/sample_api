<?php

namespace App\Tests\Entity;

use App\Entity\Tag;
use PHPUnit\Framework\TestCase;

class TagTest extends TestCase
{

    public function testJsonSerialize(): void
    {

        $tag = new Tag('test', 1);

        $result = $tag->jsonSerialize();

        $this->assertNotNull($result);
        $this->assertNotEmpty($result);
        $this->assertEquals('test', $result['description']);
        $this->assertEquals(1, $result['id']);
    }
}
