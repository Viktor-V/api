<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Platform\DataProvider;

use ApiPlatform\Core\DataProvider\PaginatorInterface;
use IteratorAggregate;
use Traversable;
use Generator;

class Paginator implements PaginatorInterface, IteratorAggregate
{
    public function __construct(
        private Generator $items,
        private int $currentPage,
        private int $itemsPerPage
    ) {
    }

    public function getIterator(): Traversable
    {
        return $this->items;
    }

    public function count(): int
    {
        return (int) $this->getItemsPerPage();
    }

    public function getLastPage(): float
    {
        return ceil($this->getTotalItems() / $this->getItemsPerPage()) ?: 1.0;
    }

    public function getTotalItems(): float
    {
        return (float) $this->items->getReturn();
    }

    public function getCurrentPage(): float
    {
        return $this->currentPage;
    }

    public function getItemsPerPage(): float
    {
        return $this->itemsPerPage;
    }
}
