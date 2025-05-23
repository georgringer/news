<?php

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Backend\FormDataProvider;

use GeorgRinger\News\Domain\Model\Dto\EmConfiguration;
use TYPO3\CMS\Backend\Form\FormDataProviderInterface;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Fill the news records with default values
 */
class NewsRowInitializeNew implements FormDataProviderInterface
{
    protected EmConfiguration $emConfiguration;

    public function __construct()
    {
        $this->emConfiguration = GeneralUtility::makeInstance(EmConfiguration::class);
    }

    public function addData(array $result): array
    {
        if ($result['tableName'] !== 'tx_news_domain_model_news') {
            return $result;
        }

        $result = $this->setTagListingId($result);

        if ($result['command'] === 'new') {
            $result = $this->fillDateField($result);
        }

        return $result;
    }

    protected function fillDateField(array $result): array
    {
        if ($this->emConfiguration->getDateTimeRequired()) {
            $result['databaseRow']['datetime'] = GeneralUtility::makeInstance(Context::class)->getPropertyFromAspect('date', 'timestamp');
        }

        if (isset($result['pageTsConfig']['tx_news.']['predefine.'])
            && is_array($result['pageTsConfig']['tx_news.']['predefine.'])
        ) {
            if (isset($result['pageTsConfig']['tx_news.']['predefine.']['author']) && (int)$result['pageTsConfig']['tx_news.']['predefine.']['author'] === 1) {
                $result['databaseRow']['author'] = $GLOBALS['BE_USER']->user['realName'];
                $result['databaseRow']['author_email'] = $GLOBALS['BE_USER']->user['email'];
            }

            if (isset($result['pageTsConfig']['tx_news.']['predefine.']['archive'])) {
                $calculatedTime = strtotime($result['pageTsConfig']['tx_news.']['predefine.']['archive']);

                if ($calculatedTime !== false) {
                    $result['databaseRow']['archive'] = $calculatedTime;
                }
            }
        }

        return $result;
    }

    protected function setTagListingId(array $result): array
    {
        $tagPid = (int)($result['pageTsConfig']['tx_news.']['tagPid'] ?? 0);
        if ($tagPid <= 0) {
            return $result;
        }

        $result['processedTca']['columns']['tags']['config']['fieldControl']['listModule']['options']['pid'] = $tagPid;

        return $result;
    }
}
