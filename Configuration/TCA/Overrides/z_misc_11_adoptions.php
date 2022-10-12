<?php

defined('TYPO3') or die();

call_user_func(static function () {
    $versionInformation = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Information\Typo3Version::class);
    if ($versionInformation->getMajorVersion() < 12) {
        // todo add v11
    }
});
