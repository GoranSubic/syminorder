<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ProductAdditionsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *  attributes={"security"="is_granted('ROLE_USER')"},
 *  collectionOperations={
 *     "get"={"security"="is_granted('ROLE_USER')", "normalization_context"={"groups"="product:list"}},
 *     },
 *  itemOperations={
 *     "get"={"security"="is_granted('ROLE_USER')", "normalization_context"={"groups"="product:item"}},
 *     },
 * )
 * @ApiFilter(BooleanFilter::class, properties={"enabled"})
 * @ORM\Entity(repositoryClass=ProductAdditionsRepository::class)
 */
class ProductAdditions
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="enabled", type="boolean", nullable=true)
     * @Groups({"product:list", "product:item"})
     */
    private $enabled;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"product:list", "product:item"})
     *
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=10)
     * @Groups({"product:list", "product:item"})
     *
     * @Assert\NotBlank()
     */
    private $code;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"product:list", "product:item"})
     */
    private $price;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param mixed $enabled
     */
    public function setEnabled($enabled): void
    {
        $this->enabled = $enabled;
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

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price = 0): self
    {
        $this->price = $price;

        return $this;
    }
}
