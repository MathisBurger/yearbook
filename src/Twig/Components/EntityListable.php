<?php

namespace App\Twig\Components;

interface EntityListable
{
    /**
     * Gets the entity list entry
     *
     * @return array The entry as array
     */
    function getEntityListEntry(): array;

    /**
     * Gets all headers
     *
     * @return array All headers
     */
    static function getHeaders(): array;
}