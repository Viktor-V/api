<?php

declare(strict_types=1);

namespace App\Common\Domain\Assert;

use Webmozart\Assert\Assert;

class Assertion extends Assert
{
    /**
     * @psalm-pure this method is not supposed to perform side-effects
     */
    protected static function reportInvalidArgument($message)
    {
        throw new InvalidArgumentException($message);
    }
}
