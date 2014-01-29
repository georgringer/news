<?php
/***************************************************************
 *  Copyright notice
 *  (c) 2013 Georg Ringer <typo3@ringerge.org>
 *  All rights reserved
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Tag controller
 */
class Tx_News_Controller_TagController extends Tx_News_Controller_NewsController {

	/**
	 * @var Tx_News_Domain_Repository_TagRepository
	 */
	protected $tagRepository;

	/**
	 * Inject a tag repository to enable DI
	 *
	 * @param Tx_News_Domain_Repository_TagRepository $tagRepository
	 * @return void
	 */
	public function injectTagRepository(Tx_News_Domain_Repository_TagRepository $tagRepository) {
		$this->tagRepository = $tagRepository;
	}

	/**
	 * List categories
	 *
	 * @param array $overwriteDemand
	 * @return void
	 */
	public function listAction(array $overwriteDemand = NULL) {
		// Default value is wrong for tags
		if ($this->settings['orderBy'] === 'datetime') {
			unset($this->settings['orderBy']);
		}

		$demand = $this->createDemandObjectFromSettings($this->settings);

		if ($this->settings['disableOverrideDemand'] != 1 && $overwriteDemand !== NULL) {
			$demand = $this->overwriteDemandObject($demand, $overwriteDemand);
		}

		$this->view->assignMultiple(array(
			'tags' => $this->tagRepository->findDemanded($demand),
			'overwriteDemand' => $overwriteDemand,
			'demand' => $demand,
		));
	}

}
