<?php

namespace App\Message;

use App\Enum\Status;

final class OrderStatusChange
{
    public string $ucsInvoiceId;

    public string $status;

    public function isStatusVerified(): bool
    {
        return Status::VERIFIED === $this->status;
    }
}
