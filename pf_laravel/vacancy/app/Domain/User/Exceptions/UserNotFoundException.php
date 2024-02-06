<?php
/**
 * Created date: 19.04.2019 17:55
 *
 * @author yamilramilev <yamilramilev@gmail.com>
 */

namespace App\Domain\User\Exceptions;

use Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserNotFoundException extends NotFoundHttpException
{
    public function __construct(string $message = null, Exception $previous = null, int $code = 0, array $headers = [])
    {
        parent::__construct($message ?? __('User not found'), $previous, $code, $headers);
    }
}
