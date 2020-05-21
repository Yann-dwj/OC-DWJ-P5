<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClassroomRepository")
 */
class Classroom
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $year;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="classroom")
     */
    private $users;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", cascade={"persist", "remove"})
     */
    private $teacher;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Article", mappedBy="classroom")
     */
    private $articles;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->year = date('Y');
        $this->articles = new ArrayCollection();
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

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setClassroom($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getClassroom() === $this) {
                $user->setClassroom(null);
            }
        }

        return $this;
    }

   public function  __toString(){
        return $this->name;
    }

   public function getTeacher(): ?User
   {
       return $this->teacher;
   }

   public function setTeacher(?User $teacher): self
   {
       $this->teacher = $teacher;

       return $this;
   }

   /**
    * @return Collection|Article[]
    */
   public function getArticles(): Collection
   {
       return $this->articles;
   }

   public function addArticle(Article $article): self
   {
       if (!$this->articles->contains($article)) {
           $this->articles[] = $article;
           $article->setClassroom($this);
       }

       return $this;
   }

   public function removeArticle(Article $article): self
   {
       if ($this->articles->contains($article)) {
           $this->articles->removeElement($article);
           // set the owning side to null (unless already changed)
           if ($article->getClassroom() === $this) {
               $article->setClassroom(null);
           }
       }

       return $this;
   }
}
