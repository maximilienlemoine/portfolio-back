<?php

namespace App\Entity;

use App\Repository\StackRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: StackRepository::class)]
class Stack
{
    public const READ = 'stack:read';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups([self::READ, Project::READ, Skill::READ])]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups([self::READ, Project::READ])]
    private ?string $icon = null;

    #[ORM\Column(length: 255)]
    #[Groups([self::READ])]
    private ?string $color = null;

    /**
     * @var Collection<int, Skill>
     */
    #[ORM\ManyToMany(targetEntity: Skill::class, mappedBy: 'stacks')]
    private Collection $skills;

    /**
     * @var Collection<int, Project>
     */
    #[ORM\ManyToMany(targetEntity: Project::class, mappedBy: 'stacks')]
    private Collection $projects;

    #[ORM\Column(options: ['default' => true])]
    private ?bool $badgeVisible = null;

    public function __construct()
    {
        $this->skills = new ArrayCollection();
        $this->projects = new ArrayCollection();
        $this->setBadgeVisible(true);
    }

    public function __toString(): string
    {
        return sprintf('%s - %s', $this->id, $this->title);
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

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(?string $icon): static
    {
        $this->icon = $icon;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): static
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @return array<Skill>
     */
    public function getSkills(): array
    {
        return $this->skills->getValues();
    }

    public function addSkill(Skill $skill): static
    {
        if (!$this->skills->contains($skill)) {
            $this->skills->add($skill);
            $skill->addStack($this);
        }

        return $this;
    }

    public function removeSkill(Skill $skill): static
    {
        if ($this->skills->removeElement($skill)) {
            $skill->removeStack($this);
        }

        return $this;
    }

    /**
     * @return array<Project>
     */
    public function getProjects(): array
    {
        return $this->projects->getValues();
    }

    public function addProject(Project $project): static
    {
        if (!$this->projects->contains($project)) {
            $this->projects->add($project);
            $project->addStack($this);
        }

        return $this;
    }

    public function removeProject(Project $project): static
    {
        if ($this->projects->removeElement($project)) {
            $project->removeStack($this);
        }

        return $this;
    }

    public function isBadgeVisible(): ?bool
    {
        return $this->badgeVisible;
    }

    public function setBadgeVisible(bool $badgeVisible): static
    {
        $this->badgeVisible = $badgeVisible;

        return $this;
    }
}
