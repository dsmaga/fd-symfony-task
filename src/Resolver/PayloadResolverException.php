<?php

declare(strict_types=1);

namespace App\Resolver;

use Exception;
use Symfony\Component\Serializer\Exception\MissingConstructorArgumentsException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class PayloadResolverException extends Exception
{
    /**
     * @var array<string, string>
     */
    private array $errors = [];

    public function addError(string $key, string $message): void
    {
        $this->errors[$key] = $message;
    }

    /**
     * @param array<string, string> $errors
     */
    public function setErrors(array $errors): void
    {
        $this->errors = $errors;
    }

    /**
     * @return array<string, string>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param array<string, string> $errors
     */
    public static function fromErrors(array $errors): self
    {
        $self = new self();
        $self->setErrors($errors);

        return $self;
    }

    public static function fromValidationFailedException(ValidationFailedException $exception): self
    {
        return self::fromConstraintViolationListInterface($exception->getViolations());
    }

    public static function fromConstraintViolationListInterface(ConstraintViolationListInterface $violations): self
    {
        $self = new self();

        foreach ($violations as $violation) {
            $self->addError($violation->getPropertyPath(), (string) $violation->getMessage());
        }

        return $self;
    }

    public static function fromMissingConstructorArgumentsException(
        MissingConstructorArgumentsException $exception
    ): self {
        $self = new self();

        foreach ($exception->getMissingConstructorArguments() as $argument) {
            $self->addError($argument, 'This field is required.');
        }

        return $self;
    }

    public static function fromNotNormalizableValueException(NotNormalizableValueException $exception): self
    {
        $self = new self();
        if ($exception->getCurrentType() === null && $exception->getExpectedTypes() === null) {
            $message = $exception->getMessage();
        } elseif ($exception->getCurrentType() === 'null') {
            $message = 'This field is required.';
        } else {
            $message = sprintf(
                'Expected type(s): %s. Given: "%s"',
                implode(', ', (array)$exception->getExpectedTypes()),
                $exception->getCurrentType()
            );
        }
        $self->addError($exception->getPath() ?: '_', $message);

        return $self;
    }

    public static function fromException(Exception $exception): self
    {
        $self = new self();
        $self->addError('_', $exception->getMessage());
        return $self;
    }
}
