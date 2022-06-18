<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Bus\Query;

use App\Common\Application\Query\QueryBusInterface;
use App\Common\Application\Query\QueryInterface;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

final class QueryBus implements QueryBusInterface
{
    use HandleTrait {
        handle as handleQuery;
    }

    public function __construct(
        MessageBusInterface $messageBus
    ) {
        $this->messageBus = $messageBus;
    }

    public function handle(QueryInterface $query): mixed
    {
        return $this->handleQuery($query);
    }
}
