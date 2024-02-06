<?php

namespace App\Message;

use OpenApi\Attributes\Property;
use Symfony\Component\Serializer\Annotation\SerializedName;

class LotusNotification
{
    #[SerializedName('id')]
    #[Property(description: 'id ордера')]
    public string $id;

    #[SerializedName('success')]
    #[Property(description: 'Статус')]
    public bool $success;

    public function __construct(string $id, bool $success)
    {
        $this->id = $id;
        $this->success = $success;
    }
}
