<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2004-2012 Bernhard Kraft (kraftb@seicht.co.at)
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
 * @author	Bernhard Kraft <kraftb@seicht.co.at>
 */


unset($MCONF);
require ('conf.php');
require ($BACK_PATH.'init.php');

// 2012-07-12 | kraftb | Added the following line
// There was a change in t3lib_div so the names of XCLASSed classes get cached. As "template.php" directly creates an instance of the
// class defined in it, the class has to be already extended. The switch to execute the new code part is achieved by a global flag.
$GLOBALS['TYPO3_CONF_VARS']['BE']['XCLASS']['typo3/template.php'] = t3lib_extMgm::extPath('kb_conttable').'tt_content_tx_kbconttable_flex_ds/ux_template.php';

require ($BACK_PATH.'template.php');
require_once(t3lib_extMgm::extPath('kb_conttable').'class.tx_kbconttable_funcs.php');
	// Unset MCONF/MLANG since all we wanted was back path etc. for this particular script.
unset($MCONF);
unset($MLANG);




$scriptFile = t3lib_extMgm::extPath('cms').'layout/db_new_content_el.php';
$scriptData = t3lib_div::getURL($scriptFile);

$scriptData = preg_replace('/^require\(\'conf\.php\'\);\s*$/m', '', $scriptData);
$scriptData = preg_replace('/^require\(\$BACK_PATH\.\'init\.php\'\);\s*$/m', '', $scriptData);
$scriptData = preg_replace('/^require\(\$BACK_PATH\.\'template\.php\'\);\s*$/m', '', $scriptData);

$_GET['colPos'] = 10;
$GLOBALS['T3_VARS']['kb_conttable']['altTemplate'] = true;

t3lib_div::writeFile(PATH_site.'typo3temp/kbconttable_db_new_cont_el.php', $scriptData);
require_once(PATH_site.'typo3temp/kbconttable_db_new_cont_el.php');

?>
