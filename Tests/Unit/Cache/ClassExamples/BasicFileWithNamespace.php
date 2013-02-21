<?php

namespace Vendor\Fo\Bar;

	/***************************************************************
	 *  Copyright notice
	 *  (c) 2012 Christian Kuhn <lolli@schwarzbu.ch>
	 *  All rights reserved
	 *  This script is part of the TYPO3 project. The TYPO3 project is
	 *  free software; you can redistribute it and/or modify
	 *  it under the terms of the GNU General Public License as published by
	 *  the Free Software Foundation; either version 2 of the License, or
	 *  (at your option) any later version.
	 *  The GNU General Public License can be found at
	 *  http://www.gnu.org/copyleft/gpl.html.
	 *  A copy is found in the textfile GPL.txt and important notices to the license
	 *  from the author is found in LICENSE.txt distributed with these scripts.
	 *  This script is distributed in the hope that it will be useful,
	 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
	 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	 *  GNU General Public License for more details.
	 *  This copyright notice MUST APPEAR in all copies of the script!
	 ***************************************************************/

/**
 * Constraints for log entries
 *
 * @author Christian Kuhn <lolli@schwarzbu.ch>
 */
class Model extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * Some fo
	 *
	 * @var array
	 */
	protected $fo = array();

	/**
	 * @param array $fo
	 */
	public function setFo($fo) {
		$this->fo = $fo;
	}

	/**
	 * @return array
	 */
	public function getFo() {
		return $this->fo;
	}

}

?>