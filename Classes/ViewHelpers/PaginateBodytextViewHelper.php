<?php

namespace GeorgRinger\News\ViewHelpers;

use GeorgRinger\News\Domain\Model\News;
/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Paginate the bodytext which is very useful for longer texts or to increase
 * traffic.
 *
 * Example
 * ----------
 * <n:paginateBodytext object="{newsItem}"
 *        as="bodytext" currentPage="{currentPage}">
 *    <div class="articleBodyText">
 *        <f:format.html>{bodytext}</f:format.html>
 *    </div>
 *    <f:if condition="{pagination.numberOfPages} > 1">
 *        <div class="articlePagination">
 *            <span>Seite</span>
 *            <ul>
 *                <f:for each="{pagination.pages}" as="page">
 *                    <f:if condition="{page.isCurrent}">
 *                        <f:then>
 *                            <li class="current">
 *                                {page.number}
 *                            </li>
 *                        </f:then>
 *                        <f:else>
 *                            <li>
 *                                <f:if condition="{page.number} == 1">
 *                                    <f:then>
 *                                        <f:link.action action="detail" arguments="{news: newsItem}">
 *                                            {page.number}
 *                                        </f:link.action>
 *                                    </f:then>
 *                                    <f:else>
 *                                        <f:link.action action="detail" arguments="{news: newsItem, currentPage: page.number}">
 *                                            {page.number}
 *                                        </f:link.action>
 *                                    </f:else>
 *                                </f:if>
 *                            </li>
 *                        </f:else>
 *                    </f:if>
 *                </f:for>
 *            </ul>
 *        </div>
 *    </f:if>
 * </n:paginateBodytext>
 */
class PaginateBodytextViewHelper extends AbstractViewHelper
{
    /**
     * @var bool
     */
    protected $escapeOutput = false;

    /**
     * Initialize arguments
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('object', News::class, 'news item', true);
        $this->registerArgument('as', 'string', 'as', true);
        $this->registerArgument('currentPage', 'int', 'current page', true);
        $this->registerArgument('token', 'string', 'token', '###more###');
    }

    /**
     * Render everything
     *
     * @return string
     */
    public function render(): string
    {
        $as = $this->arguments['as'];
        $currentPage = $this->arguments['currentPage'];

        $parts = GeneralUtility::trimExplode($this->arguments['token'], $this->arguments['object']->getBodytext(), true);
        $numberOfPages = count($parts);

        if ($numberOfPages === 1) {
            $result = $parts[0];
        } else {
            $currentPage = (int)$currentPage;
            if ($currentPage < 1) {
                $currentPage = 1;
            } elseif ($currentPage > $numberOfPages) {
                $currentPage = $numberOfPages;
            }

            $tagsToOpen = [];
            $tagsToClose = [];

            for ($j = 0; $j < $currentPage; $j++) {
                $chunk = $parts[$j];

                while (($chunk = mb_strstr($chunk, '<'))) {
                    $tag = $this->extractTag($chunk);
                    $tagStrLen = mb_strlen($tag);

                    if ($this->isOpeningTag($tag)) {
                        if ($j < $currentPage - 1) {
                            $tagsToOpen[] = $tag;
                        }
                        $tagsToClose[] = $tag;
                    } elseif ($this->isClosingTag($tag)) {
                        if ($j < $currentPage - 1) {
                            array_pop($tagsToOpen);
                        } elseif (mb_strpos($parts[$j], $chunk) === 0) {
                            $parts[$j] = mb_substr($parts[$j], $tagStrLen);
                            array_pop($tagsToOpen);
                        }
                        array_pop($tagsToClose);
                    }

                    $chunk = mb_substr($chunk, $tagStrLen);
                }
            }

            $result = implode('', $tagsToOpen) . $parts[$currentPage - 1];

            while ($tag = array_pop($tagsToClose)) {
                $result .= $this->getClosingTagByOpeningTag($tag);
            }
        }

        $pages = [];
        for ($i = 1; $i <= $numberOfPages; $i++) {
            $pages[] = ['number' => $i, 'isCurrent' => ($i === $currentPage)];
        }

        $pagination = [
            'pages' => $pages,
            'numberOfPages' => $numberOfPages,
            'current' => $currentPage
        ];

        if ($currentPage < $numberOfPages) {
            $pagination['nextPage'] = $currentPage + 1;
        }
        if ($currentPage > 1) {
            $pagination['previousPage'] = $currentPage - 1;
        }

        $this->templateVariableContainer->add($as, $result);
        $this->templateVariableContainer->add('pagination', $pagination);

        return $this->renderChildren();
    }

    /**
     * Extracts the first html tag for a given html string
     *
     * @param string $html
     * @return string
     */
    protected function extractTag($html): string
    {
        $tag = '';
        $length = mb_strlen($html);
        for ($i = 0; $i < $length; $i++) {
            $char = mb_substr($html, $i, 1);
            $tag .= $char;
            if ($char === '>') {
                break;
            }
        }
        return $tag;
    }

    /**
     * Checks whether a given tag is self closing
     *
     * @param string $tag
     * @return bool
     */
    protected function isSelfClosingTag($tag): bool
    {
        return mb_substr($tag, -2) === '/>';
    }

    /**
     * Checks whether a given tag is closing tag
     *
     * @param string $tag
     * @return bool
     */
    protected function isClosingTag($tag): bool
    {
        return mb_substr($tag, 0, 2) === '</';
    }

    /**
     * Checks whether a given Tag is a an opening tag
     *
     * @param string $tag
     * @return bool
     */
    protected function isOpeningTag($tag): bool
    {
        return !($this->isSelfClosingTag($tag) || $this->isClosingTag($tag));
    }

    /**
     * Gets a closing tag from a given opening tag
     *
     * @param string $openingTag
     * @return string
     */
    protected function getClosingTagByOpeningTag($openingTag): string
    {
        if (!$tag = mb_strstr(mb_substr($openingTag, 1), ' ', true)) {
            $tag = mb_strstr(mb_substr($openingTag, 1), '>', true);
        }

        return '</' . $tag . '>';
    }
}
