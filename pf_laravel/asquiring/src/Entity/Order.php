<?php

namespace App\Entity;

use App\Dto\Controller\Contractor;
use App\Enum\OrderTypes;
use App\Enum\Source;
use App\Enum\Status;
use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="orders", indexes={
 *     @ORM\Index(name="orders_ucs_bill_idx", columns={"ucs_bill"}),
 *     @ORM\Index(name="orders_status_idx", columns={"status"}),
 *     @ORM\Index(name="orders_source_idx", columns={"source"}),
 *     @ORM\Index(name="orders_created_at_idx", columns={"created_at"})
 * })
 * @ORM\HasLifecycleCallbacks
 */
class Order
{
    private const BASE_DOCUMENT_TYPE = 'ОСАГО';
    private const LOTUS_PAYMENT_NUMBER_PREFIX = 'Требование';

    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private Uuid $id;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private ?string $ucsBill = null;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private ?string $ucsInvoiceId = null;

    /**
     * @ORM\Column(type="datetimetz", nullable=true)
     */
    private ?\DateTimeInterface $ucsCreatedAt = null;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private ?string $agentId;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private ?string $clientId;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private string $source;

    /**
     * @ORM\Column(type="datetimetz")
     */
    private \DateTimeInterface $createdAt;

    /**
     * @ORM\Column(type="datetimetz", nullable=true)
     */
    private ?\DateTimeInterface $updatedAt = null;

    /**
     * @ORM\Column(type="datetimetz", nullable=true)
     */
    private ?\DateTimeInterface $expiresAt;

    /**
     * @ORM\Column(type="datetimetz", nullable=true)
     */
    private ?\DateTimeInterface $deletedAt = null;

    /**
     * @ORM\Column(type="decimal", precision=15, scale=2)
     */
    private float $totalAmount;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private string $status;

    /**
     * @var PaymentBase[]
     *
     * @ORM\OneToMany(targetEntity=PaymentBase::class, mappedBy="order", orphanRemoval=true, cascade={"remove", "persist"})
     */
    private $paymentBasis;

    /**
     * @ORM\Column(type="boolean", options={"default": false})
     */
    private bool $ucsBillNeed;

    /**
     * @ORM\Column(type="json_document", nullable=true)
     */
    private ?Contractor $contractor;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $contractorName;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $contractorInn;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $returnUrl;

    /**
     * @ORM\Column(type="string", length=50, options={"default": "client"})
     */
    private string $type;

    public function __construct(string $source,
                                float $totalAmount,
                                ?string $agentId,
                                ?string $clientId,
                                ?\DateTimeInterface $expiresAt,
                                bool $uscBillNeed,
                                ?Contractor $contractor,
                                ?string $returnUrl,
                                ?string $type)
    {
        $this->id = Uuid::v4();
        $this->createdAt = new \DateTimeImmutable();
        $this->source = $source;
        $this->agentId = $agentId;
        $this->clientId = $clientId;
        $this->status = Status::CREATED;
        $this->expiresAt = $expiresAt;
        $this->totalAmount = $totalAmount;
        $this->paymentBasis = new ArrayCollection();
        $this->ucsBillNeed = $uscBillNeed;
        $this->setContractor($contractor);
        $this->returnUrl = $returnUrl;
        $this->type = $type ?? OrderTypes::CLIENT_TYPE;
    }

    /**
     * @ORM\PreUpdate
     */
    public function updateTimestamps(): void
    {
        $this->updatedAt = new \DateTime();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getSource(): string
    {
        return $this->source;
    }

    public function getAgentId(): ?string
    {
        return $this->agentId;
    }

    public function getClientId(): ?string
    {
        return $this->clientId;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function getExpiresAt(): ?\DateTimeInterface
    {
        return $this->expiresAt;
    }

    public function getTotalAmount(): float
    {
        return $this->totalAmount;
    }

    public function getIntAmount(): int
    {
        return round($this->totalAmount * 100);
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function __toString(): string
    {
        return json_encode([
            'id' => $this->id,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
            'expiresAt' => $this->expiresAt,
            'status' => $this->status,
        ]);
    }

    public function isExpired(): bool
    {
        return !empty($this->getExpiresAt()) && ($this->getExpiresAt() <= new \DateTimeImmutable());
    }

    public function getUcsBill(): ?string
    {
        return $this->ucsBill;
    }

    public function setUcsBill(?string $ucsBill): void
    {
        $this->ucsBill = $ucsBill;
    }

    /**
     * @return Collection|PaymentBase[]
     */
    public function getPaymentBasis(): Collection
    {
        return $this->paymentBasis;
    }

    public function addPaymentBasis(PaymentBase $paymentBasis): self
    {
        if (!$this->paymentBasis->contains($paymentBasis)) {
            $this->paymentBasis[] = $paymentBasis;
            $paymentBasis->setOrder($this);
        }

        return $this;
    }

    public function removePaymentBasis(PaymentBase $paymentBasis): self
    {
        if ($this->paymentBasis->removeElement($paymentBasis)) {
            // set the owning side to null (unless already changed)
            if ($paymentBasis->getOrder() === $this) {
                $paymentBasis->setOrder(null);
            }
        }

        return $this;
    }

    public function getUcsCreatedAt(): ?\DateTimeInterface
    {
        return $this->ucsCreatedAt;
    }

    public function setUcsCreatedAt(?\DateTimeInterface $ucsCreatedAt): void
    {
        $this->ucsCreatedAt = $ucsCreatedAt;
    }

    public function getUcsInvoiceId(): ?string
    {
        return $this->ucsInvoiceId;
    }

    public function setUcsInvoiceId(?string $ucsInvoiceId): void
    {
        $this->ucsInvoiceId = $ucsInvoiceId;
    }

    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTimeInterface $deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }

    public function isSuccessful(): bool
    {
        return Status::SUCCESS === $this->status;
    }

    public function getUcsBillNeed(): ?bool
    {
        return $this->ucsBillNeed;
    }

    public function getBaseDocument(): ?string
    {
        $filtered = array_filter($this->paymentBasis->toArray(), function ($e) { return self::BASE_DOCUMENT_TYPE === $e->getProduct(); });

        return !empty($filtered) ? current($filtered)->getUnitId() : $this->paymentBasis->first()->getUnitId();
    }

    public function getPaymentDescription(): string
    {
        if (!is_null($this->ucsBill)) {
            return 'Оплата страховых продуктов по счету № ' . $this->ucsBill;
        } elseif ($this->isLotus()) {
            return 'Оплата требования № ' . $this->paymentBasis->first()->getUnitId();
        }

        $products = implode(', ', array_map(function ($e) { return $e->getUnitId(); }, $this->paymentBasis->toArray()));

        return (1 === $this->paymentBasis->count() ? 'Оплата полиса' : 'Оплата полисов') . ' ' . $products;
    }

    public function getContractor(): ?Contractor
    {
        return $this->contractor;
    }

    public function setContractor(?Contractor $contractor): void
    {
        $this->contractor = $contractor;

        $this->contractorName = !is_null($contractor) ? $contractor->getName() : null;
        $this->contractorInn = !is_null($contractor) ? $contractor->getInn() : null;
    }

    public function getContractorName(): ?string
    {
        return $this->contractorName;
    }

    public function getContractorInn(): ?string
    {
        return $this->contractorInn;
    }

    public function isSuccess(): bool
    {
        return Status::SUCCESS === $this->status;
    }

    public function isFail(): bool
    {
        return Status::FAIL === $this->status;
    }

    public function getReturnUrl(): ?string
    {
        return $this->returnUrl;
    }

    public function isLotus(): bool
    {
        return Source::LOTUS === $this->source;
    }

    public function isApi(): bool
    {
        return Source::API === $this->source;
    }

    public function isReport(): bool
    {
        return Source::REPORT === $this->source;
    }

    public function setTotalAmount(float $totalAmount): void
    {
        $this->totalAmount = $totalAmount;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getPaymentNumber(): ?string
    {
        if ($this->isLotus()) {
            return self::LOTUS_PAYMENT_NUMBER_PREFIX . ' ' . $this->paymentBasis->first()->getUnitId();
        }

        return $this->getUcsBill();
    }

    public function getPaymentId(): ?string
    {
        if ($this->isLotus()) {
            return null;
        }

        return $this->getUcsInvoiceId();
    }
}
