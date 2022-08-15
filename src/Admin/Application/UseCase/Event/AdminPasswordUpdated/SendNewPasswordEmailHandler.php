<?php

declare(strict_types=1);

namespace App\Admin\Application\UseCase\Event\AdminPasswordUpdated;

use App\Admin\Application\Service\NewPasswordNotifierInterface;
use App\Admin\Domain\Event\AdminPasswordUpdatedEvent;
use App\Common\Domain\Event\EventHandlerInterface;

class SendNewPasswordEmailHandler implements EventHandlerInterface
{
    public function __construct(
        private NewPasswordNotifierInterface $notifier
    ) {
    }

    public function __invoke(AdminPasswordUpdatedEvent $event): void
    {
        $this->notifier->send($event);
    }
}
