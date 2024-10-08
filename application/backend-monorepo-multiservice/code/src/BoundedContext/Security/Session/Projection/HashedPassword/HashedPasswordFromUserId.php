<?php

declare(strict_types=1);

namespace Galeas\Api\BoundedContext\Security\Session\Projection\HashedPassword;

use Doctrine\ODM\MongoDB\DocumentManager;
use Galeas\Api\CommonException\ProjectionCannotRead;

class HashedPasswordFromUserId
{
    private DocumentManager $projectionDocumentManager;

    public function __construct(DocumentManager $projectionDocumentManager)
    {
        $this->projectionDocumentManager = $projectionDocumentManager;
    }

    /**
     * @throws ProjectionCannotRead
     */
    public function hashedPasswordFromUserId(string $userId): ?string
    {
        try {
            $queryBuilder = $this->projectionDocumentManager
                ->createQueryBuilder(HashedPassword::class)
                ->field('id')
                ->equals($userId)
            ;

            $hashedPassword = $queryBuilder->getQuery()->getSingleResult();

            if ($hashedPassword instanceof HashedPassword) {
                return $hashedPassword->getHashedPassword();
            }

            if (null === $hashedPassword) {
                return null;
            }

            throw new \Exception();
        } catch (\Throwable $exception) {
            throw new ProjectionCannotRead($exception);
        }
    }
}
