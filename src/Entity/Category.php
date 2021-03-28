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
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\String\Slugger\SluggerInterface;
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
 *  order={"position"="ASC"},
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
 * @UniqueEntity("slug")
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
     * @ORM\Column(name="show_on_front", type="boolean", nullable=true)
     * @Groups({"category:list", "category:item"})
     */
    private $showOnFront;

    /**
     * @ORM\Column(name="name", type="string", length=50)
     * @Groups({"category:list", "category:item"})
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
     * @Groups({"category:list", "category:item"})
     */
    private $description;

    /**
     * @ORM\Column(name="long_description", type="text", nullable=true)
     */
    private $long_description;

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
     *     minWidth = 300,
     *     maxWidth = 800,
     *     minHeight = 300,
     *     maxHeight = 600,
     *     maxSize = "1024k",
     *     mimeTypes = {"image/png", "image/jpeg", "image/jpg"},
     *     mimeTypesMessage = "Please upload a valid valid IMAGE"
     * )
     * @Groups({"category:list", "category:item"})
     */
    private $picture;

    /**
     * @ORM\Column(type="integer", nullable=true)
     *
     * @Groups({"category:list"})
     */
    private $position;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->children = new ArrayCollection();
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
     * @return mixed
     */
    public function getShowOnFront()
    {
        return $this->showOnFront;
    }

    /**
     * @param mixed $showOnFront
     */
    public function setShowOnFront($showOnFront): void
    {
        $this->showOnFront = $showOnFront;
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

            /* check if exists category with same name */
            $repo = $em->getRepository('App\Entity\Category');
            $categoriesByName = $repo->findBy(['name' => $this->name]);

            $foundCategories = array_filter($categoriesByName, function ($cat) {
                return ($cat->getId() !== $this->getId() && $cat->getSlug() !== null);
            });

            if (count($foundCategories)) {
                $max = 0;
                /* extract last part of slug and check if int */
                foreach ($foundCategories as $cat) {
                    $existingSlug = $cat->getSlug();
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
    public function setPosition($position): void
    {
        $this->position = $position;
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function setLft(int $lft): self
    {
        $this->lft = $lft;

        return $this;
    }

    public function setLvl(int $lvl): self
    {
        $this->lvl = $lvl;

        return $this;
    }

    public function setRgt(int $rgt): self
    {
        $this->rgt = $rgt;

        return $this;
    }

    public function setRoot(?self $root): self
    {
        $this->root = $root;

        return $this;
    }

    public function addChild(Category $child): self
    {
        if (!$this->children->contains($child)) {
            $this->children[] = $child;
            $child->setParent($this);
        }

        return $this;
    }

    public function removeChild(Category $child): self
    {
        if ($this->children->removeElement($child)) {
            // set the owning side to null (unless already changed)
            if ($child->getParent() === $this) {
                $child->setParent(null);
            }
        }

        return $this;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setCategory($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getCategory() === $this) {
                $product->setCategory(null);
            }
        }

        return $this;
    }
}
