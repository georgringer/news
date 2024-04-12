<?php

declare(strict_types=1);

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\ViewHelpers\MultiCategoryLink;

use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Exception;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;
use TYPO3Fluid\Fluid\Core\ViewHelper\ViewHelperInterface;

/**
 * ViewHelper to get additional params including add/remove categories from list
 *
 * # Example: Basic Example
 * # Description: Render the content of the VH as page title
 * <code>
 *    <f:link.page title="{category.item.title}" pageUid="{settings.listPid}"
 *       additionalParams="{n:multiCategoryLink.arguments(
 *          mode:'add',
 *          item:category.item.uid,
 *          list:overwriteDemand.categories)}">link
 *     </f:link.page>
 * </code>
 * <output>
 *    <title>TYPO3 is awesome</title>
 * </output>
 */
class ArgumentsViewHelper extends AbstractViewHelper implements ViewHelperInterface
{
    use CompileWithRenderStatic;

    /**
     * Initialize arguments
     */
    public function initializeArguments()
    {
        $this->registerArgument('mode', 'string', 'Mode, either "add" or "remove"', true);
        $this->registerArgument('list', 'string', 'Category list', false, '');
        $this->registerArgument('item', 'int', 'Category id', true);
        $this->registerArgument('additionalParams', 'array', 'Additional params', false, []);
        parent::initializeArguments();
    }

    /**
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     *
     * @return array
     */
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ): array {
        if ($arguments['mode'] !== 'add' && $arguments['mode'] !== 'remove') {
            throw new Exception('Mode must be either "add" or "remove', 1522293549);
        }

        $allArguments = (array)$arguments['additionalParams'];
        $categoryId = (int)$arguments['item'];

        // All IDs are numeric. Hence, split and type cast.
        $categoryList = GeneralUtility::intExplode(',', $arguments['list'], true);
        if ($arguments['mode'] === 'add') {
            $categoryList[] = $categoryId;
        } else {
            // array_diff has the advantage, that it does not care how often the searched value occurs.
            $categoryList = array_diff($categoryList, [$categoryId]);
        }
        // Ensure each ID to only occur once
        $categoryList = array_unique($categoryList);
        // Sort IDs, so lists are more uniform and less duplicate caches are generated
        sort($categoryList);

        if (!empty($categoryList)) {
            $categoryList = implode(',', $categoryList);
            $categoryArray = [
                'tx_news_pi1' => [
                    'overwriteDemand' => [
                        'categories' => $categoryList,
                    ],
                ],
            ];
            ArrayUtility::mergeRecursiveWithOverrule($allArguments, $categoryArray);
        }

        return $allArguments;
    }
}
