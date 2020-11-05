<?php
declare(strict_types=1);
namespace Leuchtfeuer\Typo3AudienceStudio;

class Configuration
{
    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $secret;

    /**
     * @var string
     */
    private $region;

    /**
     * @var string
     */
    private $bucket;

    /**
     * @var string
     */
    private $cookieName;

    private function __construct(
        string $key,
        string $secret,
        string $region,
        string $bucket,
        string $cookieName
    ) {
        $this->key = $key;
        $this->secret = $secret;
        $this->region = $region;
        $this->bucket = $bucket;
        $this->cookieName = $cookieName;
    }

    public static function fromGlobals(): self
    {
        $config = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['audience_studio'] ?? [];
        if (empty($config)) {
            throw new \RuntimeException('Configuration for audience studio is missing', 1602767075);
        }
        if (empty($config['storageConfiguration'])) {
            throw new \RuntimeException('S3 storage configuration for audience studio is missing', 1604577970);
        }

        return new self(
            $config['storageConfiguration']['key'],
            $config['storageConfiguration']['secret'],
            $config['storageConfiguration']['region'],
            $config['storageConfiguration']['bucket'],
            $config['cookieName'] ?? 'KUID'
        );
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getSecret(): string
    {
        return $this->secret;
    }

    /**
     * @return string
     */
    public function getRegion(): string
    {
        return $this->region;
    }

    /**
     * @return string
     */
    public function getBucket(): string
    {
        return $this->bucket;
    }

    /**
     * @return string
     */
    public function getCookieName(): string
    {
        return $this->cookieName;
    }
}
