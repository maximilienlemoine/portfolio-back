<?php

namespace App\Entity;

use App\Repository\MeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: MeRepository::class)]
class Me
{
    const READ = "me:read";
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups([self::READ])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups([self::READ])]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups([self::READ])]
    private ?string $image = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups([self::READ])]
    private ?string $cv = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getCv(): ?string
    {
        return $this->cv;
    }

    public function setCv(?string $cv): static
    {
        $this->cv = $cv;

        return $this;
    }
}
