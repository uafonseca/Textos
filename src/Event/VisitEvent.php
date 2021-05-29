<?php

namespace App\Event;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\EventDispatcher\Event;

class VisitEvent extends Event
{

    private UserInterface $user;

    private array $routeParams;


     
    /**
     * Method __construct
     *
     * @param UserInterface $user [explicite description]
     * @param array $routeParams [explicite description]
     *
     */
    public function __construct(UserInterface $user, array $routeParams)
    {
        $this->user = $user;
        $this->routeParams = $routeParams;
    }

    /**
     * Get the value of user
     *
     * @return UserInterface
     */
    public function getUser(): UserInterface
    {
        return $this->user;
    }

    /**
     * Set the value of user
     *
     * @param UserInterface $user
     *
     * @return self
     */
    public function setUser(UserInterface $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get the value of routeParams
     *
     * @return array
     */
    public function getRouteParams(): array
    {
        return $this->routeParams;
    }

    /**
     * Set the value of routeParams
     *
     * @param array $routeParams
     *
     * @return self
     */
    public function setRouteParams(array $routeParams): self
    {
        $this->routeParams = $routeParams;

        return $this;
    }
}