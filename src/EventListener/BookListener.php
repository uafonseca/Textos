<?php

namespace App\EventListener;

use App\Entity\Book;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class BookListener implements EventSubscriberInterface
{

    private TokenStorageInterface $tokenStorage;

    private EntityManager $em;

    public function __construct(
        TokenStorageInterface $tokenStorage
    ) {
        $this->tokenStorage = $tokenStorage;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'prePersist',
            'preUpdate',
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

        if ($object instanceof Book) {
            $this->updateBookFields($object);
        }
    }

    /**
     * Pre persist listener based on doctrine common.
     *
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args): void
    {
        $object = $args->getObject();

        if ($object instanceof Book) {
            $this->updateBookFields($object,'update');
        }
    }


    
    /**
     * Pre update listener based on doctrine common.
     *
     * @param LifecycleEventArgs $args
     */
    public function updateBookFields(Book $book, $action = 'persist'){
        $loggedUser = $this->tokenStorage->getToken()->getUser();
        if($action === 'persist'){
            $book->setCreatedBy($loggedUser);
        }else{
            $book->setUpdatedBy($loggedUser);
        }
    }
}
