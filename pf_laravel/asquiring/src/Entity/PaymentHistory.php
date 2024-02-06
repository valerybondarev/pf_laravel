<?php

namespace App\Entity;

use App\Repository\PaymentHistoryRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Entity(repositoryClass=PaymentHistoryRepository::class)
 * @ORM\Table(name="payments_history", indexes={
 *     @ORM\Index(name="payments_history_payment_idx", columns={"payment_id"})
 * })
 */
class PaymentHistory
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
    private Uuid $paymentId;

    /**
     * @ORM\Column(type="datetimetz")
     */
    private \DateTimeInterface $createdAt;

    /**
     * @ORM\Column(type="json")
     */
    private array $payload;

    public function __construct(\DateTimeImmutable $createdAt, Uuid $paymentId, array $payload, ?Uuid $id = null)
    {
        $this->id = !is_null($id) ? $id : Uuid::v4();
        $this->createdAt = $createdAt;
        $this->paymentId = $paymentId;
        $this->payload = $payload;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getPaymentId(): Uuid
    {
        return $this->paymentId;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getPayload(): array
    {
        return $this->payload;
    }
}
