<?php
declare(strict_types=1);
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
