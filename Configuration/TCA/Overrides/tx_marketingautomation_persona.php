<?php
declare(strict_types = 1);
defined('TYPO3_MODE') || die();

call_user_func(
    static function () {

        $tempColumns = [
            'tx_audience_studio_segments' => [
                'label' => 'LLL:EXT:audience_studio/Resources/Private/Language/locallang_tca.xlf:tx_marketingautomation_persona.segments',
                'config' => [
                    'type' => 'select',
                    'renderType' => 'selectMultipleSideBySide',
                    'foreign_table' => 'tx_audience_studio_segment',
                    'foreign_table_where' => 'ORDER BY title',
                    'MM' => 'tx_audience_studio_segment_persona_mm',
                    'MM_opposite_field' => 'items',
                    'MM_match_fields' => [
                        'tablenames' => 'tx_marketingautomation_persona',
                        'fieldname' => 'tx_audience_studio_segments',
                    ],
                    'size' => 10,
                    'autoSizeMax' => 30,
                    'enableMultiSelectFilterTextfield' => true,
                ],
            ],
        ];

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tx_marketingautomation_persona', $tempColumns);
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
            'tx_marketingautomation_persona',
            '--div--;LLL:EXT:audience_studio/Resources/Private/Language/locallang_tca.xlf:audience_studio,tx_audience_studio_segments',
            '',
            'before:--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended'
        );

    }
);
