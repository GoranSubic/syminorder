<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\OrderItemRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderItemRepository::class)
 * @ORM\HasLifecycleCallbacks()
 *
 * @ApiResource(
 *  collectionOperations={"get"={
 *     "security"="(is_granted('ROLE_USER') and object.getOrderRef().getCustomer() == user) or is_granted('ROLE_WAITER') or is_granted('ROLE_DRIVER')",
 *     "security_message"="Only workers can get collections of a orderItems!",
 *     "normalization_context"={"groups"="orderitem:list"},}
 *     },
 *  itemOperations={"get"={
 *     "security"="(is_granted('ROLE_USER') and object.getOrderRef().getCustomer() == user) or is_granted('ROLE_WAITER') or is_granted('ROLE_DRIVER')",
 *     "security_message"="Only the creator can get orderItem!",
 *     "normalization_context"={"groups"="orderitem:item"},}
 *     },
 *  order={"product"="DESC"},
 *  paginationEnabled=false
 * )
 *
 * @ApiFilter(SearchFilter::class, properties={"conference": "exact"})
 */
class OrderItem
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @Groups({"orderitem:list", "orderitem:item"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class)
     * @ORM\JoinColumn(nullable=false)
     *
     * @Groups({"orderitem:list", "orderitem:item", "order:list", "order:write", "suborder"})
     */
    private $product;

    /**
     * @ORM\Column(name="product_code", type="string", length=255)
     *
     * @Groups({"orderitem:list", "orderitem:item", "order:list", "order:write"})
     */
    private $productCode;

    /**
     * @ORM\Column(type="integer")
     *
     * @Groups({"orderitem:list", "orderitem:item", "order:list", "order:write", "suborder"})
     */
    private $quantity;

    /**
     * @ORM\Column(name="ordered_item_price", type="integer", nullable=false)
     *
     * @Groups({"orderitem:list", "orderitem:item", "order:write"})
     */
    private $orderedItemPrice;

    /**
     * @ORM\ManyToOne(targetEntity=Order::class, inversedBy="items")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Groups({"orderitem:list", "orderitem:item"})
     */
    private $orderRef;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getProductCode()
    {
        return $this->productCode;
    }

    /**
     * @param mixed $productCode
     */
    public function setProductCode($productCode): void
    {
        $this->productCode = $productCode;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOrderedItemPrice()
    {
        return $this->orderedItemPrice;
    }

    /**
     * @param mixed $orderedItemPrice
     */
    public function setOrderedItemPrice($orderedItemPrice): void
    {
        $this->orderedItemPrice = $orderedItemPrice;
    }

    public function getOrderRef(): ?Order
    {
        return $this->orderRef;
    }

    public function setOrderRef(?Order $orderRef): self
    {
        $this->orderRef = $orderRef;

        return $this;
    }

    /**
     * Tests if the given item given corresponds to the same order item.
     *
     * @param OrderItem $item
     *
     * @return bool
     */
    public function equals(OrderItem $item): bool
    {
        return $this->getProduct()->getId() === $item->getProduct()->getId();
    }

    /**
     * Calculates the item total.
     *
     * @return float|int
     */
    public function getTotal(): float
    {
        return $this->getProduct()->getPrice() * $this->getQuantity();
    }

}
