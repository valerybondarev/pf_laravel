<?php

namespace App\Message;

final class UCSPaymentNotification
{
    public function __construct(public string $InvoiceID,
                                public bool $PayConfirmed,
                                public ?string $PaymentSystemId,
                                public ?int $TransactionID)
    {
    }
}
