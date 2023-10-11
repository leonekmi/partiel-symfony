<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    #[ORM\OneToMany(mappedBy: 'post', targetEntity: Comment::class, orphanRemoval: true)]
    private Collection $comments;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'liked_posts')]
    #[ORM\JoinTable(name: 'users_liked_posts')]
    private Collection $likes;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'disliked_posts')]
    #[ORM\JoinTable(name: 'users_disliked_posts')]
    private Collection $dislikes;

    #[ORM\OneToMany(mappedBy: 'post', targetEntity: PostAttachment::class, orphanRemoval: true)]
    private Collection $attachments;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    private ?HelpGroup $helpGroup = null;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->likes = new ArrayCollection();
        $this->dislikes = new ArrayCollection();
        $this->attachments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;

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
            $comment->setPost($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getPost() === $this) {
                $comment->setPost(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(User $like): static
    {
        if (!$this->likes->contains($like)) {
            $this->likes->add($like);
        }

        return $this;
    }

    public function removeLike(User $like): static
    {
        $this->likes->removeElement($like);

        return $this;
    }

    public function toggleLike(User $like): static
    {
        if ($this->dislikes->contains($like)) {
            $this->dislikes->removeElement($like);
        }
        if (!$this->likes->contains($like)) {
            $this->likes->add($like);
        } else {
            $this->likes->removeElement($like);
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getDislikes(): Collection
    {
        return $this->dislikes;
    }

    public function addDislike(User $dislike): static
    {
        if (!$this->dislikes->contains($dislike)) {
            $this->dislikes->add($dislike);
        }

        return $this;
    }

    public function removeDislike(User $dislike): static
    {
        $this->dislikes->removeElement($dislike);

        return $this;
    }

    public function toggleDislike(User $dislike): static
    {
        if ($this->likes->contains($dislike)) {
            $this->likes->removeElement($dislike);
        }
        if (!$this->dislikes->contains($dislike)) {
            $this->dislikes->add($dislike);
        } else {
            $this->dislikes->removeElement($dislike);
        }

        return $this;
    }

    /**
     * @return Collection<int, PostAttachment>
     */
    public function getAttachments(): Collection
    {
        return $this->attachments;
    }

    public function addAttachment(PostAttachment $attachment): static
    {
        if (!$this->attachments->contains($attachment)) {
            $this->attachments->add($attachment);
            $attachment->setPost($this);
        }

        return $this;
    }

    public function removeAttachment(PostAttachment $attachment): static
    {
        if ($this->attachments->removeElement($attachment)) {
            // set the owning side to null (unless already changed)
            if ($attachment->getPost() === $this) {
                $attachment->setPost(null);
            }
        }

        return $this;
    }

    public function getHelpGroup(): ?HelpGroup
    {
        return $this->helpGroup;
    }

    public function setHelpGroup(?HelpGroup $helpGroup): static
    {
        $this->helpGroup = $helpGroup;

        return $this;
    }
}
