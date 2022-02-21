<?php

namespace GeorgRinger\News\Backend\FormDataProvider;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use GeorgRinger\News\Domain\Model\Dto\EmConfiguration;
use TYPO3\CMS\Backend\Form\FormDataProviderInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Fill the news records with default values
 */
class NewsRowInitializeNew implements FormDataProviderInterface
{

    /** @var  EmConfiguration */
    protected $emConfiguration;

    public function __construct()
    {
        $this->emConfiguration = GeneralUtility::makeInstance(EmConfiguration::class);
    }

    /**
     * @param array $result
     * @return array
     */
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

    /**
     * @param array $result
     * @return array
     */
    protected function fillDateField(array $result): array
    {
        if ($this->emConfiguration->getDateTimeRequired()) {
            $result['databaseRow']['datetime'] = $GLOBALS['EXEC_TIME'];
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

    /**
     * @param array $result
     * @return array
     */
    protected function setTagListingId(array $result): array
    {
        if (!isset($result['pageTsConfig']['tx_news.']['tagPid'])) {
            return $result;
        }
        $tagPid = (int)$result['pageTsConfig']['tx_news.']['tagPid'];
        if ($tagPid <= 0) {
            return $result;
        }

        $result['processedTca']['columns']['tags']['config']['fieldControl']['listModule']['options']['pid'] = $tagPid;

        return $result;
    }
}
