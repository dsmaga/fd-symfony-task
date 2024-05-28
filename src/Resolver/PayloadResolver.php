<?php

declare(strict_types=1);

namespace App\Resolver;

use Symfony\Component\Serializer\Exception\MissingConstructorArgumentsException;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final readonly class PayloadResolver
{
    public function __construct(
        private SerializerInterface $serializer,
        private ValidatorInterface $validator
    ) {
    }

    /**
     * @template T of object
     * @param class-string<T> $className
     * @throws PayloadResolverException
     * @return T
     */
    public function resolve(string $payload, string $className): object
    {
        try {
            $object = $this->serializer->deserialize($payload, $className, 'json');
            $errors = $this->validator->validate($object);

            if (count($errors) > 0) {
                throw new ValidationFailedException($object, $errors);
            }

            return $object;
        } catch (ValidationFailedException $exception) {
            throw PayloadResolverException::fromValidationFailedException($exception);
        } catch (MissingConstructorArgumentsException $exception) {
            throw PayloadResolverException::fromMissingConstructorArgumentsException($exception);
        } catch (NotNormalizableValueException $exception) {
            throw PayloadResolverException::fromNotNormalizableValueException($exception);
        } catch (NotEncodableValueException $exception) {
            throw PayloadResolverException::fromException($exception);
        }
    }
}
