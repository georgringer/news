<?php

namespace GeorgRinger\News\ViewHelpers\Social;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

/**
 * Hashes an email-address and fetches the image from gravatar
 * see: http://en.gravatar.com/site/implement/images/
 *
 * * Examples
 * ==============
 *
 * <n:social.gravatar email="{newsItem.authorEmail}" size="20" class="gravatar" />
 * Result: a img-tag with the gravatar-url with 20px square-image
 *
 */
class GravatarViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper
{
    /**
     * @var string
     */
    protected $tagName = 'img';

    /**
     * Gravatar request-url
     */
    const GRAVATAR_IMAGE_REQUEST_URL = 'https://www.gravatar.com/avatar/';

    /**
     * initializes the arguments
     *
     */
    public function initializeArguments()
    {
        $this->registerUniversalTagAttributes();
        $this->registerArgument('email', 'string', 'e-mail address of the user');
        $this->registerArgument('size', 'integer', 'size since the images are square');
        $this->registerArgument('alt', 'string', 'alt-text');
        $this->registerArgument('default', 'string',
            'default gravatar image (404, mm, identicon, monsterid, wavatar, retro, or blank)');
    }

    /**
     * Renders the view
     *
     * @return string
     */
    public function render()
    {
        $args = [];
        $urlArgs = '';

        if ((int)$this->arguments['size'] > 0) {
            $args['s'] = (int)$this->arguments['size'];
        }

        if ($this->arguments['default']) {
            $args['d'] = $this->arguments['default'];
        }

        if (count($args) > 0) {
            $urlArgs = \TYPO3\CMS\Core\Utility\GeneralUtility::implodeArrayForUrl('', $args);
            $urlArgs = '?' . ltrim($urlArgs, '&');
        }

        $this->tag->addAttribute('src', self::GRAVATAR_IMAGE_REQUEST_URL . md5($this->arguments['email']) . $urlArgs);
        $this->tag->addAttribute('alt', $this->arguments['alt']);

        return $this->tag->render();
    }
}
