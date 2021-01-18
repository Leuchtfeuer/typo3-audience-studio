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

class Segment
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var \DateTimeInterface
     */
    private $lastComputeDate;

    /**
     * @var string
     */
    private $longId;

    /**
     * @var string
     */
    private $category;

    /**
     * @var string
     */
    private $subCategory;

    public function __construct(
        string $id,
        string $title,
        \DateTimeInterface $lastComputeDate = null,
        string $longId = '',
        string $category = '',
        string $subCategory = ''
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->lastComputeDate = $lastComputeDate;
        $this->longId = $longId;
        $this->category = $category;
        $this->subCategory = $subCategory;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    public function toString(): string
    {
        return $this->id;
    }
}
