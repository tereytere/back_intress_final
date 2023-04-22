<?php

namespace App\Entity;

use App\Repository\TasksRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TasksRepository::class)]
class Tasks
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'tasks', targetEntity: Workshops::class)]
    private Collection $savetask;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    public function __construct()
    {
        $this->savetask = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Workshops>
     */
    public function getSavetask(): Collection
    {
        return $this->savetask;
    }

    public function addSavetask(Workshops $savetask): self
    {
        if (!$this->savetask->contains($savetask)) {
            $this->savetask->add($savetask);
            $savetask->setTasks($this);
        }

        return $this;
    }

    public function removeSavetask(Workshops $savetask): self
    {
        if ($this->savetask->removeElement($savetask)) {
            // set the owning side to null (unless already changed)
            if ($savetask->getTasks() === $this) {
                $savetask->setTasks(null);
            }
        }

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }
    public function __toString() {
        return $this -> name;
    
    }
}
