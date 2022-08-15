<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\Validator\UserPassword;

use App\Admin\Application\UseCase\Query\FindByEmail\FindByEmailQuery;
use App\Admin\Domain\DataTransfer\Admin;
use App\Common\Application\Query\QueryBusInterface;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\ConstraintValidator;

class UserPasswordValidator extends ConstraintValidator
{
    public function __construct(
        private TokenStorageInterface $tokenStorage,
        private PasswordHasherFactoryInterface $hasherFactory,
        private QueryBusInterface $queryBus
    ) {
    }

    public function validate(mixed $password, Constraint $constraint): void
    {
        if (!$constraint instanceof UserPassword) {
            throw new UnexpectedTypeException($constraint, UserPassword::class);
        }

        /** @var Admin $payload */
        $payload = $this->context->getObject();
        if ($payload === null) {
            return;
        }

        if ($payload->getPassword() === null || $payload->getPassword() === '') {
            return;
        }

        if ($password === null || $password === '') {
            $this->context->addViolation($constraint->message);

            return;
        }

        if (!\is_string($password)) {
            throw new UnexpectedTypeException($password, 'string');
        }

        $user = $this->tokenStorage->getToken()?->getUser();
        if (!$user instanceof PasswordAuthenticatedUserInterface) {
            throw new ConstraintDefinitionException(sprintf(
                'The "%s" class must implement the "%s" interface.',
                PasswordAuthenticatedUserInterface::class,
                get_debug_type($user)
            ));
        }

        if ($this->isNotCurrentPassword($user, $password)) {
            $this->context->addViolation($constraint->message);
        }
    }

    private function isNotCurrentPassword(UserInterface $user, string $password): bool
    {
        $hasher = $this->hasherFactory->getPasswordHasher($user);

        /** @var Admin $userWithPassword */
        $userWithPassword = $this->queryBus->handle(new FindByEmailQuery($user->getUserIdentifier()));

        return $userWithPassword === null
            || $userWithPassword->getPassword() === null
            || !$hasher->verify($userWithPassword->getPassword(), $password);
    }
}
