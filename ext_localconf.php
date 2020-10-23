<?php
defined('TYPO3_MODE') || die;

call_user_func(static function () {

    $marketingDispatcher = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\Bitmotion\MarketingAutomation\Dispatcher\Dispatcher::class);
    $marketingDispatcher->addSubscriber(\Leuchtfeuer\Typo3AudienceStudio\MarketingAutomation\AudienceStudioSubscriber::class);

});
