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

namespace Leuchtfeuer\Typo3AudienceStudio\Domain\Model;

class User
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var Segment[]
     */
    private $segments;

    /**
     * User constructor.
     *
     * @param string $id
     * @param Segment[] $segments
     */
    public function __construct(string $id, array $segments = [])
    {
        $this->id = $id;
        $this->segments = $segments;
    }

    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return Segment[]
     */
    public function getSegments(): array
    {
        return $this->segments;
    }
}
