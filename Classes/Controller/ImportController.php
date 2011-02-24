<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Georg Ringer <typo3@ringerge.org>
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
 * Controller to import news records from tt_news2
 *
 * @package TYPO3
 * @subpackage tx_news2
 * @version $Id$
 */
class Tx_News2_Controller_ImportController extends Tx_Extbase_MVC_Controller_ActionController {

	protected function getAvailableJobs() {
		return array(
			'Tx_News2_Jobs_TTNewsCategoryImportJob' => 'Import tt_news category records',
			'Tx_News2_Jobs_TTNewsImportJob' => 'Import tt_news news records'
		);
	}

	public function indexAction() {
		$this->view->assign('availableJobs', array_merge(array(0 => ''), $this->getAvailableJobs()));
	}

	/**
	 * @param string $jobClassName
	 * @param int $offset
	 * @return string
	 */
	public function runJobAction($jobClassName, $offset = 0) {
		$job = $this->objectManager->get($jobClassName);
		$job->run($offset);

		return 'OK';
	}

	/**
	 * @param  string $jobClassName
	 * @return string
	 */
	public function jobInfoAction($jobClassName) {
		$job = $this->objectManager->get($jobClassName);
		return json_encode($job->getInfo());
	}
}
?>