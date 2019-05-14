<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations AS MongoDB;

/**
 * @MongoDB\Document(collection="message", repositoryClass=App\Repository\MessageRepository::class)
 */
class Message
{
    /**
     * @MongoDB\Id(strategy="auto", type="string")
     */
    private $id;

    /**
     * @MongoDB\Field(name="content", type="string")
     */
    private $content;

    /**
     * @MongoDB\Field(name="title", type="string")
     */
    private $title;

    /**
     * @MongoDB\Field(name="date", type="date")
     */
    private $date;

    public function getId(): ?string
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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(?\DateTime $date): self
    {
        $this->date = $date;

        return $this;
    }
}
