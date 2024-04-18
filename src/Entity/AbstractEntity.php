<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\MappedSuperclass]
abstract class AbstractEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected ?int $id = null;

    /**
     * Gets the ID of the user
     *
     * @return int|null The ID of the user
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    public static function valueToString(mixed $value): string
    {
        if (is_array($value)) {
            return implode(", ", $value);
        }
        return (string)$value;
    }
}