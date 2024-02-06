<?php

namespace App\Services;

use App\Dto\Controller\RequestDtoInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class ArgumentValueResolver implements ArgumentValueResolverInterface
{
    public function __construct(private SerializerInterface $serializer)
    {
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        try {
            $reflection = new \ReflectionClass($argument->getType());

            return $reflection->implementsInterface(RequestDtoInterface::class);
        } catch (\ReflectionException $e) {
            return false;
        }
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $class = $argument->getType();
        if (!empty($request->getContent())) {
            yield $this->serializer->deserialize($request->getContent(), $class, 'json',
                /* [DateTimeNormalizer::FORMAT_KEY => DateTimeInterface::ISO8601] */
                [DateTimeNormalizer::TIMEZONE_KEY => 'UTC']
            );
        } else {
            yield new $class();
        }
    }
}
