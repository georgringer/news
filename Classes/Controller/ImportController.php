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
 * Controller to import news records
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class Tx_News_Controller_ImportController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	/**
	 * Retrieve all available import jobs by traversing trough registered
	 * import jobs and checking "isEnabled".
	 *
	 * @return array
	 */
	protected function getAvailableJobs() {
		$availableJobs = array();
		$registeredJobs = Tx_News_Utility_ImportJob::getRegisteredJobs();

		foreach ($registeredJobs as $registeredJob) {
			$jobInstance = $this->objectManager->get($registeredJob['className']);
			if ($jobInstance instanceof Tx_News_Jobs_ImportJobInterface && $jobInstance->isEnabled()) {
				$availableJobs[$registeredJob['className']] = $GLOBALS['LANG']->sL($registeredJob['title']);
			}
		}

		return $availableJobs;
	}

	/**
	 * Shows the import jobs selection .
	 *
	 * @return void
	 */
	public function indexAction() {
		$this->view->assign('availableJobs', array_merge(array(0 => ''), $this->getAvailableJobs()));
		$this->view->assign('moduleUrl', \TYPO3\CMS\Backend\Utility\BackendUtility::getModuleUrl($this->request->getPluginName()));
	}

	/**
	 * Runs an actual job.
	 *
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
	 * Retrieves the job info of a given jobClass
	 *
	 * @param  string $jobClassName
	 * @return string
	 */
	public function jobInfoAction($jobClassName) {
		$job = $this->objectManager->get($jobClassName);
		return json_encode($job->getInfo());
	}
}
