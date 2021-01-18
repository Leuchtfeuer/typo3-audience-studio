<?php

/*
 * This file is part of the "Audience Studio" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * Florian Wessels <f.wessels@Leuchtfeuer.com>, Leuchtfeuer Digital Marketing
 */

namespace Leuchtfeuer\Typo3AudienceStudio\Hooks;

use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class LocalStorageHook
{
    public function addCookieScript(): void
    {
        $localStorageKey = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['audience_studio']['localStorageKey'] ?? '';

        if (!empty($localStorageKey)) {
            GeneralUtility::makeInstance(PageRenderer::class)
                ->addJsFooterInlineCode(
                    'AudienceStudio',
                    $this->buildJavaScript($localStorageKey)
                );
        }
    }

    protected function buildJavaScript(string $localStorageKey): string
    {
        $cookieName = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['audience_studio']['cookieName'];

        return <<<JS
(function() {
    if (window.localStorage) {
        const KUID = localStorage.getItem('{$localStorageKey}');
        if (KUID !== null) {
            let date = new Date();
            date.setTime(date.getTime() + (30 * 24 * 60 * 60 * 1000));
            document.cookie = '{$cookieName}=' + KUID + ';expires=' + date.toUTCString() + ';path=/';
        }
    }
})();
JS;
    }
}
