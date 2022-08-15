<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\Service;

use App\Admin\Application\Service\NewPasswordNotifierInterface;
use App\Common\Domain\Event\EventInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class NewPasswordNotifier implements NewPasswordNotifierInterface
{
    public function __construct(
        private MailerInterface $mailer
    ) {
    }

    public function send(EventInterface $event): void
    {
        $subject = 'Your password has been changed!'; // TODO: trans

        $this->mailer->send(
            (new TemplatedEmail())
                ->to($event->getEmail()->__toString())
                ->subject($subject)
                ->context([
                    'subject' => $subject,
                    'user' => $event->getEmail()->__toString(),
                    'name' => $event->getName()->__toString(),
                    'password' => $event->getPlainPassword()->__toString()
                ])
                ->htmlTemplate('mail/admin/admin_password_updated.html.twig')
        );
    }
}
