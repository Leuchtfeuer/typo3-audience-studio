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
