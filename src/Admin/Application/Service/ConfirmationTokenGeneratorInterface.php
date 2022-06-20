<?php

declare(strict_types=1);

namespace App\Admin\Application\Service;

interface ConfirmationTokenGeneratorInterface
{
    public function generate(): string;
}
