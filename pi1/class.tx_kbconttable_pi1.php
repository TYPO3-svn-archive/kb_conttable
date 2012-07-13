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
 * Plugin 'Content Table' for the 'kb_conttable' extension.
 *
 * @author	Bernhard Kraft <kraftb@seicht.co.at>
 */


require_once(PATH_tslib."class.tslib_pibase.php");
require_once(t3lib_extMgm::extPath('kb_conttable').'class.tx_kbconttable_funcs.php');
class tx_kbconttable_pi1 extends tslib_pibase	{
	var $prefixId = "tx_kbconttable_pi1";		// Same as class name
	var $scriptRelPath = "pi1/class.tx_kbconttable_pi1.php";	// Path to this script relative to the extension dir.
	var $extKey = "kb_conttable";	// The extension key.

	/**
	 * Generated the Table
	 *
	 * @param	string		Content
	 * @param	array		Configuration
	 * @return	string		Content
	 */
	function main($content,$conf)	{
		$this->conf = $conf;
		$this->content = $content;

		$this->content = $this->renderElement($this->cObj->data, 'tt_content');

		return strlen($this->content)?$this->pi_wrapInBaseClass($this->content,$this->conf):'';
	}

	/**
	 * Renders the Table
	 *
	 * @param	array		content element DB row
	 * @param	string		Table
	 * @return	string		Content
	 */
	function renderElement($row,$table)	{
		$DS = t3lib_div::xml2array($row['tx_kbconttable_flex_ds']);
		$data = t3lib_div::xml2array($row['tx_kbconttable_flex']);
		if (!(is_array($DS)&&count($DS)&&is_array($data)&&count($data))) return '';

		$this->funcs = t3lib_div::makeInstance('tx_kbconttable_funcs');
		$this->funcs->init($this);

		$this->tableSettings = $this->funcs->getTableSettings($data);
		$this->RTEmode = intval($data['data']['sDEF']['lDEF']['rte_mode']['vDEF'])?1:0;
		$this->tableData = $this->funcs->getTableData($this->tableSettings, $data);

		list($err, $content) = $this->getTable();
		return $content;
	}


	/**
	 * Renders the Table by calling the iteration method
	 *
	 * @return	string		Content
	 */
	function getTable()	{
		$content = '';
		// Opening Table Tag ---- begin
		$content .= '<table cellspacing="'.intval($this->tableSettings['cellspacing']).
			'" cellpadding="'.intval($this->tableSettings['cellpadding']).
			'" border="'.intval($this->tableSettings['border']).'"'.
			($this->tableSettings['width']?' width="'.$this->tableSettings['width'].$this->tableSettings['width_format'].'"':'').
			($this->tableSettings['height']?' height="'.$this->tableSettings['height'].$this->tableSettings['height_format'].'"':'').
			(strlen($this->tableSettings['align'])?' align="'.$this->tableSettings['align'].'"':'').
			(strlen($this->tableSettings['class'])?' class="'.$this->tableSettings['class'].'"':'').
			(strlen($this->tableSettings['id'])?' id="'.$this->tableSettings['id'].'"':'').
			(strlen($this->tableSettings['style'])?' style="'.$this->tableSettings['style'].'"':'').
			(strlen($this->tableSettings['additional'])?' '.$this->tableSettings['additional']:'').'>';
		// Opening Table Tag ---- end
		// Table Rows ---- begin
		$params = Array(
			'rowData' => Array(),
		);
		list($row_error, $column_error, $error) = $this->funcs->iterateTableData($this->tableSettings, $this->tableData, $this, 'iter_getTable_rowBegin', 'iter_getTable_column', '', '', 'iter_getTable_rowEnd', $params);
		if ($row_error < 0)	{
			if ($column_error < 0)	{
				return Array(-1, $this->error($this->LANG->getLL('error_colrowspan_overlap')));
			}
			return Array(-1, $this->error('Error: Rowspan ranged below end of table.'));
		}
		if ($column_error < 0)	{
			return Array(-1, $this->error('Error: Row-end reached but column expected.'));
		}
		if (strlen($error))	{
			return Array(-1, $this->error($error));
		}
		$content .= implode('', $params['rowData']);
		// Table Rows ---- end
		// Closing Table Tag ---- begin
		$content .= '</table>';
		// Closing Table Tag ---- end
		return Array(0, $content);
	}


	/**
	 * Error handler. This method returns an error message formated for output
	 *
	 * @param	string		Error message
	 * @return	string		Formated output
	 */
	function error($message) {
		return '<div style="color: red; font-weight: bold; border: 2px solid yellow; margin: 10px; padding: 10px;">'.$message.'</div>';
	}


	/**
	 * Iteration method. Resets the ColumnData field in the params array
	 *
	 * @param	array		Parameters
	 * @return	string		Content
	 */
	function iter_getTable_rowBegin(&$params)	{
		$params['columnData'] = Array();
		return Array(0, 0, '');
	}


	/**
	 * Iteration method. Get data for a single cell
	 *
	 * @param	array		Parameters
	 * @return	string		Content
	 */
	function iter_getTable_column(&$params)	{
		$columnAr = $params['columnAr'];
		$ret = '<'.
			// Attributes
			(strlen($columnAr['celltype'])?$columnAr['celltype']:'td').
			((intval($columnAr['colspan'])>1)?' colspan="'.intval($columnAr['colspan']).'"':'').
			((intval($columnAr['rowspan'])>1)?' rowspan="'.intval($columnAr['rowspan']).'"':'').
			((intval($columnAr['cellwidth'])!=0)?' width="'.intval($columnAr['cellwidth']).$columnAr['cellwidth_format'].'"':'').
			((intval($columnAr['cellheight'])!=0)?' height="'.intval($columnAr['cellheight']).$columnAr['cellheight_format'].'"':'').
			(strlen($columnAr['align'])?' align="'.$columnAr['align'].'"':'').
			(strlen($columnAr['valign'])?' valign="'.$columnAr['valign'].'"':' valign="top"').
			(strlen($columnAr['wordwrap'])?' '.$columnAr['wordwrap']:'').
			(strlen($columnAr['backgroundcolor'])==6?' bgcolor="#'.$columnAr['backgroundcolor'].'"':'').
			(strlen($columnAr['additional'])?' '.$columnAr['additional']:'').
			//	Style
			(strlen($columnAr['fontweight'])||strlen($columnAr['fontfamily'])||
			intval($columnAr['fontsize'])||strlen($columnAr['style'])
			?' style="'.
			(strlen($columnAr['fontweight'])?' font-weight: '.$columnAr['fontweight'].';':'').
			(strlen($columnAr['fontfamily'])?' font-family: '.$columnAr['fontfamily'].';':'').
			(strlen($columnAr['color'])==6?' color: #'.$columnAr['color'].';':'').
			(intval($columnAr['fontsize'])?' font-size: '.intval($columnAr['fontsize']).(strlen($columnAr['fontsize_format'])?$columnAr['fontsize_format']:'px').';':'').
			(strlen($columnAr['style'])?' '.str_replace(chr(10), ' ', $columnAr['style']).';':'').'"'
			:'').
			// Class and Id
			(strlen($columnAr['class'])?' class="'.$columnAr['class'].'"':'').
			(strlen($columnAr['id'])?' id="'.$columnAr['id'].'"':'').
	'>';

		// TS Parsing for every single cell

		if ($this->RTEmode)	{
			$cont = $this->pi_RTEcssText($columnAr['rte_content']);
		} else	{
			$cObj =t3lib_div::makeInstance('tslib_cObj');
			$cObj->setParent($this->cObj->data,$this->cObj->currentRecord);
			$cObj->start(Array('row' => $params['row'], 'col' => $params['col']),'_NO_TABLE');

			$cObj->setCurrentVal($columnAr['elements']);

			// Overtake the complete Setup (pherhaps this should get minimized)
			$setup = $GLOBALS['TSFE']->tmpl->setup;
			$setup['10'] = 'RECORDS';
			$setup['10.'] = Array(
				'source.' => Array(
					'current' => 1,
				),
				'tables' => 'tt_content',
			);
			$cont = $cObj->cObjGet($setup);
		}
		if ($columnAr['visible']&&!$columnAr['hidden'])	{
			$ret .= strlen($cont)?$cont:'&nbsp;';
			$ret .= '</'.(strlen($columnAr['celltype'])?$columnAr['celltype']:'td').'>';
			$params['columnData'][] = $ret;
		} elseif (!$columnAr['hidden'])	{
			$ret .= '&nbsp;';
			$ret .= '</'.(strlen($columnAr['celltype'])?$columnAr['celltype']:'td').'>';
			$params['columnData'][] = $ret;
		}
		return Array(0, 0, '');
	}

	/**
	 * Iteration method. Get data for the end of a row. Compile row
	 *
	 * @param	array		Parameters
	 * @return	string		Content
	 */
	function iter_getTable_rowEnd(&$params)	{
		if ($params['rowAr']['visible'])	{
			$params['rowData'][] = '<tr>'.implode('', $params['columnData']).'</tr>';
		}
		return Array(0, 0, '');
	}

}



if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/kb_conttable/pi1/class.tx_kbconttable_pi1.php"])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/kb_conttable/pi1/class.tx_kbconttable_pi1.php"]);
}

?>
