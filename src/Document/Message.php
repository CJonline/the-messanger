<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations AS MongoDB;

/**
 * @MongoDB\Document(collection="message", repositoryClass=App\Repository\MessageRepository::class)
 */
class Message
{
    /**
     * @MongoDB\Id(strategy="auto")
     */
    private $id;

    /**
     * @MongoDB\Field(name="context")
     */
    private $content;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }
}
