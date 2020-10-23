<?php
declare(strict_types=1);
namespace Leuchtfeuer\Typo3AudienceStudio;

class ImportProgress
{
    private $steps;
    private $currentStep = 0;

    public function maxSteps(int $steps): void
    {
        $this->steps = $steps;
    }

    public function proceed(): void
    {
        $this->currentStep++;
    }

    public function hasStarted(): bool
    {
        return $this->steps !== null;
    }

    public function getSteps(): int
    {
        return $this->steps;
    }

    /**
     * @return int
     */
    public function getCurrentStep(): int
    {
        return $this->currentStep;
    }
}
