<?php

namespace App\Entity;

use App\Repository\SubProductsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;

#[ORM\Entity(repositoryClass: SubProductsRepository::class)]
class SubProducts implements TimestampableInterface
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'subProducts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 2)]
    private ?string $price = null;

    #[ORM\Column(nullable: true)]
    private ?int $execution = null;

    #[ORM\Column]
    private ?bool $status = null;

    #[ORM\OneToMany(mappedBy: 'subProduct', targetEntity: ModelSubProduct::class, orphanRemoval: true)]
    private Collection $modelSubProducts;

    public function __construct()
    {
        $this->modelSubProducts = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getExecution(): ?int
    {
        return $this->execution;
    }

    public function setExecution(int $execution): static
    {
        $this->execution = $execution;

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

    /**
     * @return Collection<int, ModelSubProduct>
     */
    public function getModelSubProducts(): Collection
    {
        return $this->modelSubProducts;
    }

    public function addModelSubProduct(ModelSubProduct $modelSubProduct): static
    {
        if (!$this->modelSubProducts->contains($modelSubProduct)) {
            $this->modelSubProducts->add($modelSubProduct);
            $modelSubProduct->setSubProduct($this);
        }

        return $this;
    }

    public function removeModelSubProduct(ModelSubProduct $modelSubProduct): static
    {
        if ($this->modelSubProducts->removeElement($modelSubProduct)) {
            // set the owning side to null (unless already changed)
            if ($modelSubProduct->getSubProduct() === $this) {
                $modelSubProduct->setSubProduct(null);
            }
        }

        return $this;
    }
}
