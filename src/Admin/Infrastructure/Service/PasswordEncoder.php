<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\Service;

use App\Admin\Application\Service\PasswordEncoderInterface;
use App\Admin\Domain\Entity\Embedded\Password;
use App\Admin\Domain\Entity\Embedded\PlainPassword;
use App\Admin\Infrastructure\Security\AdminIdentity;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

class PasswordEncoder implements PasswordEncoderInterface
{
    public function __construct(
        private PasswordHasherFactoryInterface $passwordHasherFactory
    ) {
    }

    public function encode(PlainPassword $password): Password
    {
        return new Password(
            $this->passwordHasherFactory
                ->getPasswordHasher(AdminIdentity::class)
                ->hash($password->__toString())
        );
    }
}
