<?php

namespace App\Entity;

use App\Repository\VacationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VacationRepository::class)]
class Vacation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'vacations')]
    private ?Personal $personal = null;

    #[ORM\ManyToOne(inversedBy: 'vacations')]
    private ?Holidays $holidays = null;

    #[ORM\OneToMany(mappedBy: 'vacation', targetEntity: Personal::class)]
    private Collection $personals;

    public function __construct()
    {
        $this->personals = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function __toString() {
        return $this ->id;
    
    }

    public function getPersonal(): ?Personal
    {
        return $this->personal;
    }

    public function setPersonal(?Personal $personal): self
    {
        $this->personal = $personal;

        return $this;
    }
    

    public function getHolidays(): ?Holidays
    {
        return $this->holidays;
    }

    public function setHolidays(?Holidays $holidays): self
    {
        $this->holidays = $holidays;

        return $this;
    }
    

    /**
     * @return Collection<int, Personal>
     */
    public function getPersonals(): Collection
    {
        return $this->personals;
    }

    public function addPersonal(Personal $personal): self
    {
        if (!$this->personals->contains($personal)) {
            $this->personals->add($personal);
            $personal->setVacation($this);
        }

        return $this;
    }

    public function removePersonal(Personal $personal): self
    {
        if ($this->personals->removeElement($personal)) {
            // set the owning side to null (unless already changed)
            if ($personal->getVacation() === $this) {
                $personal->setVacation(null);
            }
        }

        return $this;
    }
}
