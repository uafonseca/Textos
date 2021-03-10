<?php

namespace App\Traits;

use App\Entity\Company;
use Doctrine\ORM\Mapping as ORM;

trait CompanyEntityTrait
{
    /**
     *
     * @ORM\ManyToOne(targetEntity=Company::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=true)
     */
    private $company;

    /**
     * @return mixed
     */
    public function getCompany(): ?Company
    {
        return $this->company;
    }

    /**
     * @param mixed $company
     *
     * @return self
     */
    public function setCompany(?Company $company): self
    {
        $this->company = $company;

        return $this;
    }
}