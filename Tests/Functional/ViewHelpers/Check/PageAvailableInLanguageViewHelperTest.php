<?php

declare(strict_types=1);

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Tests\Functional\ViewHelpers\Check;

use GeorgRinger\News\ViewHelpers\Check\PageAvailableInLanguageViewHelper;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Core\SystemEnvironmentBuilder;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContextFactory;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;
use TYPO3Fluid\Fluid\View\TemplateView;

class PageAvailableInLanguageViewHelperTest extends FunctionalTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // NewsAvailability reads $GLOBALS['TYPO3_REQUEST'] and its getRequest()
        // is typed non-nullable, so without a request it fails with a TypeError
        // the ViewHelper does not catch. On TYPO3 13 the RenderingContextFactory
        // additionally insists on the applicationType attribute.
        $GLOBALS['TYPO3_REQUEST'] = (new ServerRequest('https://example.com/'))
            ->withAttribute('applicationType', SystemEnvironmentBuilder::REQUESTTYPE_FE);
    }

    protected function tearDown(): void
    {
        unset($GLOBALS['TYPO3_REQUEST']);

        parent::tearDown();
    }

    /**
     * Fluid calls verdict() statically, which is also the only entry point in
     * compiled templates because AbstractConditionViewHelper::convert() is
     * final. A ViewHelper implementing its condition only in an overridden
     * render() inherits the default verdict(), which looks at a "condition"
     * argument this ViewHelper does not have - a cached template would then
     * always take the else branch.
     */
    #[Test]
    public function verdictIsImplementedAndDoesNotFallBackToTheParentDefault(): void
    {
        $context = $this->get(RenderingContextFactory::class)->create();

        // With no news id in the request NewsAvailability throws, and the
        // ViewHelper treats that as "available".
        self::assertTrue(PageAvailableInLanguageViewHelper::verdict(['language' => 0], $context));
    }

    #[Test]
    public function thenChildIsRenderedWhenPageIsAvailable(): void
    {
        $src = '<html xmlns:n="http://typo3.org/ns/GeorgRinger/News/ViewHelpers" data-namespace-typo3-fluid="true">'
            . '<n:check.pageAvailableInLanguage language="0">available</n:check.pageAvailableInLanguage>'
            . '</html>';

        $context = $this->get(RenderingContextFactory::class)->create();
        $context->getTemplatePaths()->setTemplateSource($src);

        self::assertSame('available', (string)(new TemplateView($context))->render());
    }
}
