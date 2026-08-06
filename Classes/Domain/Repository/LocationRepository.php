<?php

declare(strict_types=1);

namespace Maispace\MaiLocations\Domain\Repository;

use Maispace\MaiLocations\Domain\Model\Location;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

class LocationRepository extends Repository
{
    protected $defaultOrderings = [
        'sorting' => QueryInterface::ORDER_ASCENDING,
    ];

    public function findFromPages(array $pageUids): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setStoragePageIds($pageUids);

        return $query->execute();
    }

    public function createQueryBuilderForPagination(array $pageUids = []): QueryBuilder
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_mailocations_location');

        $queryBuilder
            ->select('*')
            ->from('tx_mailocations_location')
            ->orderBy('sorting', 'ASC');

        if ($pageUids !== []) {
            $queryBuilder
                ->andWhere(
                    $queryBuilder->expr()->in(
                        'pid',
                        $queryBuilder->createNamedParameter($pageUids, Connection::PARAM_INT_ARRAY)
                    )
                );
        }

        return $queryBuilder;
    }
}
