<?php

namespace App\Entity;

use App\Repository\VideoRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=VideoRepository::class)
 */
class Video
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "Le contenu ne peut être vide")
     */
    private $videoFileName;

    /**
     * @ORM\ManyToOne(targetEntity=Trick::class, inversedBy="videos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $trick;

    // EasyAdmin ajout pour tableau bord du site en backend
    public function __toString(): string
    {
        return $this->getVideoFileName();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVideoFileName(): string
    {
        return $this->videoFileName;
    }

    public function setVideoFileName(string $videoFileName): self
    {
        $this->videoFileName = $videoFileName;

        return $this;
    }

    public function getTrick(): ?Trick
    {
        return $this->trick;
    }

    public function setTrick(?Trick $trick): self
    {
        $this->trick = $trick;

        return $this;
    }
}
