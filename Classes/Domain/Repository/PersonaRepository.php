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

use Bitmotion\MarketingAutomation\Persona\Persona;
use Doctrine\DBAL\Connection;
use Leuchtfeuer\Typo3AudienceStudio\Domain\Model\Segment;
use Leuchtfeuer\Typo3AudienceStudio\Domain\Model\User;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class PersonaRepository
{
    public function findOneByUser(User $user): Persona
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_marketingautomation_persona');
        $expressionBuilder = $queryBuilder->expr();

        $segments = array_map(
            function (Segment $segment) {
                return $segment->getId();
            },
            $user->getSegments()
        );

        $personaUid = $queryBuilder->select('persona.uid')
            ->from('tx_marketingautomation_persona', 'persona')
            ->innerJoin(
                'persona',
                'tx_audience_studio_segment_persona_mm',
                'segment_mm',
                $expressionBuilder->eq('persona.uid', $queryBuilder->quoteIdentifier('segment_mm.uid_foreign'))
            )
            ->innerJoin(
                'segment_mm',
                'tx_audience_studio_segment',
                'segment',
                $expressionBuilder->eq('segment_mm.uid_local', $queryBuilder->quoteIdentifier('segment.uid'))
            )
            ->where(
                $expressionBuilder->in(
                    'segment.as_segment_id',
                    $queryBuilder->createNamedParameter($segments, Connection::PARAM_STR_ARRAY)
                )
            )
            ->orderBy('persona.sorting')
            ->setMaxResults(1)
            ->execute()
            ->fetchColumn();

        return new Persona((int)($personaUid ?: 0), 0);
    }
}
