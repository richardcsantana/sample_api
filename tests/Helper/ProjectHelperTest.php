<?php

namespace App\Tests\Helper;

use App\Helper\ProjectHelper;
use PHPUnit\Framework\TestCase;

class ProjectHelperTest extends TestCase
{

    public function testCreateFromJson()
    {

        $json = '{"description": "test", "id": 1}';

        $helper = new ProjectHelper();

        $result = $helper->createFromJson($json);

        $this->assertNotNull($result);
        $this->assertEquals('test', $result->getDescription());
        $this->assertEquals('1', $result->getId());
    }
}
