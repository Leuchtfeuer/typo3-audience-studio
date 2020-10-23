<?php
declare(strict_types = 1);
namespace Leuchtfeuer\Typo3AudienceStudio\Domain\Repository;

use Leuchtfeuer\Typo3AudienceStudio\Domain\Model\Segment;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class SegmentRepository
{
    private const SEGMENT_TABLE = 'tx_audience_studio_segment';

    public function addOrUpdate(Segment $segment): void
    {
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable(self::SEGMENT_TABLE);
        if (!$this->has($segment)) {
            $connection->insert(
                self::SEGMENT_TABLE,
                [
                    'crdate' => time(),
                    'tstamp' => time(),
                    'title' => $segment->getTitle(),
                    'as_segment_id' => $segment->getId(),
                ]
            );
        } else {
            $connection->update(
                self::SEGMENT_TABLE,
                [
                    'tstamp' => time(),
                    'title' => $segment->getTitle(),
                    'as_segment_id' => $segment->getId(),
                ],
                [
                    'as_segment_id' => $segment->getId(),
                ]
            );
        }
    }

    public function has(Segment $segment): bool
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable(self::SEGMENT_TABLE);

        return $queryBuilder->count('uid')
            ->from(self::SEGMENT_TABLE)
            ->where(
                $queryBuilder->expr()->eq('as_segment_id', $queryBuilder->createNamedParameter($segment->getId()))
            )
            ->execute()
            ->fetchColumn() > 0;
    }
}
