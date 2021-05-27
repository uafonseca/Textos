<?php

namespace App\Mailer;

use App\Entity\Image;
use App\Entity\Mail;
use App\Entity\User;
use App\Entity\UserGroup;
use Pelago\Emogrifier\CssInliner;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;


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

    private UploaderHelper $vich;

    protected $requestStack;

    private $kernel;
    /**
     * TwigSwiftMailer constructor.
     */
    public function __construct(MailerInterface $mailer,
        UrlGeneratorInterface $router,
        Environment $twig,
        ParameterBagInterface $params,
        UploaderHelper $vich,
        RequestStack $requestStack,
        KernelInterface $kernel
      )
    {
        $this->mailer = $mailer;
        $this->router = $router;
        $this->twig = $twig;
        $this->params = $params;
        $this->vich = $vich;
        $this->requestStack = $requestStack;
        $this->kernel = $kernel;
    }

      /**
     * @param array               $context
     * @param string              $to
     * @param Address|string|null $from
     */
    public function sendMessage(string $templateName, $context, $to, $from = null, $attached = null)
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
            ->html($htmlBody)
            ;
        if( null != $attached){
            if ($attached instanceof Image) {

                $filePath = $this->kernel->getProjectDir() .'/public'. $this->vich->asset($attached,'imagenFile');
                $message->attachFromPath($filePath, $attached->getImagenName(), $attached->getMimeType());
            }else{
                $message->attachFromPath($attached);
            }
                 
        }

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

    public function sendCourseEmailMessage(User $user, UserGroup $group)
    {
        $template = 'mail/course_welcome.html.twig';

        $context = [
            'user' => $user,
            'group' => $group
        ];

        $this->sendMessage($template, $context, $user->getEmail());
    }

    public function sendPersonalEmailMessage(User $user, Mail $mail)
    {
        $template = 'mail/personal-message.html.twig';

        $context = [
            'user' => $user,
            'mail' => $mail
        ];

        $this->sendMessage($template, $context, $user->getEmail(), null, $mail->getAttached());
    }
   
}
