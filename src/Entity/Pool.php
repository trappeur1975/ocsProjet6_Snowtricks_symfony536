<?php

namespace App\Entity;

use App\Repository\PoolRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=PoolRepository::class)
 */
class Pool
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"group1"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"group1"})
     */
    private $name;

    // EasyAdmin ajout pour tableau bord du site en backend
    public function __toString(): string
    {
        return $this->getName();
    }

    public function getId(): ?int
    {
        return $this->id;
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
}
