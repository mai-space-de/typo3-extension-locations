<?php

declare(strict_types=1);

namespace Maispace\MaiLocations\Indexer;

use Maispace\MaiLocations\Domain\Model\Location;
use Maispace\MaiSearch\Domain\Dto\SearchResult;
use Maispace\MaiSearch\Domain\Model\IndexingContext;
use Maispace\MaiSearch\Domain\Service\SearchResultFormatterInterface;
use Maispace\MaiSearch\Indexer\AbstractIndexer;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper;

class LocationIndexer extends AbstractIndexer implements SearchResultFormatterInterface
{
    private const TABLE_NAME = 'tx_mailocations_location';

    public function getType(): string
    {
        return 'location';
    }

    public function supports(string $table): bool
    {
        return $table === self::TABLE_NAME;
    }

    public function indexAll(IndexingContext $context): void
    {
        foreach ($this->getRecordsForIndexing($context) as $record) {
            $this->indexRecord($record, $context);
        }
    }

    public function indexRecord(object $record, IndexingContext $context): void
    {
        if (!$record instanceof Location) {
            return;
        }

        $document = $this->createDocument(
            type: $this->getType(),
            uid: (int) $record->getUid(),
            title: $record->getName(),
            content: $this->buildContent($record),
            url: $this->buildUrl($record),
            crdate: new \DateTime(),
            boost: $this->getBoost($this->getType()),
        );

        $this->sendDocument($document, $context->languageCode);
    }

    public function removeRecord(int $uid, string $table): void
    {
        if ($table !== self::TABLE_NAME) {
            return;
        }

        $this->removeDocument($uid);
    }

    protected function buildContent(object $record): string
    {
        if (!$record instanceof Location) {
            return '';
        }

        $parts = array_filter([
            $record->getStreet(),
            $record->getZip() . ' ' . $record->getCity(),
            $record->getCountry(),
            $record->getPhone(),
            $record->getEmail(),
            strip_tags($record->getDescription()),
        ]);

        return implode("\n", $parts);
    }

    protected function buildUrl(object $record): string
    {
        if (!$record instanceof Location) {
            return '';
        }

        try {
            $site = GeneralUtility::makeInstance(SiteFinder::class)->getSiteByPageId((int) $record->getPid());
            $uri = $site->getRouter()->generateUri(
                (int) $record->getPid(),
                ['uid' => $record->getUid()],
            );

            return (string) $uri;
        } catch (\Exception) {
            return '';
        }
    }

    protected function getRecordsForIndexing(IndexingContext $context): iterable
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable(self::TABLE_NAME);

        $queryBuilder->select('*')->from(self::TABLE_NAME);

        if ($context->languageUid !== null) {
            $queryBuilder->where(
                $queryBuilder->expr()->eq(
                    'sys_language_uid',
                    $queryBuilder->createNamedParameter($context->languageUid, Connection::PARAM_INT),
                ),
            );
        }

        $rows = $queryBuilder
            ->setMaxResults($context->batchSize)
            ->setFirstResult($context->offset)
            ->executeQuery()
            ->fetchAllAssociative();

        if ($rows === []) {
            return [];
        }

        $dataMapper = GeneralUtility::makeInstance(DataMapper::class);

        return $dataMapper->map(Location::class, $rows);
    }

    public function formatResult(array $solrDoc): SearchResult
    {
        return new SearchResult(
            type: $this->getType(),
            title: $solrDoc['title_s'] ?? '',
            snippet: $this->buildSnippet($solrDoc),
            url: $solrDoc['url_s'] ?? '',
            icon: $this->getIcon($this->getType()),
            date: null,
            score: (float) ($solrDoc['score'] ?? 0.0),
        );
    }

    public function getIcon(string $type): string
    {
        return 'content-map-marker';
    }

    private function buildSnippet(array $solrDoc): string
    {
        $content = $solrDoc['content_t'] ?? '';

        return mb_substr(strip_tags($content), 0, 200);
    }
}
