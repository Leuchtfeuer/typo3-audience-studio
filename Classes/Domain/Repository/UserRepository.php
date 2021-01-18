<?php

declare(strict_types=1);

/*
 * This file is part of the "Audience Studio" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * Florian Wessels <f.wessels@Leuchtfeuer.com>, Leuchtfeuer Digital Marketing
 */

namespace Leuchtfeuer\Typo3AudienceStudio\Domain\Repository;

use Leuchtfeuer\Typo3AudienceStudio\Domain\Model\Segment;
use Leuchtfeuer\Typo3AudienceStudio\Domain\Model\User;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class UserRepository
{
    private const USER_TABLE = 'tx_audience_studio_user';

    public function addOrUpdate(User $user): void
    {
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable(self::USER_TABLE);
        $segmentsAsString = implode(
            ',',
            array_map(
                function (Segment $segment) {
                    return $segment->getId();
                },
                $user->getSegments()
            )
        );
        if (!$this->has($user)) {
            $connection->insert(
                self::USER_TABLE,
                [
                    'crdate' => time(),
                    'tstamp' => time(),
                    'as_ku_id' => $user->getId(),
                    'segments' => $segmentsAsString,
                ]
            );
        } else {
            $connection->update(
                self::USER_TABLE,
                [
                    'tstamp' => time(),
                    'as_ku_id' => $user->getId(),
                    'segments' => $segmentsAsString,
                ],
                [
                    'as_ku_id' => $user->getId(),
                ]
            );
        }
    }

    public function findById(string $userId): User
    {
        if (!$this->has(new User($userId))) {
            throw new \RuntimeException('No user found with this ID', 1603117793);
        }
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable(self::USER_TABLE);
        $segmentsAsString = $queryBuilder->select('segments')
            ->from(self::USER_TABLE)
            ->where(
                $queryBuilder->expr()->eq('as_ku_id', $queryBuilder->createNamedParameter($userId))
            )
            ->execute()
            ->fetchColumn();

        return new User(
            $userId,
            array_map(
                function (string $segmentId) {
                    return new Segment($segmentId, '');
                },
                explode(',', $segmentsAsString)
            )
        );
    }

    public function has(User $user): bool
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable(self::USER_TABLE);

        return $queryBuilder->count('as_ku_id')
            ->from(self::USER_TABLE)
            ->where(
                $queryBuilder->expr()->eq('as_ku_id', $queryBuilder->createNamedParameter($user->getId()))
            )
            ->execute()
            ->fetchColumn() > 0;
    }

    public function removeOlderThan(\DateTimeInterface $date): void
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable(self::USER_TABLE);
        $queryBuilder->delete(self::USER_TABLE)
            ->where(
                $queryBuilder->expr()->lt('tstamp', $queryBuilder->createNamedParameter($date->format('U')))
            )
            ->execute();
    }
}
