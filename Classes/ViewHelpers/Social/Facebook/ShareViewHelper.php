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
 * ViewHelper to share content
 * Details: https://developers.facebook.com/docs/plugins/share-button/
 *
 * Examples
 * ==============
 * <n:social.facebook.share />
 * Result: Facebook widget to share current URL with the Facebook button
 *
 */
class ShareViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper
{

    /**
     * @var \GeorgRinger\News\Service\SettingsService
     */
    protected $pluginSettingsService;

    /** @var string */
    protected $tagName = 'div';

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
        $this->registerTagAttribute('type', 'string', 'default: button_count');
        $this->registerTagAttribute('shareurl', 'string', 'share url');
    }

    /**
     * Render a share button
     *
     * @param bool $loadJs
     * @return string
     */
    public function render($loadJs = true)
    {
        GeneralUtility::deprecationLog(sprintf('The ViewHelper "%s" has been deprecated and will be removed with version 7.0.0 of EXT:news', self::class));

        if (!empty($this->arguments['type'])) {
            $this->tag->addAttribute('data-type', $this->arguments['type']);
            $this->tag->removeAttribute('type');
        } else {
            $this->tag->addAttribute('data-type', 'button_count');
        }

        $shareUrl = empty($this->arguments['shareurl']) ? GeneralUtility::getIndpEnv('TYPO3_REQUEST_URL') : $this->arguments['shareurl'];
        $this->tag->addAttribute('data-href', $shareUrl);
        $this->tag->removeAttribute('shareurl');
        $this->tag->addAttribute('class', 'fb-share-button');
        $this->tag->setContent(' ');

        $code = $this->tag->render();
        if ($loadJs) {
            $code .= '<div id="fb-root"></div>
				<script>(function(d, s, id) {
				  var js, fjs = d.getElementsByTagName(s)[0];
				  if (d.getElementById(id)) return;
				  js = d.createElement(s); js.id = id;
				  js.src = "//connect.facebook.net/de_DE/sdk.js";
				  fjs.parentNode.insertBefore(js, fjs);
				}(document, \'script\', \'facebook-jssdk\'));</script>';
        }

        // Social interaction Google Analytics
        if ($this->pluginSettingsService->getByPath('analytics.social.facebookShare') == 1) {
            $code .= GeneralUtility::wrapJS("
				FB.Event.subscribe('message.send', function(targetUrl) {
				  _gaq.push(['_trackSocial', 'facebook', 'send', targetUrl]);
				});
			");
        }

        return $code;
    }
}
