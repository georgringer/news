<?php
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
/**
 * Demand domain model interface
 *
 * @package TYPO3
 * @subpackage tx_news
 * @author Nikolas Hagelstein <nikolas.hagelstein@gmail.com>
 */
interface Tx_News_Domain_Repository_DemandedRepositoryInterface {
	public function findDemanded(Tx_News_Domain_Model_DemandInterface $demand, $respectEnableFields = TRUE);
	public function countDemanded(Tx_News_Domain_Model_DemandInterface $demand);
}
