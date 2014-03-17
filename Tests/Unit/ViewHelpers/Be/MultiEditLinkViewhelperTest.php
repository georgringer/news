<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2012 Georg Ringer <typo3@ringerge.org>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * Tests for Tx_News_ViewHelpers_Be_MultiEditLinkViewHelper
 *
 * @package TYPO3
 * @subpackage tx_news
 * @author Georg Ringer <typo3@ringerge.org>
 */
class Tx_News_Tests_Unit_ViewHelpers_Be_MultiEditLinkViewHelperTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * Test if default file format works
	 *
	 * @test
	 * @return void
	 */
	public function viewHelperReturnsCorrectJavaScriptLink() {
		$viewHelper = new Tx_News_ViewHelpers_Be_MultiEditLinkViewHelper();

		$newsItem1 = new Tx_News_Domain_Model_News();
		$newsItem1->setTitle('Item 1');
		$newsItem1->_setProperty('uid', 3);
		$newsItem2 = new Tx_News_Domain_Model_News();
		$newsItem2->setTitle('Item 2');
		$newsItem2->_setProperty('uid', 9);

		$newsItems = new SplObjectStorage();
		$newsItems->attach($newsItem1);
		$newsItems->attach($newsItem2);

		$columns = 'title,description';
		$actualResult = $viewHelper->render($newsItems, $columns);

		$content = 'window.location.href=\'alt_doc.php?returnUrl=\'+T3_THIS_LOCATION+\'&edit[tx_news_domain_model_news][' .
			'3,9' .
			']=edit&columnsOnly=title,description&disHelp=1\';return false;';

		$this->assertEquals($content, $actualResult);
	}


}
