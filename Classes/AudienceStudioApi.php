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

use Aws\S3\S3Client;
use Aws\S3\StreamWrapper;
use Keboola\Csv\CsvReader;
use Leuchtfeuer\Typo3AudienceStudio\Domain\Model\Segment;
use Leuchtfeuer\Typo3AudienceStudio\Domain\Model\User;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class AudienceStudioApi
{
    private const STREAM_PROTOCOL = 's3as';
    private const SEGMENTS_FOLDER = '/krux-data/exports/audience-segments/';
    private const USERS_FOLDERS = '/krux-data/exports/audience-segment-map/';
    private const USER_SEGMENT_SEPARATOR = '^';
    /**
     * @var S3Client
     */
    private $client;

    /**
     * @var ImportProgress
     */
    private $importProgress;

    /**
     * @var Configuration
     */
    private $configuration;

    public function __construct(
        ImportProgress $importProgress = null,
        Configuration $configuration = null
    ) {
        $this->importProgress = $importProgress ?? new ImportProgress();
        $this->configuration = $configuration ?? Configuration::fromGlobals();
    }

    public function fetchSegments(): \Generator
    {
        $this->initStreamWrapper();
        $segmentsDir = $this->getStreamWrapperPath(self::SEGMENTS_FOLDER);

        $result = GeneralUtility::getFilesInDir($segmentsDir);
        $tempFile = GeneralUtility::tempnam('as-segments-csv');
        // We need to copy the file because the CsvReader cannot operate on the file handle of the stream wrapper directly
        copy($segmentsDir . current($result), $tempFile);

        $csvFile = new CsvReader(
            $tempFile,
            ',',
            '"',
            '',
            1
        );
        foreach ($csvFile as $row) {
            [,,, $name, $segmentId] = $row;
            yield new Segment($segmentId, $name);
        }

        GeneralUtility::unlink_tempfile($tempFile);
    }

    public function fetchUsers(): \Generator
    {
        $this->initStreamWrapper();
        $usersFoldersPath = $this->getStreamWrapperPath(self::USERS_FOLDERS);

        $usersFolders = scandir($usersFoldersPath, SCANDIR_SORT_DESCENDING);
        foreach ($usersFolders as $usersFolder) {
            if (!is_dir($usersFoldersPath . $usersFolder)) {
                continue;
            }
            $usersFiles = scandir($usersFoldersPath . $usersFolder, SCANDIR_SORT_ASCENDING);
            $this->importProgress->maxSteps(count($usersFiles) - 1);
            foreach ($usersFiles as $usersFileName) {
                $usersFile = new \SplFileInfo($usersFoldersPath . $usersFolder . '/' . $usersFileName);
                if ($usersFile->getExtension() !== 'gz') {
                    continue;
                }
                $tempFile = GeneralUtility::tempnam('as-users-csv');
                $gzipUsersFile = $usersFile->getPath() . '/' . $usersFile->getFilename();
                // We need to copy the file because the CsvReader cannot operate on the file handle of the stream wrapper directly
                copy($gzipUsersFile, $tempFile);
                $gzipFilePointer = gzopen($tempFile, 'r');

                $csvFile = new CsvReader(
                    $gzipFilePointer,
                    ',',
                    '"',
                    '',
                    1
                );
                $currentUser = '';
                $segments = [];
                foreach ($csvFile as $row) {
                    [$userId,, $segmentId] = str_getcsv(current($row), self::USER_SEGMENT_SEPARATOR);
                    // Initialize first user
                    $currentUser = $currentUser ?: $userId;
                    if ($currentUser !== $userId) {
                        // new user in the row, so return the current one with its segments
                        yield new User($currentUser, $segments);
                        // and re-initialize user id and segments
                        $segments = [];
                        $currentUser = $userId;
                    }
                    $segments[] = new Segment($segmentId, '');
                }
                GeneralUtility::unlink_tempfile($tempFile);
                $this->importProgress->proceed();
            }
            // Only use the latest export, ignore older ones
            break;
        }
    }

    private function initStreamWrapper(): void
    {
        if ($this->client) {
            return;
        }

        $this->client = new S3Client([
            'region' => $this->configuration->getRegion(),
            'version' => '2006-03-01',
            'credentials' => [
                'key' => $this->configuration->getKey(),
                'secret' => $this->configuration->getSecret(),
            ],
        ]);
        StreamWrapper::register($this->client, self::STREAM_PROTOCOL);
    }

    private function getStreamWrapperPath(string $fileOrFolder): string
    {
        return self::STREAM_PROTOCOL . '://' . $this->configuration->getBucket() . $fileOrFolder;
    }
}
