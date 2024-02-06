<?php

namespace App\Intf;

use App\Entity\Order;
use App\Exception\ExternalSystemException;

interface OrderDetailInterface
{
    public function supports(string $source): bool;

    /**
     * @throws ExternalSystemException
     */
    public function getDetail(Order $order): ?array;

    /**
     * @throws ExternalSystemException
     */
    public function getContractor(Order $order): ?array;
}
