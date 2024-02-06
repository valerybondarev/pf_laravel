<?php

namespace App\Dto\Controller;

use OpenApi\Attributes\Property;
use Symfony\Component\Serializer\Annotation\SerializedName;

class OrderResponseDto
{
    #[Property(description: 'uuid записи таблицы Orders')]
    public string $id;

    #[SerializedName('total_amount')]
    #[Property(description: 'Сумма платежа')]
    public float $totalAmount;

    #[SerializedName('client_id')]
    #[Property(description: 'id клиента в системе, заполняется, если полис был заведен в b2c без участия агента')]
    public ?string $clientId = null;

    #[SerializedName('agent_id')]
    #[Property(description: 'id агента в системе, заполняется, если полис был заведен через агента')]
    public ?string $agentId = null;

    #[Property(description: 'Статус записи')]
    public string $status;

    #[SerializedName('created_at')]
    #[Property(description: 'Дата создания записи')]
    public \DateTimeInterface $createdAt;

    #[SerializedName('expires_at')]
    #[Property(description: 'Дата, до которой необходимо внести оплату по полису')]
    public ?\DateTimeInterface $expiresAt;

    #[SerializedName('deleted_at')]
    #[Property(description: 'Дата удаления записи')]
    public ?\DateTimeInterface $deletedAt;

    #[SerializedName('ucs_bill')]
    #[Property(description: 'Номер счета в УЦС')]
    public ?string $ucsBill;

    #[SerializedName('ucs_invoice_id')]
    #[Property(description: 'Ид счета в УЦС')]
    public ?string $ucsInvoiceId;

    #[SerializedName('ucs_created_at')]
    #[Property(description: 'Дата создания счета в УЦС')]
    public ?\DateTimeInterface $ucsCreatedAt;

    #[Property(description: 'Источник платежа')]
    public string $source;

    #[SerializedName('payment_basis')]
    #[Property(description: 'Данные по продуктам')]
    /** @var PaymentBaseDto[] */
    public array $paymentBasis;

    #[SerializedName('contractor_name')]
    #[Property(description: 'Плательщик')]
    public ?string $contractorName;

    #[SerializedName('return_url')]
    #[Property(description: 'Адрес возврата')]
    public ?string $returnUrl;

    #[Property(description: 'Признак ошибки')]
    public bool $success = true;
}
