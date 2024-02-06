<?php

namespace App\Controller;

use App\Dto\Controller\ErrorDto;
use App\Enum\ErrorCode;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class BaseController extends AbstractController
{
    public function error(int $errorCode, ?string $errorMessage = '', ?int $status = Response::HTTP_BAD_REQUEST): JsonResponse
    {
        if (empty($errorMessage) && array_key_exists($errorCode, ErrorCode::ERROR_MESSAGES)) {
            $errorMessage = ErrorCode::ERROR_MESSAGES[$errorCode];
        }

        return $this->json(new ErrorDto($errorCode, $errorMessage), $status);
    }

    public function validationError(?string $errorMessage = ''): JsonResponse
    {
        return $this->error(ErrorCode::VALIDATION_ERROR, $errorMessage);
    }
}
