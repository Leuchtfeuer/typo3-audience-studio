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

namespace Leuchtfeuer\Typo3AudienceStudio\MarketingAutomation;

use Bitmotion\MarketingAutomation\Dispatcher\SubscriberInterface;
use Bitmotion\MarketingAutomation\Persona\Persona;
use Leuchtfeuer\Typo3AudienceStudio\Configuration;
use Leuchtfeuer\Typo3AudienceStudio\Domain\Model\User;
use Leuchtfeuer\Typo3AudienceStudio\Domain\Repository\PersonaRepository;
use Leuchtfeuer\Typo3AudienceStudio\Domain\Repository\UserRepository;

class AudienceStudioSubscriber implements SubscriberInterface
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var PersonaRepository
     */
    private $personaRepository;

    /**
     * @var string
     */
    private $audienceStudioUserId;

    public function __construct(
        UserRepository $userRepository = null,
        PersonaRepository $personaRepository = null,
        Configuration $configuration = null
    ) {
        $this->userRepository = $userRepository ?? new UserRepository();
        $this->personaRepository = $personaRepository ?? new PersonaRepository();
        $configuration = $configuration ?? Configuration::fromGlobals();
        $this->audienceStudioUserId = $_COOKIE[$configuration->getCookieName()] ?? '';
    }

    public function needsUpdate(Persona $currentPersona, Persona $newPersona): bool
    {
        return empty($currentPersona->getId())
            && !empty($this->audienceStudioUserId)
            && $this->userRepository->has(new User($this->audienceStudioUserId));
    }

    public function update(Persona $persona): Persona
    {
        $user = $this->userRepository->findById($this->audienceStudioUserId);
        $newPersona = $this->personaRepository->findOneByUser($user);

        return $persona->withId($newPersona->getId());
    }
}
