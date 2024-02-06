<?php

namespace App\Tests\Unit;

use App\Entity\Payment;
use App\Enum\Provider;
use App\MercureEvents\Event;
use App\MercureEvents\Type;
use App\Repository\PaymentRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Uid\Uuid;

class PaymentCallbackControllerTest extends KernelTestCase
{
    public function testNotFoundPaymentFromRepository(): void
    {
        $paymentRepository = $this->createMock(PaymentRepository::class);

        $this->assertNull($paymentRepository->find('123321321'));
    }

    public function testPublishEventLogic(): void
    {
        $this->assertNull(
            $this
                ->createMock(Event::class)
                ->publish(Type::ORDER_STATUS_HAS_BEEN_CHANGED, 123321123321)
        );
    }

    public function testLogic(): void
    {
        $uuid = Uuid::v4();

        $paymentMock = new Payment(
            $uuid,
            Provider::SBER,
            'desktop'
        );

        $paymentRepository = $this->createMock(PaymentRepository::class);
        $paymentRepository->method('find')->willReturn($paymentMock);

        $payment = $paymentRepository->find('test');

        $this->assertSame($paymentMock->getId()->toRfc4122(), $payment->getId()->toRfc4122());

        $this->assertNull(
            $this
                ->createMock(Event::class)
                ->publish(Type::ORDER_STATUS_HAS_BEEN_CHANGED, $payment->getId()->toRfc4122())
        );
    }
}