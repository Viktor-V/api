<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\Service;

use App\Admin\Application\Service\WelcomeNotifierInterface;
use App\Admin\Domain\Event\SuperAdminCreatedEvent;
use App\Admin\Domain\Event\AdminRegisteredEvent;
use App\Common\Domain\Event\EventInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class WelcomeNotifier implements WelcomeNotifierInterface
{
    public function __construct(
        private MailerInterface $mailer
    ) {
    }

    public function send(EventInterface $event): void
    {
        $subject = 'Welcome to the team!';
        $email = (new TemplatedEmail());

        if ($event instanceof SuperAdminCreatedEvent) {
            $email
                ->to($event->getEmail()->__toString())
                ->subject($subject)
                ->context([
                    'subject' => $subject,
                    'user' => $event->getEmail()->__toString(),
                    'name' => $event->getName()->__toString(),
                    'password' => $event->getPlainPassword()->__toString()
                ])
                ->htmlTemplate('mail/admin/super_admin_created.html.twig');
        }

        if ($event instanceof AdminRegisteredEvent) {
            $email
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
        }

        $this->mailer->send($email);
    }
}
