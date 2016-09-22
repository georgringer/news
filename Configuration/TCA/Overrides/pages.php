<?php
defined('TYPO3_MODE') or die();

// Override news icon
$GLOBALS['TCA']['pages']['columns']['module']['config']['items'][] = [
    0 => 'LLL:EXT:news/Resources/Private/Language/locallang_be.xlf:news-folder',
    1 => 'news',
    2 => 'apps-pagetree-folder-contains-news'
];
