<?php declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Service\ProductImagesUploader;
use App\Entity\ProductPicture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index as Index;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Category
 * @package App\Entity
 *
 * @ApiResource(
 *  collectionOperations={
 *     "get"={"normalization_context"={"groups"="category:list"}},
 *     },
 *  itemOperations={
 *     "get"={"normalization_context"={"groups"="category:item"}},
 *     },
 * )
 * @ApiFilter(BooleanFilter::class, properties={"enabled"})
 *
 * @Gedmo\Tree(type="nested")
 * @ORM\Table(
 *     name="category",
 *     indexes={
 *          @Index(name="lft_ix", columns={"lft"}),
 *          @Index(name="rgt_ix", columns={"rgt"}),
 *          @Index(name="lft_rgt_ix", columns={"lft", "rgt"}),
 *          @Index(name="lvl_ix", columns={"lvl"})
 *     })
 * @ORM\Entity(repositoryClass="Gedmo\Tree\Entity\Repository\NestedTreeRepository")
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     * @Groups({"category:list", "category:item"})
     */
    private $id;

    /**
     * @ORM\Column(name="enabled", type="boolean", nullable=true)
     * @Groups({"category:list", "category:item"})
     */
    private $enabled;

    /**
     * @ORM\Column(name="name", type="string", length=30)
     * @Groups({"category:list", "category:item"})
     */
    private $name;

    /**
     * @ORM\Column(name="description", type="string", length=255)
     * @Groups({"category:list", "category:item"})
     */
    private $description;

    /**
     * @Gedmo\TreeLeft()
     * @ORM\Column(type="integer")
     */
    private $lft;

    /**
     * @Gedmo\TreeLevel()
     * @ORM\Column(type="integer")
     */
    private $lvl;

    /**
     * @Gedmo\TreeRight()
     * @ORM\Column(type="integer")
     */
    private $rgt;

    /**
     * @Gedmo\TreeRoot()
     * @ORM\ManyToOne(targetEntity="App\Entity\Category")
     * @ORM\JoinColumn(name="tree_root", referencedColumnName="id", onDelete="cascade")
     */
    private $root;

    /**
     * @Gedmo\TreeParent()
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="cascade")
     * @Groups({"category:list", "category:item"})
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Category", mappedBy="parent")
     * @ORM\OrderBy({"lft" = "ASC"})
     * @Groups({"category:list", "category:item"})
     */
    private $children;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Product", mappedBy="category")
     * @Groups({"category:list", "category:item"})
     */
    private $products;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\ProductPicture", mappedBy="category", cascade={"persist", "remove"})
     *
     * @Assert\NotBlank()
     * @Assert\Image(
     *     minWidth = 200,
     *     maxWidth = 400,
     *     minHeight = 200,
     *     maxHeight = 400,
     *     maxSize = "1024k",
     *     mimeTypes = {"image/png", "image/jpeg", "image/jpg"},
     *     mimeTypesMessage = "Please upload a valid valid IMAGE"
     * )
     * @Groups({"category:list", "category:item"})
     */
    private $picture;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getLft(): ?int
    {
        return $this->lft;
    }

    /**
     * @return int|null
     */
    public function getRgt(): ?int
    {
        return $this->rgt;
    }

    /**
     * @return null|Product[]|ArrayCollection|Collection
     */
    public function getProducts(): ?Collection
    {
        return $this->products;
    }

    /**
     * @return null|integer
     */
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
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
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
     * @return null|Category
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param Category $parent
     */
    public function setParent(Category $parent): void
    {
        $this->parent = $parent;
    }

    /**
     * @return null|Category
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * @return null|Category[]|ArrayCollection|Collection
     */
    public function getChildren(): ?Collection
    {
        return $this->children;
    }

    /**
     * @return null|int
     */
    public function getLvl(): ?int
    {
        return $this->lvl;
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
        $newCategory = $picture === null ? null : $this;
        if ($newCategory !== $picture->getCategory()) {
            $picture->setCategory($newCategory);
        }
        return $this;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
