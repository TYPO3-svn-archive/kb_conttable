<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2012 Bernhard Kraft (kraftb@seicht.co.at)
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
 * Extending the class "template" for special handling when a kb_conttable "add new content element" link gets clicked
 *
 * $Id$
 *
 * @author	Bernhard Kraft <kraftb@seicht.co.at>
 */


class ux_template extends template {
	function startPage($title) {
		if ($GLOBALS['T3_VARS']['kb_conttable']['altTemplate']) {
			$this->id = $_GET['id'];
			$this->P = $_GET['P'];
			$this->funcs = t3lib_div::makeInstance('tx_kbconttable_funcs');
			$this->funcs->init($this);
			$this->parentRecord = t3lib_div::_GP('parentRecord');

			$js = '

function goToalt_doc() {
	document.location.href = \'index.php?'.$this->funcs->linkParams().'&kbconttable[funcs][createNewRecord]='.rawurlencode($this->parentRecord).'\'+document.editForm.defValues.value;
	return false;
}

';

// $newRecordLink = 'index.php?'.$this->funcs->linkParams().'&kbconttable[funcs][createNewRecord]='.rawurlencode($this->parentRecord).$wizardItem['params'];

			$this->JScode = $this->wrapScriptTags($js);
		}
		return parent::startPage($title);
	}
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/kb_conttable/tt_content_tx_kbconttable_flex_ds/ux_template.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/kb_conttable/tt_content_tx_kbconttable_flex_ds/ux_template.php']);
}
?>
