<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['username'], message: 'Il y a déjà un compte avec ce pseudonyme')]
#[UniqueEntity(fields: ['email'], message: 'Il y a déjà un compte avec cette adresse e-mail')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $username = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    private ?string $lastName = null;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Post::class)]
    private Collection $posts;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Comment::class)]
    private Collection $comments;

    #[ORM\ManyToMany(targetEntity: Post::class, mappedBy: 'likes')]
    private Collection $liked_posts;

    #[ORM\ManyToMany(targetEntity: Post::class, mappedBy: 'dislikes')]
    private Collection $disliked_posts;

    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: HelpGroup::class)]
    private Collection $helpGroups;

    #[ORM\OneToMany(mappedBy: 'member', targetEntity: Membership::class, orphanRemoval: true)]
    private Collection $memberships;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->liked_posts = new ArrayCollection();
        $this->disliked_posts = new ArrayCollection();
        $this->helpGroups = new ArrayCollection();
        $this->memberships = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): static
    {
        if (!$this->posts->contains($post)) {
            $this->posts->add($post);
            $post->setAuthor($this);
        }

        return $this;
    }

    public function removePost(Post $post): static
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getAuthor() === $this) {
                $post->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setAuthor($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getAuthor() === $this) {
                $comment->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getLikedPosts(): Collection
    {
        return $this->liked_posts;
    }

    public function addLikedPost(Post $likedPost): static
    {
        if (!$this->liked_posts->contains($likedPost)) {
            $this->liked_posts->add($likedPost);
            $likedPost->addLike($this);
        }

        return $this;
    }

    public function removeLikedPost(Post $likedPost): static
    {
        if ($this->liked_posts->removeElement($likedPost)) {
            $likedPost->removeLike($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getDislikedPosts(): Collection
    {
        return $this->disliked_posts;
    }

    public function addDislikedPost(Post $dislikedPost): static
    {
        if (!$this->disliked_posts->contains($dislikedPost)) {
            $this->disliked_posts->add($dislikedPost);
            $dislikedPost->addDislike($this);
        }

        return $this;
    }

    public function removeDislikedPost(Post $dislikedPost): static
    {
        if ($this->disliked_posts->removeElement($dislikedPost)) {
            $dislikedPost->removeDislike($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, HelpGroup>
     */
    public function getHelpGroups(): Collection
    {
        return $this->helpGroups;
    }

    public function addHelpGroup(HelpGroup $helpGroup): static
    {
        if (!$this->helpGroups->contains($helpGroup)) {
            $this->helpGroups->add($helpGroup);
            $helpGroup->setOwner($this);
        }

        return $this;
    }

    public function removeHelpGroup(HelpGroup $helpGroup): static
    {
        if ($this->helpGroups->removeElement($helpGroup)) {
            // set the owning side to null (unless already changed)
            if ($helpGroup->getOwner() === $this) {
                $helpGroup->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Membership>
     */
    public function getMemberships(): Collection
    {
        return $this->memberships;
    }

    public function addMembership(Membership $membership): static
    {
        if (!$this->memberships->contains($membership)) {
            $this->memberships->add($membership);
            $membership->setMember($this);
        }

        return $this;
    }

    public function removeMembership(Membership $membership): static
    {
        if ($this->memberships->removeElement($membership)) {
            // set the owning side to null (unless already changed)
            if ($membership->getMember() === $this) {
                $membership->setMember(null);
            }
        }

        return $this;
    }
}
