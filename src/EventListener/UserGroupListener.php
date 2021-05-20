<?php

namespace App\EventListener;

use App\AppEvents;
use App\Entity\User;
use App\Event\UserEvent;
use App\Event\UserGroupEvent;
use App\Mailer\TwigSwiftMailer;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\User\UserInterface;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserGroupListener implements EventSubscriberInterface
{
    private TokenStorageInterface $tokenStorage;
    private TwigSwiftMailer $mailer;


    public function __construct(
        TokenStorageInterface $tokenStorage,
        TwigSwiftMailer $mailer
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->mailer = $mailer;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            AppEvents::SEND_DATA_COURSE => 'onSendDataCurse',
        ];
    }


    public function onSendDataCurse(UserGroupEvent $userGroupEvent){
        $this->mailer->sendCourseEmailMessage($userGroupEvent->getUser(), $userGroupEvent->getGroup());
    }
}