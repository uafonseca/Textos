<?php

namespace App\EventListener;

use App\AppEvents;
use App\Entity\User;
use App\Event\UserEvent;
use App\Mailer\TwigSwiftMailer;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\User\UserInterface;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserListener implements EventSubscriberInterface
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
            AppEvents::SEND_DATA_REQUEST => 'onSendDataRequest',
        ];
    }

    /**
     * Pre persist listener based on doctrine common.
     *
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args): void
    {
        $object = $args->getObject();

        if ($object instanceof User) {
            $this->updateUserFields($object);
        }
    }

    /**
     * Updates the user properties.
     *
     * @param User $user
     */
    private function updateUserFields(User $user): void
    {
        // $this->canonicalFieldsUpdater->updateCanonicalFields($user);
        // $this->passwordUpdater->hashPassword($user);
    }

    /**
     * Pre update listener based on doctrine common.
     *
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        $object = $args->getObject();

        if ($object instanceof User) {
            // $this->updateUserFields($object);
            $this->recomputeChangeSet($args->getEntityManager(), $object);
        }
    }

    /**
     * Recomputes change set for Doctrine implementations not doing it automatically after the event.
     *
     * @param EntityManagerInterface $om
     * @param UserInterface $user
     */
    private function recomputeChangeSet(EntityManagerInterface $om, UserInterface $user)
    {
        $meta = $om->getClassMetadata(get_class($user));

        if ($om instanceof EntityManager) {
            $om->getUnitOfWork()->recomputeSingleEntityChangeSet($meta, $user);

            return;
        }
    }


    public function onSendDataRequest(UserEvent $userEvent){
        $this->mailer->sendWelcomeEmailMessage($userEvent->getUser());
    }
}