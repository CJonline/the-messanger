<?php

namespace App\Message;

class EmailNotification
{
    private $content;

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     *
     * @return EmailNotification
     */
    public function setContent(string $content): EmailNotification
    {
        $this->content = $content;

        return $this;
    }
}