<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\Validator\UserPassword;

class UserPassword extends \Symfony\Component\Security\Core\Validator\Constraints\UserPassword
{
    public $service = UserPasswordValidator::class;
}
