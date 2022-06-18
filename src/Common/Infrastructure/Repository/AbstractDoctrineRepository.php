<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

abstract class AbstractDoctrineRepository
{
    protected const CLASS_NAME = null;

    protected ObjectRepository $objectRepository;

    /**
     * @psalm-suppress MixedArgument
     */
    public function __construct(
        protected EntityManagerInterface $entityManager
    ) {
        $this->objectRepository = $this->entityManager->getRepository(static::CLASS_NAME);
    }
}
