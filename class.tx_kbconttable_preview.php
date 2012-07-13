<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2009-2012 Bernhard Kraft (kraftb@seicht.co.at)
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
 * Render the preview for a content element (show wizard link)
 *
 * $Id$
 *
 * @author	Bernhard Kraft <kraftb@seicht.co.at>
 */
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   48: class tx_kbconttable_preview implements tx_cms_layout_tt_content_drawItemHook
 *   60:     public function preProcess(tx_cms_layout &$parentObject, &$drawItem, &$headerContent, &$itemContent, array &$row)
 *
 * TOTAL FUNCTIONS: 1
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */



require_once(t3lib_extMgm::extPath('cms').'layout/interfaces/interface.tx_cms_layout_tt_content_drawitemhook.php');

class tx_kbconttable_preview implements tx_cms_layout_tt_content_drawItemHook {

	/**
	 * Preprocesses the preview rendering of a content element.
	 *
	 * @param	tx_cms_layout		$parentObject: Calling parent object
	 * @param	boolean		$drawItem: Whether to draw the item using the default functionalities
	 * @param	string		$headerContent: Header content
	 * @param	string		$itemContent: Item content
	 * @param	array		$row: Record row of tt_content
	 * @return	void
	 */
	public function preProcess(tx_cms_layout &$parentObject, &$drawItem, &$headerContent, &$itemContent, array &$row) {
		if ($row['CType'] == 'kb_conttable_pi1') {
			$wizardLink = $parentObject->backPath.t3lib_extMgm::extRelPath('kb_conttable').'tt_content_tx_kbconttable_flex_ds/index.php?P[table]=tt_content&P[uid]='.$row['uid'].'&P[pid]='.$row['pid'].'&P[field]=tx_kbconttable_flex_ds&P[returnUrl]='.rawurlencode(t3lib_div::linkThisScript());
			$wizardImg = $parentObject->backPath.t3lib_extMgm::extRelPath('kb_conttable').'tt_content_tx_kbconttable_flex_ds/wizard_icon.gif';
			$wizardLabel = $GLOBALS['LANG']->sL('LLL:EXT:kb_conttable/locallang_db.xml:tt_content.tx_kbconttable_flex_ds');
			$itemContent .= '<a href="'.$wizardLink.'"><img src="'.$wizardImg.'" alt="'.$wizardLabel.'" title="'.$wizardLabel.'"></a>';
		}
	}

}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/kb_conttable/class.tx_kbconttable_preview.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/kb_conttable/class.tx_kbconttable_preview.php']);
}

?>
