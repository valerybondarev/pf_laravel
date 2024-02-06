<?php

namespace App\Dto\Controller;

use App\Enum\ErrorCode;
use OpenApi\Attributes\Property;
use Symfony\Component\Serializer\Annotation\SerializedName;

class ErrorDto
{
    #[SerializedName('error_code')]
    #[Property(description: 'Код ошибки')]
    public int $errorCode;

    #[SerializedName('error_message')]
    #[Property(description: 'Текст ошибки')]
    public ?string $errorMessage;

    #[Property(description: 'Признак ошибки')]
    public bool $success = false;

    public function __construct(int $errorCode, ?string $errorMessage = null)
    {
        $this->errorCode = $errorCode;
        if (is_null($errorMessage) && array_key_exists($errorCode, ErrorCode::ERROR_MESSAGES)) {
            $errorMessage = ErrorCode::ERROR_MESSAGES[$errorCode];
        }
        $this->errorMessage = $errorMessage;
    }
}
