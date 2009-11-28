<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2003-2004 Robert Lemke (robert@typo3.org)
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
*  A copy is found in the textfile GPL.txt and important notices to the license
*  from the author is found in LICENSE.txt distributed with these scripts.
*
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * New content elements wizard for templavoila
 *
 * $Id: db_new_content_el.php,v 1.1.1.1 2005/04/13 17:37:43 kraftb Exp $
 * Originally based on the CE wizard / cms extension by Kasper Skaarhoj <kasper@typo3.com>
 * XHTML compatible.
 *
 * @author		Robert Lemke <robert@typo3.org>
 * @coauthor	Kasper Skaarhoj <kasper@typo3.com>
 */
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *  100: class tx_templavoila_posMap extends t3lib_positionMap
 *  110:     function wrapRecordTitle($str,$row)
 *  124:     function onClickInsertRecord($row,$vv,$moveUid,$pid,$sys_lang=0)
 *
 *
 *  152: class tx_templavoila_dbnewcontentel
 *  175:     function init()
 *  211:     function main()
 *  355:     function printContent()
 *
 *              SECTION: OTHER FUNCTIONS:
 *  384:     function getWizardItems()
 *  394:     function wizardArray()
 *
 * TOTAL FUNCTIONS: 7
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */



unset($MCONF);
require ('conf.php');
require ($BACK_PATH.'init.php');
require ($BACK_PATH.'template.php');

require_once(t3lib_extMgm::extPath('kb_conttable').'class.tx_kbconttable_funcs.php');

	// Unset MCONF/MLANG since all we wanted was back path etc. for this particular script.
unset($MCONF);
unset($MLANG);

class ux_template extends template {
	function startPage($title) {
		$this->id = $_GET['id'];
		$this->P = $_GET['P'];
		$this->funcs = t3lib_div::makeInstance('tx_kbconttable_funcs');
		$this->funcs->init($this);
		$this->parentRecord = t3lib_div::GPvar('parentRecord');

		$js = '

function goToalt_doc() {
	document.location.href = \'index.php?'.$this->funcs->linkParams().'&kbconttable[funcs][createNewRecord]='.rawurlencode($this->parentRecord).'\'+document.editForm.defValues.value;
	return false;
}

';

// $newRecordLink = 'index.php?'.$this->funcs->linkParams().'&kbconttable[funcs][createNewRecord]='.rawurlencode($this->parentRecord).$wizardItem['params'];

		$this->JScode = $this->wrapScriptTags($js);
		return parent::startPage($title);
	}
}

$scriptFile = t3lib_extMgm::extPath('cms').'layout/db_new_content_el.php';
$scriptData = t3lib_div::getURL($scriptFile);

$scriptData = preg_replace('/^require\(\'conf\.php\'\);\s*$/m', '', $scriptData);
$scriptData = preg_replace('/^require\(\$BACK_PATH\.\'init\.php\'\);\s*$/m', '', $scriptData);
$scriptData = preg_replace('/^require\(\$BACK_PATH\.\'template\.php\'\);\s*$/m', '', $scriptData);

$_GET['colPos'] = 10;

t3lib_div::writeFile(PATH_site.'typo3temp/kbconttable_db_new_cont_el.php', $scriptData);
require_once(PATH_site.'typo3temp/kbconttable_db_new_cont_el.php');

?>
