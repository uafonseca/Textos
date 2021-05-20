<?php

namespace App\Event;

use App\Entity\UserGroup;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\EventDispatcher\Event;

class UserGroupEvent extends Event
{
    /**
     * @var UserGroup
     */
    private UserGroup $group;

    private UserInterface $user;

    /**
     * UserEvent constructor.
     * @param UserGroup $group;
     */
    public function __construct(UserGroup $group, UserInterface $user)
    {
        $this->group = $group;
        $this->user = $user;
    }

    public function getGroup(): UserGroup
    {
        return $this->group;
    }

    public function settGroup(UserGroup $group): void
    {
        $this->group = $group;
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
}