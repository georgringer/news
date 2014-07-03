<?php

namespace Vendor\Fo\Bar;

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
