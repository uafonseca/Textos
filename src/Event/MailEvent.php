<?php

namespace App\Event;

use App\Entity\Mail;
use Symfony\Contracts\EventDispatcher\Event;

class MailEvent extends Event
{
    /**
     * @var Mail
     */
    private Mail $mail;

    /**
     * MailEvent constructor.
     * @param Mail $mail
     */
    public function __construct(Mail $mail)
    {
        $this->mail = $mail;
    }

    public function getMail(): Mail
    {
        return $this->mail;
    }

    public function setMail(Mail $mail): void
    {
        $this->mail = $mail;
    }
}