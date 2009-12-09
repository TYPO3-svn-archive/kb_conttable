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
 * kb_conttable module tt_content_tx_kbconttable_flex_dswiz0
 *
 * $Id$
 *
 * @author	Bernhard Kraft <kraftb@think-open.at>
 */
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   43: class tx_kbconttable_layout extends tx_cms_layout
 *   52:     function renderText($input)
 *
 * TOTAL FUNCTIONS: 1
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_kbconttable_layout extends tx_cms_layout	{
	var $previewTextLen = 100;

	/**
	 * Processing of larger amounts of text (usually from RTE/bodytext fields) with word wrapping etc.
	 *
	 * @param	string		Input string
	 * @return	string		Output string
	 */
	function renderText($input)	{
		$input = strip_tags($input);
		$input = t3lib_div::fixed_lgd_cs($input,$this->previewTextLen);
		return nl2br(htmlspecialchars(trim($this->wordWrapper($input))));
	}

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/kb_conttable/class.tx_kbconttable_layout.php'])    {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/kb_conttable/class.tx_kbconttable_layout.php']);
}

?>
