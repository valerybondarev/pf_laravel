<?php

namespace App\Entity;

use App\Repository\PaymentRequestRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Entity(repositoryClass=PaymentRequestRepository::class)
 * @ORM\Table(name="payments_request", indexes={
 *     @ORM\Index(name="payments_request_payment_idx", columns={"payment_id"})
 * })
 */
class PaymentRequest
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
     * @ORM\Column(type="string", length=20)
     */
    private ?string $method;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $url;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private ?array $request;

    public function __construct(\DateTimeImmutable $createdAt, Uuid $paymentId, string $method, string $url, array $request, ?Uuid $id = null)
    {
        $this->id = !is_null($id) ? $id : Uuid::v4();
        $this->createdAt = $createdAt;
        $this->paymentId = $paymentId;
        $this->method = $method;
        $this->url = $url;
        $this->request = $request;
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

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getRequest(): ?array
    {
        return $this->request;
    }
}
