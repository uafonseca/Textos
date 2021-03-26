<?php

namespace App\Mailer;

use App\Entity\User;
use Pelago\Emogrifier\CssInliner;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;


class TwigSwiftMailer
{
    /**
     * @var MailerInterface
     */
    protected $mailer;

    /**
     * @var UrlGeneratorInterface
     */
    protected $router;

    /**
     * @var Environment
     */
    protected $twig;

    /**
     * @var array
     */
    protected $params;

    /**
     * TwigSwiftMailer constructor.
     */
    public function __construct(MailerInterface $mailer, UrlGeneratorInterface $router, Environment $twig, ParameterBagInterface $params)
    {
        $this->mailer = $mailer;
        $this->router = $router;
        $this->twig = $twig;
        $this->params = $params;
    }

  
   
}
