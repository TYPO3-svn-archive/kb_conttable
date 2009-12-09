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
 * Class for adding our new Content Element to the New Content Element wizard
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
 *   43: class tx_kbconttable_wizicon
 *   51:     function proc($wizardItems)
 *
 * TOTAL FUNCTIONS: 1
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_kbconttable_wizicon	{

	/**
	 * Process the list of Content Elements and replace Table element by Content Table element
	 *
	 * @param	array		wizard items
	 * @return	array		modified wizard items
	 */
	function proc($wizardItems)	{
		$wizardConf = array(
			'icon' => t3lib_extMgm::extRelPath('kb_conttable').'res/new_el_icon.gif',
			'title' => 'LLL:EXT:kb_conttable/locallang_db.xml:tt_content.CType_pi1',
			'description' => 'LLL:EXT:kb_conttable/locallang_db.xml:tt_content.CType_pi1.description',
			'params' => '&defVals[tt_content][CType]=kb_conttable_pi1',
			'tt_content_defValues.' => array(
				'CType' => 'kb_conttable_pi1',
			),
		);

		$wizardItems['common_kb_conttable'] = $wizardConf;

		return $wizardItems;
	}

}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/kb_conttable/class.tx_kbconttable_wizicon.php'])    {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/kb_conttable/class.tx_kbconttable_wizicon.php']);
}

?>
