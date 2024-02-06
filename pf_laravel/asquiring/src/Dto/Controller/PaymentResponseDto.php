<?php

namespace App\Dto\Controller;

use OpenApi\Attributes\Property;
use Symfony\Component\Serializer\Annotation\SerializedName;

class PaymentResponseDto
{
    #[Property(description: 'id платежа')]
    public string $id;

    #[SerializedName('order_id')]
    #[Property(description: 'id страхового договора (поле id таблицы Orders)')]
    public string $orderId;

    #[Property(description: 'Наименование ПС и метод оплаты')]
    public string $provider;

    #[SerializedName('device_type')]
    #[Property(description: 'Тип устройства')]
    public string $deviceType;

    #[SerializedName('created_at')]
    #[Property(description: 'Дата создания записи')]
    public \DateTimeInterface $createdAt;

    #[SerializedName('updated_at')]
    #[Property(description: 'Дата последнего обновления записи')]
    public ?\DateTimeInterface $updatedAt;

    #[SerializedName('invoice_id')]
    #[Property(description: 'id платежа во внешней системе')]
    public ?string $invoiceId;

    #[SerializedName('qr_id')]
    #[Property(description: 'id qr-кода')]
    public ?string $qrId;

    #[SerializedName('payment_url')]
    #[Property(description: 'Ссылка на оплату')]
    public ?string $paymentUrl;

    #[SerializedName('paid_at')]
    #[Property(description: 'Дата проведения платежа в ПС')]
    public ?\DateTimeInterface $paidAt;

    #[SerializedName('paid_status')]
    #[Property(description: 'Статус платежа в ПС')]
    public ?string $paidStatus;

    #[SerializedName('transaction_id')]
    #[Property(description: 'id транзакции')]
    public ?int $transactionId;

    #[SerializedName('verified_at')]
    #[Property(description: 'Дата получения данных о совершении платежа из банка')]
    public ?\DateTimeInterface $verifiedAt;

    #[SerializedName('verify_status')]
    #[Property(description: 'Статус верификации')]
    public ?string $verifyStatus;
}
