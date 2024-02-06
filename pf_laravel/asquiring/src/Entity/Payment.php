<?php

namespace App\Entity;

use App\Enum\PaymentType;
use App\Enum\Provider;
use App\Enum\Status;
use App\Repository\PaymentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Entity(repositoryClass=PaymentRepository::class)
 * @ORM\Table(name="payments", indexes={
 *     @ORM\Index(name="payments_order_idx", columns={"order_id"})
 * })
 * @ORM\HasLifecycleCallbacks
 */
class Payment
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private Uuid $id;

    /**
     * @ORM\Column(type="uuid")
     */
    private Uuid $orderId;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private string $provider;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private ?string $type;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private string $deviceType;

    /**
     * @ORM\Column(type="datetimetz")
     */
    private \DateTimeInterface $createdAt;

    /**
     * @ORM\Column(type="datetimetz", nullable=true)
     */
    private ?\DateTimeInterface $updatedAt;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private ?string $invoiceId = null;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private ?string $qrId = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $paymentUrl = null;

    /**
     * @ORM\Column(type="datetimetz", nullable=true)
     */
    private ?\DateTimeInterface $paidAt = null;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private ?string $paidStatus = null;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $transactionId = null;

    /**
     * @ORM\Column(type="datetimetz", nullable=true)
     */
    private ?\DateTimeInterface $verifiedAt = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $verifyStatus = null;

    public function __construct(Uuid $orderId, string $provider, string $deviceType)
    {
        $this->id = Uuid::v4();
        $this->createdAt = new \DateTime();
        $this->orderId = $orderId;
        $providerArray = explode('-', $provider);
        $this->provider = Provider::of($providerArray[0]);
        $this->type = count($providerArray) > 1 ? PaymentType::of($providerArray[1]) : '';
        $this->deviceType = $deviceType;
        $this->paidStatus = Status::CREATED;
    }

    /**
     * @ORM\PreUpdate
     */
    public function updateTimestamps()
    {
        $this->updatedAt = new \DateTime();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getStringId(): string
    {
        return str_replace('-', '', $this->id->toRfc4122());
    }

    public function getOrderId(): Uuid
    {
        return $this->orderId;
    }

    public function getProvider(): ?string
    {
        return $this->provider;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getProviderType(): ?string
    {
        return $this->provider . (!empty($this->type) ? '-' . $this->type : '');
    }

    public function getDeviceType(): ?string
    {
        return $this->deviceType;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function getInvoiceId(): ?string
    {
        return $this->invoiceId;
    }

    public function setInvoiceId(?string $invoiceId): self
    {
        $this->invoiceId = $invoiceId;

        return $this;
    }

    public function getQrId(): ?string
    {
        return $this->qrId;
    }

    public function setQrId(?string $qrId): self
    {
        $this->qrId = $qrId;

        return $this;
    }

    public function getPaymentUrl(): ?string
    {
        return $this->paymentUrl;
    }

    public function setPaymentUrl(?string $paymentUrl): self
    {
        $this->paymentUrl = $paymentUrl;

        return $this;
    }

    public function getPaidAt(): ?\DateTimeInterface
    {
        return $this->paidAt;
    }

    public function setPaidAt(?\DateTimeInterface $paidAt): self
    {
        $this->paidAt = $paidAt;

        return $this;
    }

    public function getPaidStatus(): ?string
    {
        return $this->paidStatus;
    }

    public function setPaidStatus(?string $paidStatus): self
    {
        $this->paidStatus = $paidStatus;

        return $this;
    }

    public function getTransactionId(): ?int
    {
        return $this->transactionId;
    }

    public function setTransactionId(?int $transactionId): self
    {
        $this->transactionId = $transactionId;

        return $this;
    }

    public function getVerifiedAt(): ?\DateTimeInterface
    {
        return $this->verifiedAt;
    }

    public function setVerifiedAt(?\DateTimeInterface $verifiedAt): self
    {
        $this->verifiedAt = $verifiedAt;

        return $this;
    }

    public function getVerifyStatus(): ?string
    {
        return $this->verifyStatus;
    }

    public function setVerifyStatus(?string $verifyStatus): self
    {
        $this->verifyStatus = $verifyStatus;

        return $this;
    }

    public function isProcess(): bool
    {
        return Status::PROCESS === $this->paidStatus;
    }

    public function isSuccess(): bool
    {
        return Status::SUCCESS === $this->paidStatus;
    }

    public function isFail(): bool
    {
        return Status::FAIL === $this->paidStatus;
    }
}
