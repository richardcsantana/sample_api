<?php

namespace App\Helper;

use App\Entity\Tag;
use JsonException;

class TagHelper
{
    /**
     * @throws JsonException
     */
    public function createFromJson(string $json): Tag
    {
        $jsonObject = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        if (array_key_exists("id", $jsonObject)) {
            return new Tag($jsonObject['description'], $jsonObject['id']);
        }
        return new Tag($jsonObject['description']);
    }

}