<?php

namespace App\Entity;

use App\Repository\CourseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * A course that is registered
 */
#[ORM\Entity(repositoryClass: CourseRepository::class)]
class Course extends AbstractEntity
{
    /**
     * The name of the course
     */
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * The description of the course
     */
    #[ORM\Column(length: 255)]
    private ?string $description = null;

    /**
     * All members of the course
     */
    #[ORM\OneToMany(targetEntity: CourseMember::class, mappedBy: 'course', cascade: ['persist', 'remove'])]
    private Collection $members;

    public function __construct() {
        $this->members = new ArrayCollection();
    }

    /**
     * Gets the name of the course
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Sets the name of the course
     *
     * @param string $name The new name
     * @return $this
     */
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets the description of the course
     *
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Sets the description of the course
     *
     * @param string $description The description of the course
     * @return $this
     */
    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Gets all members
     *
     * @return Collection All members
     */
    public function getMembers(): Collection {
        return $this->members;
    }

    /**
     * Adds a member to the collection
     *
     * @param CourseMember $member The new member
     * @return $this
     */
    public function addMember(CourseMember $member): self {
        if (!$this->members->contains($member)) {
            $this->members[] = $member;
        }
        return $this;
    }

    /**
     * Removes a member from the collection
     *
     * @param CourseMember $member The removed member
     * @return $this
     */
    public function removeMember(CourseMember $member): self {
        if ($this->members->contains($member)) {
            $this->members->removeElement($member);
        }
        return $this;
    }
}
