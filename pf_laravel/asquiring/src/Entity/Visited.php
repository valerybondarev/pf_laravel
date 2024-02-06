<?php

namespace App\Entity;

use App\Repository\VisitedRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Entity(repositoryClass=VisitedRepository::class)
 * @ORM\Table(indexes={
 *     @ORM\Index(name="visited_order_idx", columns={"order_id"})
 * })
 */
class Visited
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
     * @ORM\Column(type="datetimetz")
     */
    private \DateTimeInterface $createdAt;

    /**
     * @ORM\Column(type="datetimetz")
     */
    private \DateTimeInterface $visitedAt;

    public function __construct(\DateTimeImmutable $createdAt, Uuid $orderId, \DateTimeImmutable $visitedAt, ?Uuid $id = null)
    {
        $this->id = !is_null($id) ? $id : Uuid::v4();
        $this->orderId = $orderId;
        $this->createdAt = $createdAt;
        $this->visitedAt = $visitedAt;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getOrderId(): Uuid
    {
        return $this->orderId;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getVisitedAt(): \DateTimeInterface
    {
        return $this->visitedAt;
    }
}
