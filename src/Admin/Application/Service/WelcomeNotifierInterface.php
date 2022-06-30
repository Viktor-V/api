<?php

namespace App\Admin\Application\Service;

use App\Common\Domain\Event\EventInterface;

interface WelcomeNotifierInterface
{
    public function send(EventInterface $event): void;
}
