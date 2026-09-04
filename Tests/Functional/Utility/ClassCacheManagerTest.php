<?php

declare(strict_types=1);

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Tests\Functional\Utility;

use GeorgRinger\News\Utility\ClassCacheManager;
use PHPUnit\Framework\Attributes\IgnoreDeprecations;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Cache\Frontend\PhpFrontend;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * The proxy class generator merges the news base class with the partials of every
 * extension registered for it. Two extensions registering *different* classes must
 * not bleed into each other.
 */
// Bootstrapping EXT:news itself triggers core v14 deprecations (ext_tables.php,
// addPiFlexFormValue), unrelated to the proxy class generator under test.
#[IgnoreDeprecations]
class ClassCacheManagerTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = [
        'typo3conf/ext/news',
        'typo3conf/ext/news/Tests/Functional/Fixtures/Extensions/news_extend_news',
        'typo3conf/ext/news/Tests/Functional/Fixtures/Extensions/news_extend_newsdefault',
    ];

    /**
     * The generated proxy classes, keyed by cache entry identifier.
     *
     * @var array<string, string>
     */
    protected array $generatedClasses = [];

    protected function setUp(): void
    {
        parent::setUp();

        // Explicit registration instead of relying on the ext_localconf.php order of the
        // two fixture extensions: it is exactly that order which triggers the leak.
        $GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['classes'] = [
            'Domain/Model/News' => ['news_extend_news' => 'news_extend_news'],
            'Domain/Model/NewsDefault' => ['news_extend_newsdefault' => 'news_extend_newsdefault'],
        ];

        $classCache = $this->createMock(PhpFrontend::class);
        $classCache->method('set')->willReturnCallback(
            function (string $entryIdentifier, string $sourceCode): void {
                $this->generatedClasses[$entryIdentifier] = $sourceCode;
            }
        );

        (new ClassCacheManager($classCache))->reBuild();
    }

    #[Test]
    public function proxyClassIsGeneratedForEveryRegisteredClass(): void
    {
        self::assertSame(
            ['tx_news_domain_model_news', 'tx_news_domain_model_newsdefault'],
            array_keys($this->generatedClasses)
        );
    }

    #[Test]
    public function proxyClassContainsTheInitializeObjectLinesOfItsOwnPartialsOnly(): void
    {
        $news = $this->generatedClasses['tx_news_domain_model_news'];
        $newsDefault = $this->generatedClasses['tx_news_domain_model_newsdefault'];

        self::assertStringContainsString('$this->categories ??= new ObjectStorage();', $news);
        self::assertStringContainsString('$this->extendNewsItems ??= new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();', $news);

        self::assertStringContainsString('$this->extendDefaultItems ??= new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();', $newsDefault);
        self::assertStringNotContainsString('$this->categories ??= new ObjectStorage();', $newsDefault);
        self::assertStringNotContainsString('$this->extendNewsItems', $newsDefault);
    }

    /**
     * Only the base partial contributes `use` statements, and the base partial of
     * NewsDefault (`class NewsDefault extends News {}`) has none. Any short class name
     * leaking in from another proxy class resolves against the model namespace and
     * dies at runtime with "Class GeorgRinger\News\Domain\Model\ObjectStorage not found".
     */
    #[Test]
    public function proxyClassDoesNotUseShortClassNamesWithoutAnImport(): void
    {
        $newsDefault = $this->generatedClasses['tx_news_domain_model_newsdefault'];

        self::assertStringNotContainsString('use TYPO3\CMS\Extbase\Persistence\ObjectStorage;', $newsDefault);
        self::assertStringNotContainsString('new ObjectStorage(', $newsDefault);
    }

    #[Test]
    public function initializeObjectIsEmittedExactlyOncePerProxyClass(): void
    {
        foreach ($this->generatedClasses as $identifier => $sourceCode) {
            self::assertSame(
                1,
                substr_count($sourceCode, 'public function initializeObject(): void'),
                $identifier . ' declares initializeObject() more than once'
            );
        }
    }

    #[Test]
    public function generatedProxyClassesAreSyntacticallyValidPhp(): void
    {
        foreach ($this->generatedClasses as $identifier => $sourceCode) {
            $file = tempnam(sys_get_temp_dir(), 'news_proxy_') . '.php';
            file_put_contents($file, '<?php' . LF . $sourceCode);
            exec(escapeshellarg(PHP_BINARY) . ' -l ' . escapeshellarg($file) . ' 2>&1', $output, $exitCode);
            unlink($file);

            self::assertSame(0, $exitCode, $identifier . ': ' . implode(LF, $output));
        }
    }
}
