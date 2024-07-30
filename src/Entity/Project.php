<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
    public const READ = 'project:read';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups([self::READ])]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    #[Groups([self::READ])]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups([self::READ])]
    private ?string $projectLink = null;

    #[ORM\Column(length: 255)]
    #[Groups([self::READ])]
    private ?string $sourceCodeLink = null;

    /**
     * @var Collection<int, Goal>
     */
    #[ORM\ManyToMany(targetEntity: Goal::class, inversedBy: 'projects')]
    #[Groups([self::READ])]
    private Collection $goals;

    /**
     * @var Collection<int, Stack>
     */
    #[ORM\ManyToMany(targetEntity: Stack::class, inversedBy: 'projects')]
    #[Groups([self::READ])]
    private Collection $stacks;

    #[ORM\Column(options: ['default' => true])]
    private ?bool $visible = null;

    public function __construct()
    {
        $this->goals = new ArrayCollection();
        $this->stacks = new ArrayCollection();
        $this->setVisible(true);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getProjectLink(): ?string
    {
        return $this->projectLink;
    }

    public function setProjectLink(?string $projectLink): static
    {
        $this->projectLink = $projectLink;

        return $this;
    }

    public function getSourceCodeLink(): ?string
    {
        return $this->sourceCodeLink;
    }

    public function setSourceCodeLink(string $sourceCodeLink): static
    {
        $this->sourceCodeLink = $sourceCodeLink;

        return $this;
    }

    /**
     * @return array<Goal>
     */
    public function getGoals(): array
    {
        return $this->goals->getValues();
    }

    public function addGoal(Goal $goal): static
    {
        if (!$this->goals->contains($goal)) {
            $this->goals->add($goal);
        }

        return $this;
    }

    public function removeGoal(Goal $goal): static
    {
        $this->goals->removeElement($goal);

        return $this;
    }

    /**
     * @return array<Stack>
     */
    public function getStacks(): array
    {
        $stacks = $this->stacks->getValues();
        usort($stacks, fn (Stack $a, Stack $b) => $a->getNumber() <=> $b->getNumber());

        return $stacks;
    }

    public function addStack(Stack $stack): static
    {
        if (!$this->stacks->contains($stack)) {
            $this->stacks->add($stack);
        }

        return $this;
    }

    public function removeStack(Stack $stack): static
    {
        $this->stacks->removeElement($stack);

        return $this;
    }

    public function isVisible(): ?bool
    {
        return $this->visible;
    }

    public function setVisible(bool $visible): static
    {
        $this->visible = $visible;

        return $this;
    }
}
