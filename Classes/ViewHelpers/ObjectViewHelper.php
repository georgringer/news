<?php

namespace GeorgRinger\News\ViewHelpers;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3\CMS\Fluid\Core\ViewHelper\Facets\CompilableInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

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
class ObjectViewHelper extends AbstractViewHelper implements CompilableInterface
{
    use CompileWithRenderStatic;

    /**
     * @var bool
     */
    protected $escapeOutput = false;

    /**
     */
    public function initializeArguments()
    {
        $this->registerArgument('newsItem', News::class, 'Current newsitem', true);
        $this->registerArgument('as', 'string', 'Output variable', true);
        $this->registerArgument('className', 'string', 'Custom class which handles the news objects', true);
        $this->registerArgument('extendedTable', 'string', 'Table which is extended', false, 'tx_news_domain_model_news');
    }

    /**
     * Output different objects
     *
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return string
     */
    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext)
    {
        $newsItem = $arguments['newsItem'];
        $as = $arguments['as'];
        $className = $arguments['className'];
        $extendedTable = $arguments['extendedTable'];
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

        // @TODO: getTemplateVariableContainer() deprecated on 8.0+, use getVariableProvider() after raising minimum
        $renderingContext->getTemplateVariableContainer()->templateVariableContainer->add($as, $record);
        $output = $renderChildrenClosure();
        $renderingContext->getTemplateVariableContainer()->templateVariableContainer->remove($as);
        return $output;
    }
}
