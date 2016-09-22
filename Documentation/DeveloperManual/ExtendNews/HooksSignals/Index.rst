.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../../Includes.txt

.. _hooks:

Signals & Hooks
===============
Several signals and hooks can be used to modify the behaviour of EXT:news.

.. only:: html

	.. contents::
		:local:
		:depth: 1

Signals
-------
Signals are currently only used in the various controllers. Every action emits a signal which can be used to change its output.

Example
^^^^^^^
As an example, the action ``detailAction`` of the ``Classes/Controller/NewsController`` is used.

.. code-block:: php

		$assignedValues = array(
			'newsItem' => $news,
			'currentPage' => (int)$currentPage,
		);

		$this->emitActionSignal('NewsController', self::SIGNAL_NEWS_DETAIL_ACTION, $assignedValues);

To fulfill that signal, you can create a slot in your custom extension. All what it needs is an entry in your ``ext_localconf.php`` file:

.. code-block:: php

	/** @var \TYPO3\CMS\Extbase\SignalSlot\Dispatcher $signalSlotDispatcher */
	$signalSlotDispatcher = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\SignalSlot\\Dispatcher');
	$signalSlotDispatcher->connect(
		'GeorgRinger\\News\\Controller\\NewsController',
		'listAction',
		'YourVendor\\yourextkey\\Slots\\NewsControllerSlot', // fully your choice
		'listActionSlot', // fully your choice
		TRUE
	);

An example call look like this:

.. code-block:: php

	/**
	 * @param int $listPid
	 * @param string $dateField
	 * @param array $data
	 * @param QueryResultInterface $news
	 * @param array $overwriteDemand
	 * @param NewsDemand $demand
	 * @param array $extendedVariables
	 */
	public function dateMenuActionSlot($listPid, $dateField, $data, $news, $overwriteDemand, $demand, $extendedVariables) {
		$data['authors'] = $this->newsAuthorRepository->countByAuthor($demand);
		return [
			'listPid' => $listPid,
			'dateField' => $dateField,
			'data' => $data,
			'news' => $news,
			'overwriteDemand' => $overwriteDemand,
			'demand' => $demand,
			'extendedVariables' => $extendedVariables
		];
	}

Hooks
-----

Domain/Repository/AbstractDemandedRepository.php findDemanded
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

This hook is very powerful, as it allows to modify the query used to fetch the news records.

Example
"""""""
This examples modifies the query and adds a constraint that only news records are shown which contain the word *yeah*.


First, register your implementation in the file ``ext_localconf.php``:

.. code-block:: php

	<?php
	defined('TYPO3_MODE') or die();

	$GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['Domain/Repository/AbstractDemandedRepository.php']['findDemanded'][$_EXTKEY]
		= 'YourVendor\\Extkey\\Hooks\\Repository->modify';

Now create the file ``Classes/Hooks/Repository.php``:

.. code-block:: php

	<?php

	namespace YourVendor\Extkey\Hooks;

	use TYPO3\CMS\Core\Utility\GeneralUtility;
	use \GeorgRinger\News\Domain\Repository\NewsRepository;

	class Repository {

		public function modify(array $params, NewsRepository $newsRepository) {
			$this->updateConstraints($params['demand'], $params['respectEnableFields'], $params['query'], $params['constraints']);
		}

		/**
		 * @param \GeorgRinger\News\Domain\Model\Dto\NewsDemand $demand
		 * @param bool $respectEnableFields
		 * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
		 * @param array $constraints
		 */
		protected function updateConstraints($demand, $respectEnableFields, \TYPO3\CMS\Extbase\Persistence\QueryInterface $query, array &$constraints) {
			$subject = 'yeah';
			$constraints[] = $query->like('title', '%' . $subject . '%');
		}
	}

.. hint:: Please change the vendor and extension key to your real life code.

Controller/NewsController overrideSettings
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
Use this hook to change the final settings which are for building queries, for the template, ...

Example
"""""""
This examples modifies the query and adds a constraint that only news records are shown which contain the word *yeah*.


First, register your implementation in the file ``ext_localconf.php``:

.. code-block:: php

	<?php
	defined('TYPO3_MODE') or die();

	$GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['Controller/NewsController.php']['overrideSettings'][$_EXTKEY]
		= 'YourVendor\\Extkey\\Hooks\\NewsControllerSettings->modify';

Now create the file ``Classes/Hooks/NewsControllerSettings.php``:

.. code-block:: php

	<?php

	namespace YourVendor\Extkey\Hooks;

	class NewsControllerSettings {

		public function modify(array $params) {
			$settings = $params['originalSettings'];
			$settings['fo'] = bar;

			return $settings
		}

.. hint:: Please change the vendor and extension key to your real life code.