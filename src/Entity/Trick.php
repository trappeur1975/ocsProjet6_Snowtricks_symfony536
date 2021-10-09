<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\TrickRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=TrickRepository::class)
 * @UniqueEntity(fields={"name"}, message="Ce trick existe déjà sur le site")
 * @UniqueEntity("slug")
 */
class Trick
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "Ce champ est requis !")
     * @Assert\Length(
     *      min = 3,
     *      max = 50,
     *      minMessage = "Votre titre de trick doit contenir au moins {{ limit }} caractères !",
     *      maxMessage = "Votre titre de trick ne peut pas contenir plus que {{ limit }} caractères !"
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message = "Ce champ est requis !")
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=Picture::class, mappedBy="trick", cascade={"persist", "remove"})
     */
    private $pictures;

    /**
     * @ORM\OneToMany(targetEntity=Video::class, mappedBy="trick", cascade={"persist", "remove"})
     */
    private $videos;

    /**
     * @ORM\ManyToOne(targetEntity=Pool::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $pool;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="trick", orphanRemoval=true)
     * @ORM\OrderBy({"create_At" = "DESC"})
     */
    private $messages;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="tricks")
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updateAt;

    /**
     * @ORM\Column(type="string", length=255)
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $slug;

    public function __construct()
    {
        $this->pictures = new ArrayCollection();
        $this->videos = new ArrayCollection();
        $this->messages = new ArrayCollection();
    }

    public function computeSlug(SluggerInterface $slugger)
    {
        // if (!$this->slug || '-' === $this->slug) {
        //     // $this->slug = (string) $slugger->slug((string) $this)->lower();
        //     $this->slug = (string) $slugger->slug((string) $this->getName())->lower();
        // }
        $this->slug = (string) $slugger->slug((string) $this->getName())->lower();
    }


    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Picture[]
     */
    public function getPictures(): Collection
    {
        return $this->pictures;
    }

    public function addPicture(Picture $picture): self
    {
        if (!$this->pictures->contains($picture)) {
            $this->pictures[] = $picture;
            $picture->setTrick($this);
        }

        return $this;
    }

    // public function removePicture(Picture $picture): self
    // {
    //     if ($this->pictures->removeElement($picture)) {
    //         // set the owning side to null (unless already changed)
    //         if ($picture->getTrick() === $this) {
    //             $picture->setTrick(null);
    //         }
    //     }

    //     return $this;
    // }

    // ------------------FUNCTION NICOLAS TCHENIO-------------------
    public function removePicture(Picture $picture, EntityManagerInterface $manager): self
    {
        // we delete the file physically
        $pictureFileName = $picture->getPictureFileName();
        unlink($this->params->get('pictures_directory_contributions') . '/' . $pictureFileName);

        // removing the picture from the database 
        $manager->remove($picture);
        $manager->flush();

        return $this;
    }
    // ------------------fin FUNCTION NICOLAS TCHENIO-------------------

    /**
     * @return Collection|Video[]
     */
    public function getVideos(): Collection
    {
        return $this->videos;
    }

    public function addVideo(Video $video): self
    {
        if (!$this->videos->contains($video)) {
            $this->videos[] = $video;
            $video->setTrick($this);
        }

        return $this;
    }

    public function removeVideo(Video $video): self
    {
        if ($this->videos->removeElement($video)) {
            // set the owning side to null (unless already changed)
            if ($video->getTrick() === $this) {
                $video->setTrick(null);
            }
        }

        return $this;
    }

    public function getPool(): ?Pool
    {
        return $this->pool;
    }

    public function setPool(?Pool $pool): self
    {
        $this->pool = $pool;

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setTrick($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getTrick() === $this) {
                $message->setTrick(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeInterface $createAt): self
    {
        $this->createAt = $createAt;

        return $this;
    }

    public function getUpdateAt(): ?\DateTimeInterface
    {
        return $this->updateAt;
    }

    public function setUpdateAt(?\DateTimeInterface $updateAt): self
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }
}
