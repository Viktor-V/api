<?php

declare(strict_types=1);

namespace App\Admin\Application\UseCase\Command\Update;

use App\Admin\Domain\Entity\Embedded\Email;
use App\Admin\Domain\Entity\Embedded\Name;
use App\Admin\Domain\Entity\Embedded\PlainPassword;
use App\Common\Application\Command\CommandInterface;
use App\Common\Domain\Entity\Embedded\Uuid;

final class UpdateCommand implements CommandInterface
{
    public readonly Uuid $uuid;
    public readonly Email $email;
    public readonly Name $name;
    public readonly Email $changedBy;
    public readonly ?PlainPassword $password;

    public function __construct(
        string $uuid,
        string $email,
        string $firstname,
        string $lastname,
        string $changedBy,
        ?string $password
    ) {
        $this->uuid = new Uuid($uuid);
        $this->email = new Email($email);
        $this->name = new Name($firstname, $lastname);
        $this->changedBy = new Email($changedBy);
        $this->password = $password ? new PlainPassword($password) : null;
    }
}
