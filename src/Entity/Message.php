<?php

namespace App\Entity;

use Doctrine\ODM\MongoDB\Mapping\Annotations AS MongoDB;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @MongoDB\Document()
 */
class Message
{
    /**
     * @MongoDB\Id(strategy="auto")
     */
    private $id;

    /**
     * @MongoDB\Field(name="context")
     * @Assert\NotBlank()
     */
    private $context;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContext(): ?string
    {
        return $this->context;
    }

    public function setContext(?string $context): self
    {
        $this->context = $context;

        return $this;
    }
}
