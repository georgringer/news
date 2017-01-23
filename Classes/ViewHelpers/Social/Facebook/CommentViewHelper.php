<?php

namespace GeorgRinger\News\ViewHelpers\Social\Facebook;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * ViewHelper to comment content
 * Details: http://developers.facebook.com/docs/reference/plugins/comments
 *
 * Examples
 * ==============
 * <n:social.facebook.comment appId="165193833530000" xid="news-{newsItem.uid}" />
 * Result: Facebook widget to comment an article
 *
 */
class CommentViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper
{

    /**
     * @var    string
     */
    protected $tagName = 'fb:comments';

    /**
     * @var \GeorgRinger\News\Service\SettingsService
     */
    protected $pluginSettingsService;

    /**
     * @var \GeorgRinger\News\Service\SettingsService $pluginSettingsService
     */
    public function injectSettingsService(\GeorgRinger\News\Service\SettingsService $pluginSettingsService)
    {
        $this->pluginSettingsService = $pluginSettingsService;
    }

    /**
     * Arguments initialization
     *
     */
    public function initializeArguments()
    {
        $this->registerTagAttribute('xid', 'string',
            'An id associated with the comments object, Default: URL-encoded page URL', true);
        $this->registerTagAttribute('numposts', 'integer',
            'the number of comments to display, or 0 to hide all comments');
        $this->registerTagAttribute('width', 'integer', 'The width of the plugin in px, default = 425');
        $this->registerTagAttribute('publishFeed', 'boolean',
            'Whether the publish feed story checkbox is checked., default = TRUE');
    }

    /**
     * Render facebook comment viewhelper
     *
     * @param string $appId
     * @return string
     */
    public function render($appId)
    {
        GeneralUtility::deprecationLog(sprintf('The ViewHelper "%s" has been deprecated and will be removed with version 7.0.0 of EXT:news', self::class));

        $tsSettings = $this->pluginSettingsService->getSettings();
        $this->tag->addAttribute('data-href', GeneralUtility::getIndpEnv('TYPO3_REQUEST_URL'));
        $this->tag->forceClosingTag(true);

        $locale = (!empty($tsSettings['facebookLocale']) && strlen($tsSettings['facebookLocale']) <= 5) ? $tsSettings['facebookLocale'] : 'en_US';

        $code = '<div id="fb-root"></div>
					<script src="http://connect.facebook.net/' . $locale . '/all.js#appId=' . htmlspecialchars($appId) .
            '&amp;xfbml=1"></script>';
        $code .= $this->tag->render();

        return $code;
    }
}
