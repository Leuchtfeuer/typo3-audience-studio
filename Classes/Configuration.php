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

    private function __construct(
        string $key,
        string $secret,
        string $region,
        string $bucket
    ) {
        $this->key = $key;
        $this->secret = $secret;
        $this->region = $region;
        $this->bucket = $bucket;
    }

    public static function fromGlobals(): self
    {
        $config = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['audience_studio']['storageConfiguration'] ?? [];
        if (empty($config)) {
            throw new \RuntimeException('Configuration for audience studio is missing', 1602767075);
        }

        return new self(
            $config['key'],
            $config['secret'],
            $config['region'],
            $config['bucket']
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
    public static function getCookieName(): string
    {
        $cookieName = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['audience_studio']['cookieName'] ?? '';
        if (empty($cookieName)) {
            throw new \RuntimeException('Cookie name containing audience studio user id is missing', 1604563503);
        }

        return $cookieName;
    }
}
