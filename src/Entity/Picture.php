<?php

namespace App\Entity;

use App\Repository\PictureRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PictureRepository::class)
 */
class Picture
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pictureFileName;

    /**
     * @ORM\ManyToOne(targetEntity=Trick::class, inversedBy="pictures")
     */
    private $trick;

    // pour le formulaire PictureType pour la gestion des champs check pour la suppression d une ou plusieurs picture
    // renvoie un simple boolean pour que cela puisse la creation du champ chek des picture puisse etre creer
    // public function isChecked()
    // {
    //     return false;
    // }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPictureFileName(): ?string
    {
        return $this->pictureFileName;
    }

    public function setPictureFileName(string $pictureFileName): self
    {
        $this->pictureFileName = $pictureFileName;

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
