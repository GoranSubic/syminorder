<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use App\Repository\CityRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *  attributes={"security"="is_granted('ROLE_USER')"},
 *  collectionOperations={
 *     "get"={"security"="is_granted('ROLE_USER')", "normalization_context"={"groups"="city:list"}},
 *     },
 *  itemOperations={
 *     "get"={"security"="is_granted('ROLE_USER')", "normalization_context"={"groups"="city:item"}},
 *     },
 *  order={"position"="ASC"},
 * )
 * @ApiFilter(RangeFilter::class, properties={"position"})
 * @ORM\Entity(repositoryClass=CityRepository::class)
 */
class City
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     *
     * @Assert\NotBlank()
     * @Groups({"city:list"})
     */
    private $name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $postalCode;

    /**
     * @ORM\Column(type="integer", nullable=true)
     *
     * @Groups({"city:list"})
     */
    private $price;

    /**
     * @ORM\Column(type="integer", nullable=true)
     *
     * @Groups({"city:list"})
     */
    private $deliveryFree;

    /**
     * @var string
     *
     * @ORM\Column(name="currency", type="string", length=64)
     */
    private $currency = "RSD";

    /**
     * @ORM\Column(type="integer", nullable=true)
     *
     * @Groups({"city:list"})
     */
    private $position;

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

    /**
     * @return mixed
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * @param mixed $postalCode
     */
    public function setPostalCode($postalCode): void
    {
        $this->postalCode = $postalCode;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDeliveryFree(): ?int
    {
        return $this->deliveryFree;
    }

    /**
     * @param mixed $deliveryFree
     */
    public function setDeliveryFree(?int $deliveryFree)
    {
        $this->deliveryFree = $deliveryFree;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     */
    public function setCurrency(string $currency = "RSD"): void
    {
        $this->currency = $currency;
    }

    /**
     * @return mixed
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param mixed $position
     */
    public function setPosition($position = 0): void
    {
        $this->position = $position;
    }
}
