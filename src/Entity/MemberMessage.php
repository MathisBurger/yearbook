<?php

namespace App\Entity;

use App\Repository\MemberMessageRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * A message on a member of a course
 */
#[ORM\Entity(repositoryClass: MemberMessageRepository::class)]
class MemberMessage extends AbstractEntity
{

    /**
     * @var string|null The actual message
     */
    #[ORM\Column(type: "text")]
    private ?string $message = null;

    /**
     * @var CourseMember|null The course member
     */
    #[ORM\ManyToOne(targetEntity: CourseMember::class, inversedBy: "messages")]
    private ?CourseMember $member = null;

    /**
     * Gets the message
     *
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * Sets the message
     *
     * @param string $message The new message
     * @return $this
     */
    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Gets the member of a message
     *
     * @return CourseMember|null
     */
    public function getMember(): ?CourseMember {
        return $this->member;
    }

    /**
     * Sets the member of message
     *
     * @param CourseMember|null $member The new member
     * @return $this
     */
    public function setMember(?CourseMember $member): self {
        $this->member = $member;
        return $this;
    }
}
