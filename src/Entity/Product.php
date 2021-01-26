<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Service\ProductImagesUploader;
use App\Entity\ProductPicture;
use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use App\Entity\Category;

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
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 * @Vich\Uploadable
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="enabled", type="boolean", nullable=true)
     */
    private $enabled;

    /**
     * @ORM\Column(name="name", type="string", length=30)
     *
     * @Assert\NotBlank()
     * @Groups({"product:list", "product:item", "order:list", "suborder"})
     */
    private $name;

    /**
     * @ORM\Column(name="description", type="string", length=150)
     */
    private $description;

    /**
     * @ORM\Column(name="favorite_count", type="integer", nullable=true)
     */
    private $favoriteCount;

    /**
     * @var integer
     *
     * @ORM\Column(name="price", type="integer")
     *
     * @Assert\NotBlank()
     * @Groups({"order:list", "suborder"})
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="currency", type="string", length=64)
     */
    private $currency = "RSD";

    /**
     *
     * @ORM\OneToOne(targetEntity="App\Entity\ProductPicture", mappedBy="product", cascade={"persist", "remove"})
     *
     * @Assert\NotBlank()
     * @Groups({"order:list", "suborder"})
     */
    private $picture;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\Column(name="code", type="string", length=255, unique=true)
     *
     * @Assert\NotBlank()
     * @Groups({"product:list", "product:item", "order:list", "suborder"})
     */
    private $code;

    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * @param int $price
     */
    public function setPrice(int $price): void
    {
        $this->price = $price;
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
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * @param mixed $picture
     */
    public function setPicture(ProductPicture $picture): self
    {
        $this->picture = $picture;
        // set (or unset) the owning side of the relation if necessary
        $newProduct = $picture === null ? null : $this;
        if ($newProduct !== $picture->getProduct()) {
            $picture->setProduct($newProduct);
        }
        return $this;
    }

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

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }


    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getFavoriteCount()
    {
        return $this->favoriteCount;
    }

    /**
     * @param mixed $favoriteCount
     */
    public function setFavoriteCount($favoriteCount): void
    {
        $this->favoriteCount = $favoriteCount;
    }

    /**
     * @return null|Category
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @param Category $category
     */
    public function setCategory(Category $category): void
    {
        $this->category = $category;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code): void
    {
        $this->code = $code;
    }

}
