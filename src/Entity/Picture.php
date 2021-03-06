<?php

namespace App\Entity;

use App\Repository\PictureRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @ORM\Entity(repositoryClass=PictureRepository::class)
 * @Vich\Uploadable
 */
class Picture
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
    private $pictureFileName;

    /**
     * @Vich\UploadableField(mapping="trick_images", fileNameProperty="pictureFileName")
     * @var File
     */
    private $pictureFile;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated;

    /**
     * @ORM\ManyToOne(targetEntity=Trick::class, inversedBy="pictures")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $trick;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $alt;

    // EasyAdmin addition for backend site dashboard 
    public function __toString(): string
    {
        return '(id:' . $this->getId() . ')-' . $this->getPictureFileName();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPictureFileName(): ?string
    {
        return $this->pictureFileName;
    }

    public function setPictureFileName(?string $pictureFileName): self
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

    public function getAlt(): ?string
    {
        return $this->alt;
    }

    public function setAlt(string $alt): self
    {
        $this->alt = $alt;

        return $this;
    }

    /**
     * Get the value of pictureFile
     *
     * @return  File | null
     */
    public function getPictureFile(): ?file
    {
        return $this->pictureFile;
    }

    /**
     * Set the value of pictureFile
     *
     * @param  File|null   $pictureFile
     */
    public function setPictureFile(?File $pictureFile = null)
    {
        $this->pictureFile = $pictureFile;

        if (null !== $pictureFile) {
            $this->updated = new \Datetime();
        }
    }
}
