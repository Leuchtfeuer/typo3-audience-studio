<?php
declare(strict_types = 1);
defined('TYPO3_MODE') || die();

return [
    'ctrl' => [
        'title' => 'LLL:EXT:audience_studio/Resources/Private/Language/locallang_tca.xlf:tx_audience_studio_segment',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'delete' => 'deleted',
        'searchFields' => 'title',
        'typeicon_classes' => [
            'default' => 'mimetypes-x-tx_marketingautomation_persona',
        ],
        'hideTable' => true,
        'rootLevel' => true,
        'security' => [
            'ignoreRootLevelRestriction' => true,
        ],
    ],
    'interface' => [
        'showRecordFieldList' => 'title',
    ],
    'types' => [
        '1' => [
            'showitem' => '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,'
                . 'title,'
                . '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
            ',
        ],
    ],
    'palettes' => [],
    'columns' => [
        'title' => [
            'label' => 'LLL:EXT:marketing_automation/Resources/Private/Language/locallang_tca.xlf:tx_marketingautomation_persona.title',
            'config' => [
                'type' => 'input',
                'width' => 200,
                'eval' => 'trim,required',
            ],
        ],
        'items' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
    ],
];
