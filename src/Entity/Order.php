<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order implements TimestampableInterface
{
    use TimestampableTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 255, nullable: false)]
    private ?int $user_id = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?SubProducts $subProduct = null;

    #[ORM\Column(length: 255, nullable: false)]
    private ?int $product_id = null;

    #[ORM\Column(length: 255, nullable: false)]
    private ?int $subProduct_id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $order_date = null;

    #[ORM\Column]
    private ?bool $status = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ModelSubProduct $modelSubProduct = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(?int $userId): static
    {
        $this->user_id = $userId;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): static
    {
        $this->product = $product;

        return $this;
    }

    public function getProductId(): ?Int
    {
        return $this->product_id;
    }

    public function setProductId(?int $productId): static
    {
        $this->product_id = $productId;

        return $this;
    }

    public function getSubProduct(): ?SubProducts
    {
        return $this->subProduct;
    }

    public function setSubProduct(?SubProducts $subProduct): static
    {
        $this->subProduct = $subProduct;

        return $this;
    }

    public function getSubProductId(): ?Int
    {
        return $this->subProduct_id;
    }

    public function setSubProductId(?int $subProductId): static
    {
        $this->subProduct_id = $subProductId;

        return $this;
    }

    public function getOrderDate(): ?\DateTime
    {
        return $this->order_date;
    }

    public function setOrderDate(?DateTime $orderDate): static
    {
        $this->order_date = $orderDate;

        return $this;
    }

    public function isStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getModelSubProduct(): ?ModelSubProduct
    {
        return $this->modelSubProduct;
    }

    public function setModelSubProduct(?ModelSubProduct $modelSubProduct): static
    {
        $this->modelSubProduct = $modelSubProduct;

        return $this;
    }
}
