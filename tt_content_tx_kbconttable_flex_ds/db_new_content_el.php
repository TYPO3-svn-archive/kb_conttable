<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2004-2009 Bernhard Kraft (kraftb@think-open.at)
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
 * New content element wizard for kb_conttable elements
 *
 * $Id$
 *
 * @author	Bernhard Kraft <kraftb@think-open.at>
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
