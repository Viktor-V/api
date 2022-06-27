<?php

declare(strict_types=1);

namespace App\Admin\Application\UseCase\Event\AdminRegistered;

use App\Admin\Domain\Event\AdminRegisteredEvent;
use App\Common\Domain\Event\EventHandlerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class SendWelcomeEmailHandler implements EventHandlerInterface
{
    public function __construct(
        private MailerInterface $mailer
    ) {
    }

    public function __invoke(AdminRegisteredEvent $event): void
    {
        $subject = 'Welcome to the team!'; // TODO: trans

        $email = (new TemplatedEmail())
            ->to($event->getEmail()->__toString())
            ->subject($subject)
            ->context([
                'subject' => $subject,
                'user' => $event->getEmail()->__toString(),
                'name' => $event->getName()->__toString(),
                'confirmationToken' => $event->getConfirmationToken()->__toString(),
                'password' => $event->getPlainPassword()->__toString()
            ])
            ->htmlTemplate('mail/admin/registered.html.twig');

        $this->mailer->send($email);
    }
}
