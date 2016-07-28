<?php

namespace GeorgRinger\News\Tests\Unit\Hooks;

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
use TYPO3\CMS\Rtehtmlarea\Extension\Language;

/**
 * Tests for PageLayoutView
 *
 */
class PageLayoutViewTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{

    /** @var  \TYPO3\CMS\Core\Tests\AccessibleObjectInterface */
    protected $pageLayoutView;

    public function setUp()
    {
        parent::setUp();

        $languageService = $this->getAccessibleMock(Language::class, ['sL']);
        $languageService->expects($this->any())->method('sL')->will($this->returnValue('any language'));

        $GLOBALS['LANG'] = $languageService;

        $this->pageLayoutView = $this->getAccessibleMock('GeorgRinger\\News\\Hooks\\PageLayoutView', ['dummy']);
        $this->pageLayoutView->_set('databaseConnection', $this->getMockBuilder('TYPO3\CMS\\Core\\Utility\\GeneralUtility\\DatabaseConnection')->setMethods(['exec_SELECTquery', 'exec_SELECTgetRows'])->getMock());
    }

    /**
     * @test
     * @return void
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
     * @return void
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
     * @return void
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
     * @return void
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
     * @return void
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
     * @return void
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
     * @return void
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
     * @return void
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
     * @return void
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
     * @return void
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
     * @return void
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
     * @return void
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
     * @return void
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
