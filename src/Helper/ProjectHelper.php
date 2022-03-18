<?php

namespace App\Helper;

use App\Entity\Project;
use JsonException;

class ProjectHelper
{
    /**
     * @throws JsonException
     */
    public function createFromJson(string $json): Project
    {
        $jsonObject = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        if (array_key_exists("id", $jsonObject)) {
            return new Project($jsonObject['description'], $jsonObject['id']);
        }
        return new Project($jsonObject['description']);
    }

}