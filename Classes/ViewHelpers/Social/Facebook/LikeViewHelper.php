<?php

namespace GeorgRinger\News\ViewHelpers\Social\Facebook;

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
use GeorgRinger\News\Utility\Url;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * ViewHelper to add a like button
 * Details: http://developers.facebook.com/docs/reference/plugins/like
 *
 * Examples
 * ==============
 *
 * <n:social.facebook.like />
 * Result: Facebook widget to share the current URL
 *
 * <n:social.facebook.like
 *        href="http://www.typo3.org"
 *        width="300"
 *        font="arial" />
 * Result: Facebook widget to share www.typo3.org within a plugin styled with
 * width 300 and arial as font
 *
 */
class LikeViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper
{

    /**
     * @var \GeorgRinger\News\Service\SettingsService
     */
    protected $pluginSettingsService;

    /**
     * @var    string
     */
    protected $tagName = 'fb:like';

    /**
     * @var \GeorgRinger\News\Service\SettingsService $pluginSettingsService
     * @return void
     */
    public function injectSettingsService(\GeorgRinger\News\Service\SettingsService $pluginSettingsService)
    {
        $this->pluginSettingsService = $pluginSettingsService;
    }

    /**
     * Arguments initialization
     *
     * @return void
     */
    public function initializeArguments()
    {
        $this->registerTagAttribute('href', 'string', 'Given url, if empty, current url is used');
        $this->registerTagAttribute('layout', 'string', 'Either: standard, button_count or box_count');
        $this->registerTagAttribute('width', 'integer', 'With of widget, default 450');
        $this->registerTagAttribute('font', 'string',
            'Font, options are: arial,lucidia grande,segoe ui,tahoma,trebuchet ms,verdana');
        $this->registerTagAttribute('javaScript', 'string',
            'JS URL. If not set, default is used, if set to -1 no Js is loaded');
    }

    /**
     * Render the facebook like viewhelper
     *
     * @return string
     */
    public function render()
    {
        $code = '';

        $url = (!empty($this->arguments['href'])) ?
            $this->arguments['href'] :
            GeneralUtility::getIndpEnv('TYPO3_REQUEST_URL');

        // absolute urls are needed
        $this->tag->addAttribute('href', Url::prependDomain($url));
        $this->tag->forceClosingTag(true);

        // -1 means no JS
        if ($this->arguments['javaScript'] != '-1') {
            if (empty($this->arguments['javaScript'])) {
                $tsSettings = $this->pluginSettingsService->getSettings();

                $locale = (!empty($tsSettings['facebookLocale']) && strlen($tsSettings['facebookLocale']) <= 5) ? $tsSettings['facebookLocale'] : 'en_US';

                $code = '<script src="https://connect.facebook.net/' . $locale . '/all.js#xfbml=1"></script>';

                // Social interaction Google Analytics
                if ($this->pluginSettingsService->getByPath('analytics.social.facebookLike') == 1) {
                    $code .= GeneralUtility::wrapJS("
						FB.Event.subscribe('edge.create', function(targetUrl) {
						 	_gaq.push(['_trackSocial', 'facebook', 'like', targetUrl]);
						});
						FB.Event.subscribe('edge.remove', function(targetUrl) {
						  _gaq.push(['_trackSocial', 'facebook', 'unlike', targetUrl]);
						});
					");
                }
            } else {
                $code = '<script src="' . htmlspecialchars($this->arguments['javaScript']) . '"></script>';
            }
        }

        // seems as if a div with id fb-root is needed this is just a dirty
        // workaround to make things work again Perhaps we should
        // use the iframe variation.
        $code .= '<div id="fb-root"></div>' . $this->tag->render();
        return $code;
    }
}
