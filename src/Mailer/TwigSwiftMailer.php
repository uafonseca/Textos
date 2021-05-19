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

      /**
     * @param array               $context
     * @param string              $to
     * @param Address|string|null $from
     */
    public function sendMessage(string $templateName, $context, $to, $from = null)
    {
        if (!$from) {
            $from = new Address(
                $this->params->get('address'),
                $this->params->get('sender_name')
            );
        }

        $template = $this->twig->load($templateName);
        $subject = $template->renderBlock('subject', $context);

        $html = $this->twig->render($templateName, $context);
        $htmlBody = CssInliner::fromHtml($html)->inlineCss()->render();

        $message = (new Email())
            ->subject($subject)
            ->from($from)
            ->to($to)
            ->html($htmlBody);

        $this->mailer->send($message);
    }

    
    public function sendWelcomeEmailMessage(User $user)
    {
        $template = 'mail/user_data.html.twig';

        $context = [
            'user' => $user,
        ];

        $this->sendMessage($template, $context, $user->getEmail());
    }
   
}
