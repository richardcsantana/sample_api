<?php

namespace App\Tests\Helper;

use App\Helper\TagHelper;
use PHPUnit\Framework\TestCase;

class TagHelperTest extends TestCase
{

    public function testCreateFromJson()
    {

        $json = '{"description": "test", "id": 1}';

        $helper = new TagHelper();

        $result = $helper->createFromJson($json);

        $this->assertNotNull($result);
        $this->assertEquals('test', $result->getDescription());
        $this->assertEquals('1', $result->getId());
    }
}
