<?php

namespace App\Entity;

use App\Repository\CourseMemberRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * A member of a course
 */
#[ORM\Entity(repositoryClass: CourseMemberRepository::class)]
class CourseMember extends AbstractEntity
{
    /**
     * A student
     */
    public const ROLE_STUDENT = 'ROLE_STUDENT';
    /**
     * A professor
     */
    public const ROLE_PROFESSOR = 'ROLE_PROFESSOR';

    /**
     * @var string|null The name of the member
     */
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var string|null The role of the member
     */
    private ?string $role = null;

    /**
     * @var Course|null The course of the member
     */
    #[ORM\ManyToOne(targetEntity: Course::class, inversedBy: 'members')]
    private ?Course  $course = null;

    /**
     * @var Collection|ArrayCollection The messages of the member
     */
    #[ORM\OneToMany(targetEntity: MemberMessage::class, mappedBy: 'member', cascade: ['persist', 'remove'])]
    private Collection $messages;

    public function __construct() {
        $this->messages = new ArrayCollection();
    }

    /**
     * Gets the name of the member
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Sets the name of the member
     *
     * @param string $name The new name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Gets the role of the member
     *
     * @return string|null
     */
    public function getRole(): ?string {
        return $this->role;
    }

    /**
     * Sets the role of the member
     *
     * @param string $role The new role
     * @return $this
     */
    public function setRole(string $role): self {
        $this->role = $role;
        return $this;
    }

    /**
     * Gets the course
     *
     * @return Course|null
     */
    public function getCourse(): ?Course {
        return $this->course;
    }

    /**
     * Sets the course of the user
     *
     * @param Course|null $course The new course
     * @return $this
     */
    public function setCourse(?Course  $course): self {
        $this->course = $course;
        return $this;
    }

    /**
     * Gets all messages
     *
     * @return Collection
     */
    public function getMessages(): Collection {
        return $this->messages;
    }

    /**
     * Adds a message to the member
     *
     * @param MemberMessage $message The message
     * @return $this
     */
    public function addMessage(MemberMessage $message): self {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
        }
        return $this;
    }

    /**
     * Removes a message from the user
     *
     * @param MemberMessage $message The message
     * @return $this
     */
    public function removeMessage(MemberMessage $message): self {
        if ($this->messages->contains($message)) {
            $this->messages->removeElement($message);
        }
        return $this;
    }
}
