<?php

namespace App\Entity;

use App\Repository\HolidaysRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HolidaysRepository::class)]
class Holidays
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $date = null;

    #[ORM\OneToMany(mappedBy: 'holidays', targetEntity: Vacation::class)]
    private Collection $vacations;

    #[ORM\OneToMany(mappedBy: 'holidays', targetEntity: Personal::class)]
    private Collection $personals;

    #[ORM\ManyToMany(targetEntity: Workshops::class, mappedBy: 'holidays')]
    private Collection $workshops;

    #[ORM\ManyToMany(targetEntity: Signin::class, mappedBy: 'holidays')]
    private Collection $signins;

    public $user; 

    public function setUser($user) {
        $this->user = $user;
    }
    
    public function __construct()
    {
        $this->vacations = new ArrayCollection();
        $this->personals = new ArrayCollection();
        $this->workshops = new ArrayCollection();
        $this->signins = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(string $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function __toString() {
        return $this -> date;
    
    }

    /**
     * @return Collection<int, Vacation>
     */
    public function getVacations(): Collection
    {
        return $this->vacations;
    }

    public function addVacation(Vacation $vacation): self
    {
        if (!$this->vacations->contains($vacation)) {
            $this->vacations->add($vacation);
            $vacation->setHolidays($this);
        }

        return $this;
    }

    public function removeVacation(Vacation $vacation): self
    {
        if ($this->vacations->removeElement($vacation)) {
            // set the owning side to null (unless already changed)
            if ($vacation->getHolidays() === $this) {
                $vacation->setHolidays(null);
            }
        }

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
            $personal->setHolidays($this);
        }

        return $this;
    }

    public function removePersonal(Personal $personal): self
    {
        if ($this->personals->removeElement($personal)) {
            // set the owning side to null (unless already changed)
            if ($personal->getHolidays() === $this) {
                $personal->setHolidays(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Workshops>
     */
    public function getWorkshops(): Collection
    {
        return $this->workshops;
    }

    public function addWorkshop(Workshops $workshop): self
    {
        if (!$this->workshops->contains($workshop)) {
            $this->workshops->add($workshop);
            $workshop->addHoliday($this);
        }

        return $this;
    }

    public function removeWorkshop(Workshops $workshop): self
    {
        if ($this->workshops->removeElement($workshop)) {
            $workshop->removeHoliday($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Signin>
     */
    public function getSignins(): Collection
    {
        return $this->signins;
    }

    public function addSignin(Signin $signin): self
    {
        if (!$this->signins->contains($signin)) {
            $this->signins->add($signin);
            $signin->addHoliday($this);
        }

        return $this;
    }

    public function removeSignin(Signin $signin): self
    {
        if ($this->signins->removeElement($signin)) {
            $signin->removeHoliday($this);
        }

        return $this;
    }
}
