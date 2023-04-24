<?php

namespace App\Entity;

use App\Repository\PersonalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PersonalRepository::class)]
class Personal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $surname = null;

    #[ORM\Column(length: 255)]
    private ?string $rol = null;

    #[ORM\OneToMany(mappedBy: 'personal', targetEntity: Vacation::class)]
    #[ORM\JoinColumn(nullable: true)]
    private Collection $vacations;

    #[ORM\ManyToOne(inversedBy: 'personals')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Workshops $workshops = null;

    #[ORM\ManyToOne(inversedBy: 'personals')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Signin $signin = null;

    #[ORM\ManyToOne(inversedBy: 'personals')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Holidays $holidays = null;

    #[ORM\ManyToOne(inversedBy: 'personals')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Documents $documents = null;

    #[ORM\ManyToOne(inversedBy: 'personals')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Vacation $vacation = null;

    public function __construct()
    {
        $this->vacations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getRol(): ?string
    {
        return $this->rol;
    }

    public function setRol(string $rol): self
    {
        $this->rol = $rol;

        return $this;
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
            $vacation->setPersonal($this);
        }

        return $this;
    }

    public function removeVacation(Vacation $vacation): self
    {
        if ($this->vacations->removeElement($vacation)) {
            // set the owning side to null (unless already changed)
            if ($vacation->getPersonal() === $this) {
                $vacation->setPersonal(null);
            }
        }

        return $this;
    }

    public function getWorkshops(): ?Workshops
    {
        return $this->workshops;
    }

    public function setWorkshops(?Workshops $workshops): self
    {
        $this->workshops = $workshops;

        return $this;
    }

    public function getSignin(): ?Signin
    {
        return $this->signin;
    }

    public function setSignin(?Signin $signin): self
    {
        $this->signin = $signin;

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
    public function __toString()
{
    if ($this->holidays !== null) {
        return $this->holidays;
    } else {
        return '';        
    };
}

    public function getDocuments(): ?Documents
    {
        return $this->documents;
    }

    public function setDocuments(?Documents $documents): self
    {
        $this->documents = $documents;

        return $this;
    }

    public function getVacation(): ?Vacation
    {
        return $this->vacation;
    }

    public function setVacation(?Vacation $vacation): self
    {
        $this->vacation = $vacation;

        return $this;
    }
}
