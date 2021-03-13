<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use App\Repository\TagServicesRepository;

/**
 * @ORM\Table(name="tag_services")
 * @ORM\Entity(repositoryClass=TagServicesRepository::class)
 * @Vich\Uploadable
 * @UniqueEntity ("slug")
 *
 * @ApiResource(
 *  collectionOperations={
 *     "get"={"normalization_context"={"groups"="tagservices:list"}},
 *     },
 *  itemOperations={
 *     "get"={"normalization_context"={"groups"="tagservices:item"}},
 *     },
 * )
 * @ApiFilter(BooleanFilter::class, properties={"enabled"})
 *
 */
class TagServices
{
    /**
     * @ORM\Column (type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"tagservices:list", "tagservices:item"})
     */
    protected $id;

    /**
     * @ORM\Column(name="enabled", type="boolean", nullable=true)
     * @Groups({"tagservices:list", "tagservices:item"})
     */
    private $enabled;

    /**
     * @ORM\Column(type="string")
     * @Groups({"tagservices:list", "tagservices:item"})
     */
    protected $name;

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
     * @Groups({"tagservices:list", "tagservices:item"})
     */
    private $description;

    /**
     * @ORM\Column(name="long_description", type="text", nullable=true)
     */
    private $long_description;

    /**
     * @ORM\ManyToMany(targetEntity="Product", mappedBy="tagServices")
     * @Groups({"tagservices:list", "tagservices:item"})
     */
    protected $products;

    /**
     * @ORM\OneToOne(
     *     targetEntity="App\Entity\ProductPicture",
     *     mappedBy="tagServices",
     *     cascade={"persist", "remove"})
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
     * @Groups({"tagservices:list", "tagservices:item"})
     */
    private $picture;

    public function __construct()
    {
        $this->products = new ArrayCollection();
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

            $newSlug = (string)$slugger->slug((string)$this)->lower();
            $this->slug = $newSlug;

            /* check if exists tag with same name */
            $repo = $em->getRepository('App\Entity\TagServices');
            $tagsByName = $repo->findBy(['name' => $this->name]);

            $foundTags = array_filter($tagsByName, function ($tag) {
                return ($tag->getId() !== $this->getId() && $tag->getSlug() !== null);
            });

            if (count($foundTags)) {
                $max = 0;
                /* extract last part of slug and check if int */
                foreach ($foundTags as $tag) {
                    $existingSlug = $tag->getSlug();
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
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->addTag($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            $product->removeTag($this);
        }

        return $this;
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
        $newTag = $picture === null ? null : $this;
        if ($newTag !== $picture->getTagServices()) {
            $picture->setTagServices($newTag);
        }
        return $this;
    }

    public function __toString(): string
    {
        return $this->name;
    }

}