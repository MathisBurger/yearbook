<?php

namespace App\Twig\Components;


use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Entity list component
 */
#[AsTwigComponent]
class EntityList
{
    /**
     * All headers
     * NOTE: Structure must be ['name' => ?, 'id' => ?]
     *
     * @var array All headers
     */
    public array $headers;

    /**
     * All entities
     *
     * @var array All entities
     */
    public array $entities;

    /**
     * All actions on a row
     * NOTE: Structure must be ['class' => ?, 'label' => ?, 'basePath' => ??, 'linkPath' => ??]
     *
     * @var array $actions all actions
     */
    public array $actions;

    /**
     * Gets all entries
     */
    public function getEntries(): array {

        $entries = [];
        foreach ($this->entities as $entity) {
            if ($entity instanceof EntityListable) {
                $entries[] = $entity->getEntityListEntry();
            }
        }
        return $entries;
    }

    /**
     * Gets the action path
     *
     * @param string $basePath The base path of the action
     * @param int $id The ID of the entity
     * @return string The full path
     */
    public function getActionPath(string $basePath, int $id): string
    {
        return $basePath . "/" . $id;
    }

    /**
     * Checks if an array key exists
     *
     * @param string $key The key
     * @param array $array The array
     * @return bool
     */
    public function keyExists(string $key, array $array): bool
    {
        return array_key_exists($key, $array);
    }
}