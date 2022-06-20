<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\Service;

use App\Admin\Application\Service\ConfirmationTokenGeneratorInterface;
use Exception;

class ConfirmationTokenGenerator implements ConfirmationTokenGeneratorInterface
{
    /**
     * @throws Exception
     */
    public function generate(): string
    {
        return bin2hex(random_bytes(16));
    }
}
