<?php

namespace GeorgRinger\News\ViewHelpers;

/**
     * This file is part of the TYPO3 CMS project.
     *
     * It is free software; you can redistribute it and/or modify it under
     * the terms of the GNU General Public License, either version 2
     * of the License, or any later version.
     *
     * For the full copyright and license information, please read the
     * LICENSE.txt file that was distributed with this source code.
     *
     * The TYPO3 project - inspiring people to share!
     */
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper;

/**
 * ViewHelper to render extended objects
 *
 * # Example: Basic example
 * <code>
 * <n:object newsItem="{newsItem}"
 *        as="out"
 *        className="Vendor\Myext\Domain\Model\CustomModel" >
 * {out.fo}
 * </n:object>
 * </code>
 * <output>
 * Property "fo" from model Vendor\Myext\Domain\Model\CustomModel
 * which extends the table tx_news_domain_model_news
 *
 * !!Be aware that this needs a mapping in TS!!
 *    config.tx_extbase.persistence.classes {
 *        Vendor\Myext\Domain\Model\CustomModel {
 *             mapping {
 *                tableName = tx_news_domain_model_news
 *            }
 *        }
 *    }
 * </output>
 *
 */
class ObjectViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{

    /**
     * @var bool
     */
    protected $escapeOutput = false;

    /**
     * Output different objects
     *
     * @param \GeorgRinger\News\Domain\Model\News $newsItem current newsitem
     * @param string $as output variable
     * @param string $className custom class which handles the new objects
     * @param string $extendedTable table which is extended
     * @return string output
     */
    public function render(
        \GeorgRinger\News\Domain\Model\News $newsItem,
        $as,
        $className,
        $extendedTable = 'tx_news_domain_model_news'
    ) {
        $rawRecord = $GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow('*', $extendedTable,
            'uid=' . (int)$newsItem->getUid());
        $rawRecord = $GLOBALS['TSFE']->sys_page->getRecordOverlay(
            $extendedTable,
            $rawRecord,
            $GLOBALS['TSFE']->sys_language_content,
            $GLOBALS['TSFE']->sys_language_contentOL);

        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        /* @var $dataMapper DataMapper */
        $dataMapper = $objectManager->get(DataMapper::class);

        $records = $dataMapper->map($className, [$rawRecord]);
        $record = array_shift($records);

        $this->templateVariableContainer->add($as, $record);
        $output = $this->renderChildren();
        $this->templateVariableContainer->remove($as);
        return $output;
    }
}
