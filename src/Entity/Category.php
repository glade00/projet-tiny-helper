<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category
{
    public function __toString(): string
    {
        return $this->title;
    }
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Sites", mappedBy="category")
     */
    private $sites;

    public function __construct()
    {
        $this->sites = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection|Sites[]
     */
    public function getSites(): Collection
    {
        return $this->sites;
    }

    public function addSite(Sites $site): self
    {
        if (!$this->sites->contains($site)) {
            $this->sites[] = $site;
            $site->setCategory($this);
        }

        return $this;
    }

    public function removeSite(Sites $site): self
    {
        if ($this->sites->contains($site)) {
            $this->sites->removeElement($site);
            // set the owning side to null (unless already changed)
            if ($site->getCategory() === $this) {
                $site->setCategory(null);
            }
        }

        return $this;
    }
}
