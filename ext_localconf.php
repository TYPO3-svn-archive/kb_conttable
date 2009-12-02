<?php
if (!defined ("TYPO3_MODE")) 	die ("Access denied.");

$TYPO3_CONF_VARS['BE']['XCLASS']['t3lib/class.t3lib_clipboard.php'] = t3lib_extMgm::extPath($_EXTKEY).'class.ux_t3lib_clipboard.php';
$TYPO3_CONF_VARS['FE']['XCLASS']['t3lib/class.t3lib_clipboard.php'] = t3lib_extMgm::extPath($_EXTKEY).'class.ux_t3lib_clipboard.php';


$_EXTCONF = unserialize($_EXTCONF);    // unserializing the configuration so we can use it here:

$GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$_EXTKEY]['previewTextLen'] = $_EXTCONF['previewTextLen'] ? intval($_EXTCONF['previewTextLen']) : 100;
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$_EXTKEY]['colPos'] = $_EXTCONF['colPos'] ? intval($_EXTCONF['colPos']) : 10;
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$_EXTKEY]['showContentCol'] = $_EXTCONF['showContentCol'] ? 1 : 0;
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$_EXTKEY]['RTEconf'] = trim($_EXTCONF['RTEconf']);
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$_EXTKEY]['fastMode'] = $_EXTCONF['fastMode'] ? true : false;

// TODO: Database-mode not fully implemented
// $GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$_EXTKEY]['dbMode'] = $_EXTCONF['dbMode'] ? true : false;
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$_EXTKEY]['dbMode'] = false;

if ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$_EXTKEY]['showContentCol'])	{
	t3lib_extMgm::addPageTSConfig('
mod.SHARED.colPos_list = 1,0,2,3,'.$GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$_EXTKEY]['colPos'].'
');
}

t3lib_extMgm::addPageTSConfig('
mod.wizards.newContentElement.wizardItems.special {
		show := addToList(kb_conttable)
}
');

// Loading hook-files and configuring hooks
require_once(t3lib_extMgm::extPath($_EXTKEY).'class.tx_t3lib_befunc.php');
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_befunc.php']['getFlexFormDSClass'][] = 'tx_t3lib_befunc_getFlexFormDS';
require_once(t3lib_extMgm::extPath($_EXTKEY).'class.tx_t3lib_tcemain.php');
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = 'tx_t3lib_tcemain_process_datamap';


t3lib_extMgm::addPItoST43($_EXTKEY,"pi1/class.tx_kbconttable_pi1.php","_pi1","CType",1);
?>
