<?php

defined('TYPO3') or die;

call_user_func(static function () {
    $ll = 'LLL:EXT:news/Resources/Private/Language/locallang_db.xlf:';
    $configuration = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\GeorgRinger\News\Domain\Model\Dto\EmConfiguration::class);
    $versionInformation = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Information\Typo3Version::class);
    if ($versionInformation->getMajorVersion() > 11) {
        foreach (['news', 'link', 'tag'] as $tableSuffix) {
            // remove cruser_id
            unset($GLOBALS['TCA']['tx_news_domain_model_' . $tableSuffix]['ctrl']['cruser_id']);
            // set datetime for tstamp/crdate
            foreach (['tstamp', 'crdate'] as $dateField) {
                $GLOBALS['TCA']['tx_news_domain_model_' . $tableSuffix]['columns'][$dateField]['config'] = [
                    'type' => 'datetime',
                ];
            }
        }
        // set datetime for various date fields
        foreach (['starttime', 'endtime', 'archive', 'datetime'] as $dateField) {
            $GLOBALS['TCA']['tx_news_domain_model_news']['columns'][$dateField]['config'] = [
                'type' => 'datetime',
            ];
        }
        $GLOBALS['TCA']['tx_news_domain_model_news']['columns']['archive']['config']['format'] = $configuration->getArchiveDate();
        $GLOBALS['TCA']['tx_news_domain_model_news']['columns']['datetime']['config']['required'] = $configuration->getDateTimeRequired();

        // link fields
        $GLOBALS['TCA']['tx_news_domain_model_news']['columns']['internalurl']['config'] = [
            'type' => 'link',
            'required' => true,
        ];
        $GLOBALS['TCA']['tx_news_domain_model_link']['columns']['uri']['config'] = [
            'type' => 'link',
            'placeholder' => $ll . 'tx_news_domain_model_link.uri.placeholder',
            'required' => 'true',
            'softref' => 'typolink',
            'behaviour' => [
                'allowLanguageSynchronization' => true,
            ],
        ];

        // required fields
        foreach (['tx_news_domain_model_link' => ['uri'], 'tx_news_domain_model_news' => ['title', 'externalurl'], 'tx_news_domain_model_tag' => ['title']] as $table => $fields) {
            foreach ($fields as $field) {
                $GLOBALS['TCA'][$table]['columns'][$field]['config']['required'] = true;
                $GLOBALS['TCA'][$table]['columns'][$field]['config']['eval'] = str_replace('required', '', $GLOBALS['TCA'][$table]['columns'][$field]['config']['eval'] ?? '');
            }
        }
    }
});
