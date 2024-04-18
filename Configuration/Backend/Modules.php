<?php

declare(strict_types=1);

use GeorgRinger\News\Controller\AdministrationController;
use GeorgRinger\News\Domain\Model\Dto\EmConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$configuration = GeneralUtility::makeInstance(
    EmConfiguration::class
);

if ($configuration->getShowAdministrationModule()) {
    return [
        'web_newsAdministration' => [
            'parent' => 'web',
            'position' => ['after' => '*'],
            'access' => 'user,group',
            'path' => '/module/web/NewsAdministration/',
            'icon' => 'EXT:news/Resources/Public/Icons/module_administration.svg',
            'labels' => 'LLL:EXT:news/Resources/Private/Language/locallang_modadministration.xlf',
            'extensionName' => 'News',
            'controllerActions' => [
                AdministrationController::class => [
                    'index',
                    'newNews',
                    'newCategory',
                    'newTag',
                    'newsPidListing',
                    'donate',
                ],
            ],
        ],
    ];
}

return [];
