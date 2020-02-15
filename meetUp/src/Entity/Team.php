<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TeamRepository")
 */
class Team
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
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $thematic;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="teams")
     */
    private $users;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="teams_member")
     */
    private $users_member;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->users_member = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getThematic(): ?string
    {
        return $this->thematic;
    }

    public function setThematic(string $thematic): self
    {
        $this->thematic = $thematic;

        return $this;
    }

    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user)
    {
        if ($this->users->contains($user)) {
            return;
        }

        $this->users->add($user);
        $user->addTeam($this);
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
        }

        return $this;
    }

    public function getSlug()
    {
        return (new Slugify())->slugify($this->name);
    }

    /**
     * @return Collection|User[]
     */
    public function getUsersMember(): Collection
    {
        return $this->users_member;
    }

    public function addUsersMember(User $usersMember): self
    {
        if (!$this->users_member->contains($usersMember)) {
            $this->users_member[] = $usersMember;
            $usersMember->addTeamsMember($this);
        }

        return $this;
    }

    public function removeUsersMember(User $usersMember): self
    {
        if ($this->users_member->contains($usersMember)) {
            $this->users_member->removeElement($usersMember);
            $usersMember->removeTeamsMember($this);
        }

        return $this;
    }
}
