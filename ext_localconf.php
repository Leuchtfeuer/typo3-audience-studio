<?php
defined('TYPO3_MODE') || die;

call_user_func(static function () {
    // Load libraries when TYPO3 is not in composer mode
    // TODO: Use environment class when dropping TYPO3 v9 support.
    if (!defined('TYPO3_COMPOSER_MODE') || !TYPO3_COMPOSER_MODE) {
        require \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('audience_studio') . 'Libraries/vendor/autoload.php';
    }

    \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\Bitmotion\MarketingAutomation\Dispatcher\Dispatcher::class)
        ->addSubscriber(\Leuchtfeuer\Typo3AudienceStudio\MarketingAutomation\AudienceStudioSubscriber::class);
});
