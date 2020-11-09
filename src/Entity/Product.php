<?php

namespace App\Entity;

use App\Service\ProductImagesUploader;
use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
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
     * @ORM\Column(name="product", type="string", length=255)
     */
    private $product;

    /**
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(name="favorite_count", type="integer", nullable=true)
     */
    private $favoriteCount;

    /**
     * @ORM\Column(name="price", type="float", length=255, nullable=true)
     */
    private $price;

    /**
     *
     * @ORM\OneToOne(targetEntity="App\Entity\ProductPicture", mappedBy="product", cascade={"persist", "remove"})
     *
     */
    private $picture;

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
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param mixed $product
     */
    public function setProduct($product): void
    {
        $this->product = $product;
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
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price): void
    {
        $this->price = $price;
    }
}
