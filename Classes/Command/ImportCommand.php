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

namespace Leuchtfeuer\Typo3AudienceStudio\Command;

use Leuchtfeuer\Typo3AudienceStudio\AudienceStudioApi;
use Leuchtfeuer\Typo3AudienceStudio\Domain\Repository\SegmentRepository;
use Leuchtfeuer\Typo3AudienceStudio\Domain\Repository\UserRepository;
use Leuchtfeuer\Typo3AudienceStudio\ImportProgress;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportCommand extends Command
{
    protected function configure()
    {
        $this->setDescription('Import users and segments from Audience Studio');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $currentTime = new \DateTimeImmutable();
        $importProgress = new ImportProgress();
        $api = new AudienceStudioApi($importProgress);

        $io->comment('Importing segments');
        $segmentRepository = new SegmentRepository();
        foreach ($api->fetchSegments() as $segment) {
            $segmentRepository->addOrUpdate($segment);
        }
        $io->success('done');

        $io->comment('Importing users');
        $userRepository = new UserRepository();
        $currentStep = 0;
        $progressBar = $io->createProgressBar();
        foreach ($api->fetchUsers() as $user) {
            if ($currentStep === 0 && $importProgress->hasStarted()) {
                $progressBar->start($importProgress->getSteps());
            }
            if ($importProgress->getCurrentStep() > $currentStep) {
                $progressBar->advance();
                $currentStep = $importProgress->getCurrentStep();
            }
            $userRepository->addOrUpdate($user);
        }
        $userRepository->removeOlderThan($currentTime);
        $progressBar->finish();
        $io->writeln('');

        $io->success('done');

        return 0;
    }
}
