<?php
defined('TYPO3_MODE') or die();

if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('scheduler')) {
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['AlterNET\\AlternetBeloginnews\\Task\\BeLoginNews'] = array(
		'extension' => $_EXTKEY,
		'title' => 'BE login news task',
		'description' => 'Imports items from RSS feed into the BE login news',
	);
}