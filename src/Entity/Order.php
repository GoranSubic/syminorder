<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;
use function Webmozart\Assert\Tests\StaticAnalysis\false;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 *
 * @ApiResource(
 *  collectionOperations={
 *     "get"={
 *          "security"="is_granted('ROLE_WAITER') or is_granted('ROLE_DRIVER')",
 *          "normalization_context"={"groups"="order:list"},
 *     },
 *     "post"={
 *          "security"="is_granted('ROLE_USER')",
 *          "denormalization_context"={"groups"={"order:write"}},
 *      },
 *     },
 *  itemOperations={
 *     "get"={
 *              "security"="is_granted('ROLE_WAITER') or is_granted('ROLE_DRIVER')",
 *              "normalization_context"={"groups"="order:item"},
 *     },
 *     "delete"={"security"="is_granted('ROLE_ADMIN')"},
 *     "put"={"security"="is_granted('ROLE_USER') and object.getCustomer() == user",
 *              "security_message"="Only the creator can edit a order!"
 *      },
 *     "patch"={
 *              "input_formats"={"json"={"application/ld+json"}},
 *              "method"="PATCH",
 *              "security"="is_granted('ROLE_WAITER') or is_granted('ROLE_DRIVER')",
 *              "denormalization_context"={"groups"={"order:write"}},
 *      },
 *     },
 *  order={"createdAt"="ASC"},
 *  paginationEnabled=true,
 *  attributes={
 *      "pagination_items_per_page"=10,
 *  },
 *  subresourceOperations={
 *     "api_users_orders_get_subresource"={
 *         "method"="GET",
 *     "security"="is_granted('ROLE_USER')",
 *     "security_message"="Only workers can get collections of a orderItems!",
 *         "normalization_context"={"groups"={"suborder"}}
 *     }
 *  }
 * )
 * @ApiFilter(SearchFilter::class, properties={"status": "exact"})
 * @ApiFilter(BooleanFilter::class, properties={"isDelivered"})
 */
class Order
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @Groups({"order:list", "order:item"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="orders")
     * @ORM\JoinColumn(nullable=true)
     *
     * @Groups({"order:list", "order:item", "order:write", "suborder"})
     */
    private $customer;

    /**
     * @ORM\OneToMany(targetEntity=OrderItem::class, mappedBy="orderRef", cascade={"persist", "remove"}, orphanRemoval=true)
     *
     * @Assert\Valid()
     * @Groups({"order:list", "order:item", "order:write", "suborder"})
     */
    private $items;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Groups({"order:list", "order:item", "order:write", "suborder"})
     */
    private $noteCart;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Groups({"order:list", "order:item", "order:write", "suborder"})
     */
    private $noteAdmin;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Assert\NotBlank()
     * @Groups({"order:list", "order:item", "order:write", "suborder"})
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     *
     * @Assert\NotBlank()
     * @Groups({"order:list", "order:item", "order:write", "suborder"})
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Groups({"order:list", "order:item", "order:write", "suborder"})
     */
    private $status = self::STATUS_CART;

    /**
     * An order that is in progress, not placed yet.
     *
     * @var string
     */
    const STATUS_CART = 'cart';


    /**
     * @ORM\Column(type="datetime")
     *
     * @Groups({"order:list", "order:item", "order:write", "suborder"})
     */
    private $cartAt;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Groups({"order:list", "order:item", "order:write", "suborder"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Groups({"order:list", "order:item", "order:write", "suborder"})
     */
    private $processAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Groups({"order:list", "order:item", "order:write", "suborder"})
     */
    private $transportAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Groups({"order:list", "order:item", "order:write", "suborder"})
     */
    private $deliveredAt;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     *
     * @Groups({"order:list", "order:item", "order:write", "suborder"})
     */
    private $isDelivered = false;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Groups({"order:list", "order:item", "order:write", "suborder"})
     */
    private $updatedAt;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @param mixed $customer
     */
    public function setCustomer($customer): void
    {
        $this->customer = $customer;
    }

    /**
     * @return Collection|OrderItem[]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(OrderItem $item): self
    {
        /*foreach ($this->getItems() as $existingItem) {
            // The item already exists, update the quantity
            if ($existingItem->equals($item)) {
                $existingItem->setQuantity(
                    $existingItem->getQuantity() + $item->getQuantity()
                );
                return $this;
            }
        }*/
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $item->setOrderRef($this);
        }

        return $this;
    }

    public function removeItem(OrderItem $item): self
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getOrderRef() === $this) {
                $item->setOrderRef(null);
            }
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getNoteCart(): ?string
    {
        return $this->noteCart;
    }

    /**
     * @param mixed $noteCart
     */
    public function setNoteCart($noteCart = null): void
    {
        $this->noteCart = $noteCart;
    }

    /**
     * @return mixed
     */
    public function getNoteAdmin(): ?string
    {
        return $this->noteAdmin;
    }

    /**
     * @param mixed $noteAdmin
     */
    public function setNoteAdmin($noteAdmin = null): void
    {
        $this->noteAdmin = $noteAdmin;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address): void
    {
        $this->address = $address;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone): void
    {
        $this->phone = $phone;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCartAt()
    {
        return $this->cartAt;
    }

    /**
     * @param mixed $cartAt
     */
    public function setCartAt($cartAt): void
    {
        $this->cartAt = $cartAt;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getProcessAt(): ?\DateTimeInterface
    {
        return $this->processAt;
    }

    public function setProcessAt(?\DateTimeInterface $processAt = null): self
    {
        $this->processAt = $processAt;

        return $this;
    }

    public function getTransportAt(): ?\DateTimeInterface
    {
        return $this->transportAt;
    }

    public function setTransportAt(?\DateTimeInterface $transportAt = null): self
    {
        $this->transportAt = $transportAt;

        return $this;
    }

    public function getDeliveredAt(): ?\DateTimeInterface
    {
        return $this->deliveredAt;
    }

    public function setDeliveredAt(?\DateTimeInterface $deliveredAt = null): self
    {
        $this->deliveredAt = $deliveredAt;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsDelivered()
    {
        return $this->isDelivered;
    }

    /**
     * @param mixed $isDelivered
     */
    public function setIsDelivered($isDelivered = false): void
    {
        $this->isDelivered = $isDelivered;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     */
    public function setUpdatedAt($updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * Removes all items from the order.
     *
     * @return $this
     */
    public function removeItems(): self
    {
        foreach ($this->getItems() as $item) {
            $this->removeItem($item);
        }

        return $this;
    }

    /**
     * Calculates the order total.
     *
     * @return float
     */
    public function getTotal(): float
    {
        $total = 0;

        foreach ($this->getItems() as $item) {
            $total += $item->getTotal();
        }

        return $total;
    }

}
