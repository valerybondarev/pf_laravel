<?php

namespace App\Entity;

use App\Repository\PaymentResponseRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Entity(repositoryClass=PaymentResponseRepository::class)
 * @ORM\Table(name="payments_response", indexes={
 *     @ORM\Index(name="payments_response_request_idx", columns={"request_id"})
 * })
 */
class PaymentResponse
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
    private ?Uuid $requestId;

    /**
     * @ORM\Column(type="datetimetz")
     */
    private \DateTimeInterface $createdAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $success;

    /**
     * @ORM\Column(type="json")
     */
    private array $response;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private ?string $errorCode;

    /**
     * @ORM\Column(type="string", length=512, nullable=true)
     */
    private ?string $errorMessage;

    public function __construct(\DateTimeImmutable $createdAt, Uuid $requestId, array $response, bool $success, ?string $errorCode = null, ?string $errorMessage = null, ?Uuid $id = null)
    {
        $this->id = !is_null($id) ? $id : Uuid::v4();
        $this->createdAt = $createdAt;
        $this->requestId = $requestId;
        $this->response = $response;
        $this->success = $success;
        $this->errorCode = $errorCode;
        $this->errorMessage = $errorMessage;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getRequestId(): ?Uuid
    {
        return $this->requestId;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getSuccess(): bool
    {
        return $this->success;
    }

    public function getErrorCode(): ?string
    {
        return $this->errorCode;
    }

    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

    public function getResponse(): ?array
    {
        return $this->response;
    }
}
