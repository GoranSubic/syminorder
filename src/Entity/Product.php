<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Service\ProductImagesUploader;
use App\Entity\ProductPicture;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use App\Entity\Category;

/**
 * @ApiResource(
 *  collectionOperations={
 *     "get"={"normalization_context"={"groups"="product:list"}},
 *     },
 *  itemOperations={
 *     "get"={"normalization_context"={"groups"="product:item"}},
 *     },
 * )
 * @ApiFilter(BooleanFilter::class, properties={
 *     "enabled",
 *     "category.enabled"
 * })
 * @ApiFilter(SearchFilter::class, properties={
 *     "name": "partial",
 *     "productCode": "exact"
 * })
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 * @UniqueEntity("slug")
 * @Vich\Uploadable
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"product:list", "product:item", "order:list", "suborder", "category:list", "category:item"})
     */
    private $id;

    /**
     * @ORM\Column(name="enabled", type="boolean", nullable=true)
     * @Groups({"product:list", "product:item", "order:list", "suborder", "category:list", "category:item"})
     */
    private $enabled;

    /**
     * @ORM\Column(name="name", type="string", length=30)
     *
     * @Assert\NotBlank()
     * @Groups({"product:list", "product:item", "order:list", "suborder", "category:list", "category:item"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, unique=true, nullable=true)
     */
    private $slug;

    /**
     * @Assert\Length(
     *      min = 2,
     *      max = 255,
     *      minMessage = "Description must be at least {{ limit }} chars long",
     *      maxMessage = "Description cannot be longer than 240 characters"
     * )
     * @ORM\Column(name="description", type="string", length=255)
     * @Groups({"product:list", "category:list", "category:item"})
     */
    private $description;

    /**
     * @ORM\Column(name="long_description", type="text", nullable=true)
     */
    private $long_description;

    /**
     * @ORM\Column(name="show_additions", type="boolean", nullable=true)
     * @Groups({"product:list", "product:item", "order:list", "suborder", "category:list", "category:item"})
     */
    private $showAdditions;

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
     * @Groups({"product:list", "order:list", "suborder", "category:list", "category:item"})
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
     * @Assert\Image(
     *     minWidth = 200,
     *     maxWidth = 400,
     *     minHeight = 200,
     *     maxHeight = 400,
     *     maxSize = "512k",
     *     mimeTypes = {"image/png", "image/jpeg", "image/jpg"},
     *     mimeTypesMessage = "Please upload a valid valid IMAGE"
     * )
     * @Groups({"product:list", "order:list", "suborder", "category:list", "category:item"})
     */
    private $picture;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Groups({"product:list", "product:item"})
     */
    private $category;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\TagServices", inversedBy="products", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    protected $tagServices;

    /**
     * @ORM\Column(name="code", type="string", length=255, unique=true)
     *
     * @Assert\NotBlank()
     * @Groups({"product:list", "product:item", "order:list", "suborder", "category:list", "category:item"})
     */
    private $code;

    public function __construct()
    {
        $this->tagServices = new ArrayCollection();
    }

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
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     */
    public function setSlug($slug): void
    {
        $this->slug = $slug;
    }

    public function computeSlug(SluggerInterface $slugger, $em)
    {
        if (!$this->slug || '-' === $this->slug) {

            $newSlug = (string) $slugger->slug((string) $this)->lower();
            $this->slug = $newSlug;

            /* check if exists product with same name */
            $repo = $em->getRepository('App\Entity\Product');
            $productsByName = $repo->findBy(['name' => $this->name]);

            $foundProducts = array_filter($productsByName, function ($prod) {
                return ($prod->getId() !== $this->getId() && $prod->getSlug() !== null);
            });

            if (count($foundProducts)) {
                $max = 0;
                /* extract last part of slug and check if int */
                foreach ($foundProducts as $prod) {
                    $existingSlug = $prod->getSlug();
                    $words = explode("-", $existingSlug);
                    $lastWord = end($words);
                    if (is_numeric($lastWord) && $lastWord > $max) $max = $lastWord;
                }

                if ($max > 0) {
                    $max += 1;
                    $newSlug .= "-" . $max;
                } else {
                    $newSlug .= "-" . 1;
                }
                $this->slug = $newSlug;
            }
        }
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
    public function getLongDescription()
    {
        return $this->long_description;
    }

    /**
     * @param mixed $long_description
     */
    public function setLongDescription($long_description): void
    {
        $this->long_description = $long_description;
    }

    /**
     * @return mixed
     */
    public function getShowAdditions()
    {
        return $this->showAdditions;
    }

    /**
     * @param mixed $showAdditions
     */
    public function setShowAdditions($showAdditions): void
    {
        $this->showAdditions = $showAdditions;
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
     * @return Collection|TagServices[]
     */
    public function getTagServices(): Collection
    {
        return $this->tagServices;
    }

    public function addTagServices(TagServices $tag): self
    {
        if (!$this->tagServices->contains($tag)) {
            $this->tagServices[] = $tag;
            $tag->addProduct($this);
        }

        return $this;
    }

    public function removeTagServices(TagServices $tag): self
    {
        if ($this->tagServices->removeElement($tag)) {
            $tag->removeProduct($this);
        }

        return $this;
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

    public function __toString(): string
    {
        return $this->name;
    }
}
