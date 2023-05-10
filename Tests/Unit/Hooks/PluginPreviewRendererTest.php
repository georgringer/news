<?php

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Tests\Unit\Hooks;

use GeorgRinger\News\Hooks\PluginPreviewRenderer;
use GeorgRinger\News\Utility\TemplateLayout;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\TestingFramework\Core\BaseTestCase;

/**
 * Tests for PluginPreviewRenderer
 */
class PluginPreviewRendererTest extends BaseTestCase
{
    /** @var PluginPreviewRenderer|\PHPUnit\Framework\MockObject\MockObject|\TYPO3\TestingFramework\Core\AccessibleObjectInterface */
    protected $pageLayoutView;

    public function setup(): void
    {
        parent::setUp();

        $languageService = $this->getAccessibleMock(LanguageService::class, ['sl'], [], '', false, false);
        $languageService->expects(self::any())->method('sL')->willReturn('any language');

        $GLOBALS['LANG'] = $languageService;

        $this->pageLayoutView = $this->getAccessibleMock(PluginPreviewRenderer::class, null, [], '', false);
        $this->pageLayoutView->_set('databaseConnection', $this->getMockBuilder('TYPO3\CMS\\Core\\Utility\\GeneralUtility\\DatabaseConnection')->setMethods(['exec_SELECTquery', 'exec_SELECTgetRows'])->getMock());
    }

    /**
     * @test
     */
    public function getArchiveSettingAddsValueIfFilled(): void
    {
        $flexform = [];
        $this->addContentToFlexform($flexform, 'settings.archiveRestriction', 'something');

        $this->pageLayoutView->_set('flexformData', $flexform);
        $this->pageLayoutView->_call('getArchiveSettings');
        self::assertEquals(count($this->pageLayoutView->_get('tableData')), 1);
    }

    /**
     * @test
     */
    public function getDetailPidSettingAddsValueIfFilled(): void
    {
        $flexform = [];
        $this->addContentToFlexform($flexform, 'settings.detailPid', '9999999999', 'additional');

        $this->pageLayoutView->_set('flexformData', $flexform);
        $this->pageLayoutView->_call('getDetailPidSetting');
        self::assertEquals(count($this->pageLayoutView->_get('tableData')), 1);
    }

    /**
     * @test
     */
    public function getTagRestrictionSettingAddsValueIfFilled(): void
    {
        $flexform = [];
        $this->addContentToFlexform($flexform, 'settings.tags', '9999999999', 'additional');

        $this->pageLayoutView->_set('flexformData', $flexform);
        $this->pageLayoutView->_call('getTagRestrictionSetting');
        self::assertEquals(count($this->pageLayoutView->_get('tableData')), 1);
    }

    /**
     * @test
     */
    public function getListPidSettingAddsValueIfFilled(): void
    {
        $flexform = [];
        $this->addContentToFlexform($flexform, 'settings.listPid', '9999999999', 'additional');

        $this->pageLayoutView->_set('flexformData', $flexform);
        $this->pageLayoutView->_call('getListPidSetting');
        self::assertEquals(count($this->pageLayoutView->_get('tableData')), 1);
    }

    /**
     * @test
     */
    public function getOrderBySettingAddsValueIfFilled(): void
    {
        $flexform = [];
        $this->addContentToFlexform($flexform, 'settings.orderBy', 'fo');

        $this->pageLayoutView->_set('flexformData', $flexform);
        $this->pageLayoutView->_call('getOrderSettings');
        self::assertEquals(count($this->pageLayoutView->_get('tableData')), 1);
    }

    /**
     * @test
     */
    public function getOrderDirectionSettingAddsValueIfFilled(): void
    {
        $flexform = [];
        $this->addContentToFlexform($flexform, 'settings.orderDirection', 'asc');

        self::assertEquals($this->pageLayoutView->_call('getOrderDirectionSetting'), '');

        $this->pageLayoutView->_set('flexformData', $flexform);
        $out = $this->pageLayoutView->_call('getOrderDirectionSetting');
        self::assertEquals(strlen($out) > 1, true);
    }

    /**
     * @test
     */
    public function getTopNewsFirstSettingAddsValueIfFilled(): void
    {
        $flexform = [];
        $this->addContentToFlexform($flexform, 'settings.topNewsFirst', '1', 'additional');

        self::assertEquals($this->pageLayoutView->_call('getTopNewsFirstSetting'), '');

        $this->pageLayoutView->_set('flexformData', $flexform);
        $out = $this->pageLayoutView->_call('getTopNewsFirstSetting');
        self::assertEquals(strlen($out) > 1, true);
    }

    /**
     * @test
     */
    public function getOffsetLimitSettingsAddsValueIfFilled(): void
    {
        $flexform = [];
        $this->addContentToFlexform($flexform, 'settings.offset', '1', 'additional');
        $this->pageLayoutView->_set('flexformData', $flexform);
        $this->pageLayoutView->_call('getOffsetLimitSettings');
        self::assertEquals(count($this->pageLayoutView->_get('tableData')), 1);

        $this->addContentToFlexform($flexform, 'settings.limit', '1', 'additional');
        $this->pageLayoutView->_set('flexformData', $flexform);
        $this->pageLayoutView->_call('getOffsetLimitSettings');
        self::assertEquals(count($this->pageLayoutView->_get('tableData')), 2);

        $this->addContentToFlexform($flexform, 'settings.hidePagination', '0', 'additional');
        $this->pageLayoutView->_set('flexformData', $flexform);
        $this->pageLayoutView->_call('getOffsetLimitSettings');
        self::assertEquals(count($this->pageLayoutView->_get('tableData')), 2);

        $this->addContentToFlexform($flexform, 'settings.hidePagination', '1', 'additional');
        $this->pageLayoutView->_set('flexformData', $flexform);
        $this->pageLayoutView->_call('getOffsetLimitSettings');
        self::assertEquals(count($this->pageLayoutView->_get('tableData')), 3);
    }

    /**
     * @test
     */
    public function getDateMenuSettingsAddsValueIfFilled(): void
    {
        $flexform = [];
        $this->addContentToFlexform($flexform, 'settings.dateField', 'field');
        $this->pageLayoutView->_set('flexformData', $flexform);
        $this->pageLayoutView->_call('getDateMenuSettings');
        self::assertEquals(count($this->pageLayoutView->_get('tableData')), 1);
    }

    /**
     * @test
     */
    public function getTimeRestrictionSettingAddsValueIfFilled(): void
    {
        $flexform = [];
        $this->addContentToFlexform($flexform, 'settings.timeRestriction', 'fo');
        $this->pageLayoutView->_set('flexformData', $flexform);
        $this->pageLayoutView->_call('getTimeRestrictionSetting');
        self::assertEquals(count($this->pageLayoutView->_get('tableData')), 1);

        $this->addContentToFlexform($flexform, 'settings.timeRestrictionHigh', 'bar');
        $this->pageLayoutView->_set('flexformData', $flexform);
        $this->pageLayoutView->_call('getTimeRestrictionSetting');
        self::assertEquals(count($this->pageLayoutView->_get('tableData')), 2);
    }

    /**
     * @test
     */
    public function getTemplateLayoutSettingsAddsValueIfFilled(): void
    {
        $flexform = [];
        $mockedTemplateLayout = $this->getAccessibleMock(TemplateLayout::class, ['getAvailableTemplateLayouts']);

        $mockedTemplateLayout->expects(self::once())->method('getAvailableTemplateLayouts')->willReturn([['bar', 'fo']]);

        $this->addContentToFlexform($flexform, 'settings.templateLayout', 'fo', 'template');
        $this->pageLayoutView->_set('flexformData', $flexform);
        $this->pageLayoutView->_set('templateLayoutsUtility', $mockedTemplateLayout);

        $this->pageLayoutView->_call('getTemplateLayoutSettings', 1);
        self::assertEquals(count($this->pageLayoutView->_get('tableData')), 1);
    }

    /**
     * @test
     */
    public function getOverrideDemandSettingsAddsValueIfFilled(): void
    {
        $flexform = [];
        $this->addContentToFlexform($flexform, 'settings.disableOverrideDemand', '1', 'additional');
        $this->pageLayoutView->_set('flexformData', $flexform);
        $this->pageLayoutView->_call('getOverrideDemandSettings');
        self::assertEquals(count($this->pageLayoutView->_get('tableData')), 1);
    }

    /**
     * Add content to given flexform
     *
     * @param array $flexform configuration
     * @param string $key key of field
     * @param string $value value of field
     * @param string $sheet name of sheet
     */
    protected function addContentToFlexform(array &$flexform, $key, $value, $sheet = 'sDEF'): void
    {
        $flexform = [
            'data' => [
                $sheet => [
                    'lDEF' => [
                        $key => [
                            'vDEF' => $value,
                        ],
                    ],
                ],
            ],
        ];
    }
}
