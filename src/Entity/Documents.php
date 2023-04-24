<?php

namespace App\Entity;

use App\Repository\DocumentsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DocumentsRepository::class)]
class Documents
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $date = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'documents', targetEntity: Personal::class)]
    private Collection $personals;

    #[ORM\Column(length: 255)]
    private ?string $document = null;

    #[ORM\Column(length: 255)]
    private ?string $docname = null;

    public function __construct()
    {
        $this->personals = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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
            $personal->setDocuments($this);
        }

        return $this;
    }

    public function removePersonal(Personal $personal): self
    {
        if ($this->personals->removeElement($personal)) {
            // set the owning side to null (unless already changed)
            if ($personal->getDocuments() === $this) {
                $personal->setDocuments(null);
            }
        }

        return $this;
    }

    public function getDocument(): ?string
    {
        return $this->document;
    }

    public function setDocument(string $document): self
    {
        $this->document = $document;

        return $this;
    }

    public function getDocname(): ?string
    {
        return $this->docname;
    }

    public function setDocname(string $docname): self
    {
        $this->docname = $docname;

        return $this;
    }
    public function __toString() {
        return $this -> docname;
    
    }
}
