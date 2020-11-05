<?php

$EM_CONF['audience_studio'] = [
    'title' => 'Marketing Automation - Audience Studio Adapter',
    'description' => 'Add-on TYPO3 extension that enhances the "EXT:marketing_automation" TYPO3 extension by connecting it to Salseforce Audience Studio',
    'category' => 'fe',
    'state' => 'stable',
    'uploadfolder' => 0,
    'createDirs' => '',
    'author' => 'Florian Wessels, Helmut Hummel',
    'author_company' => 'Leuchtfeuer Digital Marketing',
    'author_email' => 'dev@Leuchtfeuer.com',
    'clearCacheOnLoad' => 0,
    'version' => '1.1.1',
    'constraints' => [
        'depends' => [
            'typo3' => '9.5.2-10.4.99',
            'marketing_automation' => '1.2.3-1.2.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];

