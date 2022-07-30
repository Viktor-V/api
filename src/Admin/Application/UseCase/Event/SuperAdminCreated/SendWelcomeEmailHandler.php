<?php

declare(strict_types=1);

namespace App\Admin\Application\UseCase\Event\AdminCreated;

use App\Admin\Application\Service\WelcomeNotifierInterface;
use App\Admin\Domain\Event\SuperAdminCreatedEvent;
use App\Common\Domain\Event\EventHandlerInterface;

class SendWelcomeEmailHandler implements EventHandlerInterface
{
    public function __construct(
        private WelcomeNotifierInterface $notifier
    ) {
    }

    public function __invoke(SuperAdminCreatedEvent $event): void
    {
        $this->notifier->send($event);
    }
}
