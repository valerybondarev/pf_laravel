<?php

namespace App\Entity;

use App\Repository\PaymentBaseRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Entity(repositoryClass=PaymentBaseRepository::class)
 * @ORM\Table(name="payment_base", indexes={
 *     @ORM\Index(name="payment_base_unit_idx", columns={"unit_id"}),
 *     @ORM\Index(name="payment_base_product_idx", columns={"product"})
 * })
 */
class PaymentBase
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private Uuid $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $unitId;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private string $product;

    /**
     * @ORM\Column(type="decimal", precision=15, scale=2)
     */
    private float $amount;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $paymentNumber;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $payNumber;

    /**
     * @ORM\Column(type="datetimetz")
     */
    private \DateTimeInterface $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=Order::class, inversedBy="paymentBasis")
     * @ORM\JoinColumn(nullable=false)
     */
    private Order $order;

    public function __construct(string $unitId, string $product, float $amount, ?int $paymentNumber, ?int $payNumber)
    {
        $this->id = Uuid::v4();
        $this->createdAt = new \DateTimeImmutable();
        $this->unitId = $unitId;
        $this->product = $product;
        $this->amount = $amount;
        $this->paymentNumber = $paymentNumber;
        $this->payNumber = $payNumber;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getUnitId(): string
    {
        return $this->unitId;
    }

    public function getProduct(): string
    {
        return $this->product;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getOrder(): ?Order
    {
        return $this->order;
    }

    public function setOrder(?Order $order): self
    {
        $this->order = $order;

        return $this;
    }

    public function getPaymentNumber(): ?int
    {
        return $this->paymentNumber;
    }

    public function getPayNumber(): ?int
    {
        return $this->payNumber;
    }
}
