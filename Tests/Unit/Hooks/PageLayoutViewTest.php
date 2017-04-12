<?php

namespace GeorgRinger\News\Tests\Unit\Hooks;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use Nimut\TestingFramework\MockObject\AccessibleMockObjectInterface;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use TYPO3\CMS\Lang\LanguageService;

/**
 * Tests for PageLayoutView
 *
 */
class PageLayoutViewTest extends UnitTestCase
{

    /** @var AccessibleMockObjectInterface */
    protected $pageLayoutView;

    public function setUp()
    {
        parent::setUp();

        $languageService = $this->getAccessibleMock(LanguageService::class, ['sL']);
        $languageService->expects($this->any())->method('sL')->will($this->returnValue('any language'));

        $GLOBALS['LANG'] = $languageService;

        $this->pageLayoutView = $this->getAccessibleMock('GeorgRinger\\News\\Hooks\\PageLayoutView', ['dummy']);
        $this->pageLayoutView->_set('databaseConnection', $this->getMockBuilder('TYPO3\CMS\\Core\\Utility\\GeneralUtility\\DatabaseConnection')->setMethods(['exec_SELECTquery', 'exec_SELECTgetRows'])->getMock());
    }

    /**
     * @test
     */
    public function getArchiveSettingAddsValueIfFilled()
    {
        $flexform = [];
        $this->addContentToFlexform($flexform, 'settings.archiveRestriction', 'something');

        $this->pageLayoutView->_set('flexformData', $flexform);
        $this->pageLayoutView->_call('getArchiveSettings');
        $this->assertEquals(count($this->pageLayoutView->_get('tableData')), 1);
    }

    /**
     * @test
     */
    public function getDetailPidSettingAddsValueIfFilled()
    {
        $flexform = [];
        $this->addContentToFlexform($flexform, 'settings.detailPid', '9999999999', 'additional');

        $this->pageLayoutView->_set('flexformData', $flexform);
        $this->pageLayoutView->_call('getDetailPidSetting');
        $this->assertEquals(count($this->pageLayoutView->_get('tableData')), 1);
    }

    /**
     * @test
     */
    public function getTagRestrictionSettingAddsValueIfFilled()
    {
        $flexform = [];
        $this->addContentToFlexform($flexform, 'settings.tags', '9999999999', 'additional');

        $this->pageLayoutView->_set('flexformData', $flexform);
        $this->pageLayoutView->_call('getTagRestrictionSetting');
        $this->assertEquals(count($this->pageLayoutView->_get('tableData')), 1);
    }

    /**
     * @test
     */
    public function getListPidSettingAddsValueIfFilled()
    {
        $flexform = [];
        $this->addContentToFlexform($flexform, 'settings.listPid', '9999999999', 'additional');

        $this->pageLayoutView->_set('flexformData', $flexform);
        $this->pageLayoutView->_call('getListPidSetting');
        $this->assertEquals(count($this->pageLayoutView->_get('tableData')), 1);
    }

    /**
     * @test
     */
    public function getOrderBySettingAddsValueIfFilled()
    {
        $flexform = [];
        $this->addContentToFlexform($flexform, 'settings.orderBy', 'fo');

        $this->pageLayoutView->_set('flexformData', $flexform);
        $this->pageLayoutView->_call('getOrderSettings');
        $this->assertEquals(count($this->pageLayoutView->_get('tableData')), 1);
    }

    /**
     * @test
     */
    public function getOrderDirectionSettingAddsValueIfFilled()
    {
        $flexform = [];
        $this->addContentToFlexform($flexform, 'settings.orderDirection', 'asc');

        $this->assertEquals($this->pageLayoutView->_call('getOrderDirectionSetting'), '');

        $this->pageLayoutView->_set('flexformData', $flexform);
        $out = $this->pageLayoutView->_call('getOrderDirectionSetting');
        $this->assertEquals((strlen($out) > 1), true);
    }

    /**
     * @test
     */
    public function getTopNewsFirstSettingAddsValueIfFilled()
    {
        $flexform = [];
        $this->addContentToFlexform($flexform, 'settings.topNewsFirst', '1', 'additional');

        $this->assertEquals($this->pageLayoutView->_call('getTopNewsFirstSetting'), '');

        $this->pageLayoutView->_set('flexformData', $flexform);
        $out = $this->pageLayoutView->_call('getTopNewsFirstSetting');
        $this->assertEquals((strlen($out) > 1), true);
    }

    /**
     * @test
     */
    public function getOffsetLimitSettingsAddsValueIfFilled()
    {
        $flexform = [];
        $this->addContentToFlexform($flexform, 'settings.offset', '1', 'additional');
        $this->pageLayoutView->_set('flexformData', $flexform);
        $this->pageLayoutView->_call('getOffsetLimitSettings');
        $this->assertEquals(count($this->pageLayoutView->_get('tableData')), 1);

        $this->addContentToFlexform($flexform, 'settings.limit', '1', 'additional');
        $this->pageLayoutView->_set('flexformData', $flexform);
        $this->pageLayoutView->_call('getOffsetLimitSettings');
        $this->assertEquals(count($this->pageLayoutView->_get('tableData')), 2);

        $this->addContentToFlexform($flexform, 'settings.hidePagination', '0', 'additional');
        $this->pageLayoutView->_set('flexformData', $flexform);
        $this->pageLayoutView->_call('getOffsetLimitSettings');
        $this->assertEquals(count($this->pageLayoutView->_get('tableData')), 2);

        $this->addContentToFlexform($flexform, 'settings.hidePagination', '1', 'additional');
        $this->pageLayoutView->_set('flexformData', $flexform);
        $this->pageLayoutView->_call('getOffsetLimitSettings');
        $this->assertEquals(count($this->pageLayoutView->_get('tableData')), 3);
    }

    /**
     * @test
     */
    public function getDateMenuSettingsAddsValueIfFilled()
    {
        $flexform = [];
        $this->addContentToFlexform($flexform, 'settings.dateField', 'field');
        $this->pageLayoutView->_set('flexformData', $flexform);
        $this->pageLayoutView->_call('getDateMenuSettings');
        $this->assertEquals(count($this->pageLayoutView->_get('tableData')), 1);
    }

    /**
     * @test
     */
    public function getTimeRestrictionSettingAddsValueIfFilled()
    {
        $flexform = [];
        $this->addContentToFlexform($flexform, 'settings.timeRestriction', 'fo');
        $this->pageLayoutView->_set('flexformData', $flexform);
        $this->pageLayoutView->_call('getTimeRestrictionSetting');
        $this->assertEquals(count($this->pageLayoutView->_get('tableData')), 1);

        $this->addContentToFlexform($flexform, 'settings.timeRestrictionHigh', 'bar');
        $this->pageLayoutView->_set('flexformData', $flexform);
        $this->pageLayoutView->_call('getTimeRestrictionSetting');
        $this->assertEquals(count($this->pageLayoutView->_get('tableData')), 2);
    }

    /**
     * @test
     */
    public function getTemplateLayoutSettingsAddsValueIfFilled()
    {
        $flexform = [];
        $mockedTemplateLayout = $this->getAccessibleMock('GeorgRinger\\News\\Utility\\TemplateLayout', ['getAvailableTemplateLayouts']);

        $mockedTemplateLayout->expects($this->once())->method('getAvailableTemplateLayouts')->will($this->returnValue([['bar', 'fo']]));

        $this->addContentToFlexform($flexform, 'settings.templateLayout', 'fo', 'template');
        $this->pageLayoutView->_set('flexformData', $flexform);
        $this->pageLayoutView->_set('templateLayoutsUtility', $mockedTemplateLayout);

        $this->pageLayoutView->_call('getTemplateLayoutSettings', 1);
        $this->assertEquals(count($this->pageLayoutView->_get('tableData')), 1);
    }

    /**
     * @test
     */
    public function getOverrideDemandSettingsAddsValueIfFilled()
    {
        $flexform = [];
        $this->addContentToFlexform($flexform, 'settings.disableOverrideDemand', '1', 'additional');
        $this->pageLayoutView->_set('flexformData', $flexform);
        $this->pageLayoutView->_call('getOverrideDemandSettings');
        $this->assertEquals(count($this->pageLayoutView->_get('tableData')), 1);
    }

    /**
     * Add content to given flexform
     *
     * @param array $flexform configuration
     * @param string $key key of field
     * @param string $value value of field
     * @param string $sheet name of sheet
     */
    protected function addContentToFlexform(array &$flexform, $key, $value, $sheet = 'sDEF')
    {
        $flexform = [
            'data' => [
                $sheet => [
                    'lDEF' => [
                        $key => [
                            'vDEF' => $value
                        ]
                    ]
                ]
            ]
        ];
    }
}
