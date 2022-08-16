<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\Validator\UserPassword;

use Symfony\Component\Validator\Constraint;

class UserPassword extends Constraint
{
    public string $message = 'This value should be the user\'s current password.';

    public function validatedBy(): string
    {
        return UserPasswordValidator::class;
    }
}
