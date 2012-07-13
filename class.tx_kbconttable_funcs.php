<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2004-2012 Bernhard Kraft (kraftb@seicht.co.at)
*  All rights reserved
*  Code used from:
*  (c) 2003, 2004 Kasper Skaarhoj (kasper@typo3.com)
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
 * @author	Bernhard Kraft <kraftb@seicht.co.at>
 */
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   68: class tx_kbconttable_funcs
 *  100:     function init(&$parent)
 *  115:     function linkParams()
 *  129:     function getTableSettings($flexData)
 *  159:     function getTableData($tableSettings, $flexData)
 *  353:     function iterateTableData($tableSettings, $tableData, $funcObj, $func_rowBegin, $func_column, $func_rowspan, $func_columnspan, $func_rowEnd, &$params, $debug = false)
 *  524:     function splitConfArray($conf,$splitCount)
 *  601:     function setDataFields_byDS($flexDS, $flexData)
 *
 *              SECTION: XML Generation
 *  630:     function defaultCellDS()
 *  644:     function defaultCellDS_fast()
 *  657:     function defaultCellDS_normal()
 *  670:     function defaultCellDS_rte()
 *  686:     function getDefaultTable_CellDS($row, $col)
 *  711:     function getDefaultTable_RowDS($row, $columns)
 *  737:     function getDefaultTable_DataDS($xmlArray, &$flexData)
 *  771:     function &columnLabel(&$ar, $col)
 *  789:     function defaultFlexDS()
 *
 *              SECTION: Storage Folders and Templates
 *  808:     function findingStorageFolderIds()
 *  842:     function getStorageFolders()
 *  862:     function getExistingTemplates($folders)
 *
 * TOTAL FUNCTIONS: 19
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_kbconttable_funcs	{
	var $fastMode_baseArr = array(
		'colspan' => 1,
		'rowspan' => 1,
		'cellwidth' => 0,
		'cellheight' => 0,
		'fontsize' => 0,

		'celltype' => '',
		'wordwrap' => '',
		'cellwidth_format' => '',
		'cellheight_format' => '',
		'align' => '',
		'valign' => '',
		'fontsize_format' => '',
		'fontweight' => '',

		'backgroundcolor' => '',
		'color' => '',
		'class' => '',
		'id' => '',
		'fontfamily' => '',
		'style' => '',
		'additional' => '',
	);

	/**
	 * Initialize function library
	 *
	 * @param	object		Parent object
	 * @return	void
	 */
	function init(&$parent)	{
		$this->id = &$parent->id;
		$this->P = &$parent->P;
//		$scriptName = t3lib_div::getIndpEnv('SCRIPT_NAME');
//		$this->P['returnUrl'] = t3lib_div::getIndpEnv('TYPO3_SITE_PATH').'typo3/alt_doc.php?edit[tt_content]['.$this->P['uid'].']=edit';
//		$this->P['returnUrl'] = t3lib_div::linkThisScript(array('P' => $this->P));;
		$this->tableSettings = &$parent->tableSettings;
		$this->parent = &$parent;
	}

	/**
	 * Creates additional parameters which are used for linking to the current page while editing it
	 *
	 * @return	string		parameters
	 */
	function linkParams()	{
		$out = 'id='.$this->P['pid'];
		foreach ($this->P as $key => $value) {
			$out .= '&P['.$key.']='.rawurlencode($value);
		}
		return $out;
	}

	/**
	 * Returns the tableSettings array
	 *
	 * @param	array		Flexform data
	 * @return	array		tableSettings array
	 */
	function getTableSettings($flexData)	{
		// Store table style in local variables ---- begin
		$tableSettings = array();
		$tableSettings['cellpadding'] = intval($flexData['data']['sDEF']['lDEF']['cellpadding']['vDEF']);
		$tableSettings['cellspacing'] = intval($flexData['data']['sDEF']['lDEF']['cellspacing']['vDEF']);
		$tableSettings['border'] = intval($flexData['data']['sDEF']['lDEF']['border']['vDEF']);
		$tableSettings['width'] = intval($flexData['data']['sDEF']['lDEF']['width']['vDEF']);
		$tableSettings['width_format'] = $flexData['data']['sDEF']['lDEF']['width_format']['vDEF'];
		$tableSettings['height'] = intval($flexData['data']['sDEF']['lDEF']['height']['vDEF']);
		$tableSettings['height_format'] = $flexData['data']['sDEF']['lDEF']['height_format']['vDEF'];
		$tableSettings['align'] = $flexData['data']['sDEF']['lDEF']['align']['vDEF'];
		$tableSettings['style'] = $flexData['data']['sDEF']['lDEF']['style']['vDEF'];
		$tableSettings['class'] = $flexData['data']['sDEF']['lDEF']['class']['vDEF'];
		$tableSettings['id'] = $flexData['data']['sDEF']['lDEF']['id']['vDEF'];
		$tableSettings['additional'] = $flexData['data']['sDEF']['lDEF']['additional']['vDEF'];
		$tableSettings['rows'] = $flexData['data']['sDEF']['lDEF']['rows']['vDEF'];
		$tableSettings['visible_rows'] = $flexData['data']['sDEF']['lDEF']['visible_rows']['vDEF'];
		$tableSettings['hidden_columns'] = $flexData['data']['sDEF']['lDEF']['hidden_columns']['vDEF'];
		return $tableSettings;
		// Store table style in local variables ---- end
	}


	/**
	 * Returns the internaly used tableData array with all the locking properties (keys) from the flexData XML array
	 *
	 * @param	array		Table settings
	 * @param	array		Flexform data
	 * @return	array		tableData array
	 */
	function getTableData($tableSettings, $flexData)	{
		$rows = t3lib_div::trimExplode(',', $tableSettings['rows'], 1);
		$visible_rows = t3lib_div::trimExplode(',', $tableSettings['visible_rows'], 1);
		$hidden_columns = t3lib_div::trimExplode(',', $tableSettings['hidden_columns'], 1);
		$table_data = array();
		if (count($rows))	{
			$colsOld = 0;
			foreach ($rows as $row)	{
				$cols = 0;
				$rowData = $flexData['data']['s_row_'.$row]['lDEF'];
				$columns = t3lib_div::trimExplode(',', $rowData['columns']['vDEF'], 1);
				$visible_columns = t3lib_div::trimExplode(',', $rowData['visible_columns']['vDEF'], 1);
				$table_data[$row] = Array();
				$table_data[$row]['visible'] = in_array($row, $visible_rows)?true:false;
				$col = 1;
					// Current algorithm of this loop: Loops over all defined columns of a row and in each
					// loop it loops through ALL fields of the XML row/sheet and finds the fields for this
					// column.
					// Better alogrithm: loop once through the fields in the XML row/sheet and set the accordingly
					// field in the table_data array. Increases speed by n=columns
				foreach($columns as $column)	{
					$table_data[$row][$column] = Array();
					$cols++;
					$table_data[$row][$column]['visible'] = in_array($column, $visible_columns)?true:false;
					$table_data[$row][$column]['hidden'] = in_array($column, $hidden_columns)?true:false;
					foreach ($rowData as $key => $ar)	{
						if (!strcmp(trim($key),'columns')) continue;
						if (!strcmp(trim($key),'visible_columns')) continue;
						$parts = explode('_', $key, 4);
						if (($parts[0]!='column')||($parts[1]!=$row)||($parts[2]!=$column)) continue;
						$field = $parts[3];
						if ($field==='fastprops')	{
							$fArr = unserialize($ar['vDEF']);
							if (is_array($fArr)&&count($fArr))	{
								$fArr = array_merge($this->fastMode_baseArr, $fArr);
							} else {
								$fArr = $this->fastMode_baseArr;
							}
							$table_data[$row][$column] = array_merge($table_data[$row][$column], $fArr);
							$table_data[$row][$column]['fastprops'] = $fArr;
						} else {
							$table_data[$row][$column][$field] = $ar['vDEF'];
							if ($this->parent->fastMode)	{
								$table_data[$row][$column]['fastprops'][$field] = $ar['vDEF'];
							}
						}
					}
					$col++;
				}
				if (!$colsOld)	{
					$colsOld = $cols;
				} else	{
					if ($colsOld != $cols)	{
						return false;
					}
				}
			}
			$conf = Array();
			$conf['move_column_left'] = $flexData['data']['action_locking']['lDEF']['move_column_left']['vDEF'];
			$conf['move_column_right'] = $flexData['data']['action_locking']['lDEF']['move_column_right']['vDEF'];
			$conf['delete_column'] = $flexData['data']['action_locking']['lDEF']['delete_column']['vDEF'];
			$conf['hide_column'] = $flexData['data']['action_locking']['lDEF']['hide_column']['vDEF'];
			$conf['insert_column_before'] = $flexData['data']['action_locking']['lDEF']['insert_column_before']['vDEF'];
			$conf['insert_column_after'] = $flexData['data']['action_locking']['lDEF']['insert_column_after']['vDEF'];
			$columns_splitted = $this->splitConfArray($conf, $colsOld);
			$conf = Array();
			$conf['move_row_up'] = $flexData['data']['action_locking']['lDEF']['move_row_up']['vDEF'];
			$conf['move_row_down'] = $flexData['data']['action_locking']['lDEF']['move_row_down']['vDEF'];
			$conf['delete_row'] = $flexData['data']['action_locking']['lDEF']['delete_row']['vDEF'];
			$conf['hide_row'] = $flexData['data']['action_locking']['lDEF']['hide_row']['vDEF'];
			$conf['insert_row_before'] = $flexData['data']['action_locking']['lDEF']['insert_row_before']['vDEF'];
			$conf['insert_row_after'] = $flexData['data']['action_locking']['lDEF']['insert_row_after']['vDEF'];
			$rows_splitted = $this->splitConfArray($conf, count($rows));
			$conf = Array();
			$conf['hide_cell'] = $flexData['data']['action_locking']['lDEF']['hide_cell']['vDEF'];
			$conf['move_left_cell'] = $flexData['data']['action_locking']['lDEF']['move_left_cell']['vDEF'];
			$conf['move_up_cell'] = $flexData['data']['action_locking']['lDEF']['move_up_cell']['vDEF'];
			$conf['move_right_cell'] = $flexData['data']['action_locking']['lDEF']['move_right_cell']['vDEF'];
			$conf['move_down_cell'] = $flexData['data']['action_locking']['lDEF']['move_down_cell']['vDEF'];
			if (is_array($flexData['data']['cell_locking']['lDEF']))	{
				foreach ($flexData['data']['cell_locking']['lDEF'] as $field => $fieldAr)	{
					$conf[$field] = $flexData['data']['cell_locking']['lDEF'][$field]['vDEF'];
				}
			}
			$props_splitted = $this->splitConfArray($conf, count($rows));
			$conf = Array();
			foreach ($props_splitted as $tmp_row => $elementsAr)	{
				foreach($elementsAr as $name => $val)	{
					$val = str_replace('~*~', '|*|', $val);
					$val = str_replace('~~', '||', $val);
					$conf[$tmp_row][$name] = $val;
				}
			}
			$props_splitted = $this->splitConfArray($conf, $colsOld);
			$rows = 0;
			$new_table_data = $table_data;
			foreach ($table_data as $tmp_row => $rowAr)	{
				$cols = 0;
				foreach ($rowAr as $col => $colAr)	{
					if ($col=='visible') continue;
					$new_table_data[$tmp_row][$col]['lock_props'] = strlen(trim($props_splitted[$cols][$rows]['props']))?intval($props_splitted[$cols][$rows]['props']):0;
					$new_table_data[$tmp_row][$col]['lock_content'] = strlen(trim($props_splitted[$cols][$rows]['content']))?intval($props_splitted[$cols][$rows]['content']):0;
					$new_table_data[$tmp_row][$col]['lockaction_hide_cell'] = intval($props_splitted[$cols][$rows]['hide_cell'])?1:0;
					$new_table_data[$tmp_row][$col]['lockaction_move_left_cell'] = intval($props_splitted[$cols][$rows]['move_left_cell'])?1:0;
					$new_table_data[$tmp_row][$col]['lockaction_move_up_cell'] = intval($props_splitted[$cols][$rows]['move_up_cell'])?1:0;
					$new_table_data[$tmp_row][$col]['lockaction_move_right_cell'] = intval($props_splitted[$cols][$rows]['move_right_cell'])?1:0;
					$new_table_data[$tmp_row][$col]['lockaction_move_down_cell'] = intval($props_splitted[$cols][$rows]['move_down_cell'])?1:0;
					foreach ($colAr as $element => $elementAr)	{
						if (strpos($element, 'lock_')===0) continue;
						switch ($element)	{
								// Integer values
							case 'colspan':
							case 'rowspan':
							case 'cellwidth':
							case 'cellheight':
							case 'fontsize':
								$new_table_data[$tmp_row][$col]['lock_'.$element] = t3lib_div::testInt(trim($props_splitted[$cols][$rows][$element]))?intval($props_splitted[$cols][$rows][$element]):-1;
								if ($new_table_data[$tmp_row][$col]['lock_'.$element]!=-1)	{
									$new_table_data[$tmp_row][$col][$element] = intval($props_splitted[$cols][$rows][$element]);
								}
							break;
							case 'celltype':
							case 'wordwrap':
							case 'cellwidth_format':
							case 'cellheight_format':
							case 'align':
							case 'valign':
							case 'fontsize_format':
							case 'fontweight':
								$new_table_data[$tmp_row][$col]['lock_'.$element] = (intval(trim($props_splitted[$cols][$rows][$element]))<0)?-1:trim($props_splitted[$cols][$rows][$element]);
								if ($new_table_data[$tmp_row][$col]['lock_'.$element]!=-1)	{
									$new_table_data[$tmp_row][$col][$element] = trim($props_splitted[$cols][$rows][$element]);
								}
							break;
							case 'backgroundcolor':
							case 'color':
							case 'class':
							case 'id':
							case 'fontfamily':
							case 'style':
							case 'additional':
								$new_table_data[$tmp_row][$col]['lock_'.$element] = strlen(trim($props_splitted[$cols][$rows][$element]))?trim($props_splitted[$cols][$rows][$element]):-1;
								if ($new_table_data[$tmp_row][$col]['lock_'.$element]!=-1)	{
									$new_table_data[$tmp_row][$col][$element] = trim($props_splitted[$cols][$rows][$element]);
								}
							break;
							case 'visible':
							case 'hidden':
							case 'elements':
							case 'rte_content':
							case 'fastprops':
							break;
							default:
								echo 'Invalid key "'.$element.'" in locking sheet !<br>\n';
							break;
						}
					}
					if (!$rows)	{
						$new_table_data['columns'][$cols]['lockaction_move_left']  = intval($columns_splitted[$cols]['move_column_left']);
						$new_table_data['columns'][$cols]['lockaction_move_right']  = intval($columns_splitted[$cols]['move_column_right']);
						$new_table_data['columns'][$cols]['lockaction_delete']  = intval($columns_splitted[$cols]['delete_column']);
						$new_table_data['columns'][$cols]['lockaction_hide']  = intval($columns_splitted[$cols]['hide_column']);
						$new_table_data['columns'][$cols]['lockaction_insert_before']  = intval($columns_splitted[$cols]['insert_column_before']);
						$new_table_data['columns'][$cols]['lockaction_insert_after']  = intval($columns_splitted[$cols]['insert_column_after']);
					}
					$cols++;
				}
				$new_table_data[$tmp_row]['lockaction_move_up'] = intval($rows_splitted[$rows]['move_row_up']);
				$new_table_data[$tmp_row]['lockaction_move_down'] = intval($rows_splitted[$rows]['move_row_down']);
				$new_table_data[$tmp_row]['lockaction_delete'] = intval($rows_splitted[$rows]['delete_row']);
				$new_table_data[$tmp_row]['lockaction_hide'] = intval($rows_splitted[$rows]['hide_row']);
				$new_table_data[$tmp_row]['lockaction_insert_before'] = intval($rows_splitted[$rows]['insert_row_before']);
				$new_table_data[$tmp_row]['lockaction_insert_after'] = intval($rows_splitted[$rows]['insert_row_after']);
				$rows++;
			}
		}
		return $new_table_data;
	}

	/**
	 * Iterates over the $tableData array (all rows and columns) and executes a method of $funcObj for each encountered cell
	 *
	 * @param	array		Table settings
	 * @param	array		Table data
	 * @param	object		Function object
	 * @param	string		Method reference to the method which gets called when a row begins
	 * @param	string		Method reference to the method which gets called when a column is encountered
	 * @param	string		Method reference to the method which gets called when a rowspaned column is encountered
	 * @param	string		Method reference to the method which gets called when a colspaned column is encountered
	 * @param	string		Method reference to the method which gets called when a row ends
	 * @param	array		Parmater array. Can get used to pass parameters to the methods and gets filled with parameters defining the actual position in the table and containing the contents of each actual cell
	 * @param	[type]		$debug: ...
	 * @return	array		Array containing (rows, columns iteraded, error string)
	 */
	function iterateTableData($tableSettings, $tableData, $funcObj, $func_rowBegin, $func_column, $func_rowspan, $func_columnspan, $func_rowEnd, &$params, $debug = false) {
		$params['hiddenColumns'] = t3lib_div::trimExplode(',', $tableSettings['hidden_columns'], 1);
		$params['rowSpanAr'] = Array();
		$params['rowsMax'] = count($tableData);
		$params['rows'] = 0;
		if (!count($tableData)) {
			return Array(-1, 0, 'Table without rows');
		}
		foreach ($tableData as $row => $rowAr) {
			$params['row'] = $row;
			$params['rowAr'] = $rowAr;
			if ($row=='columns') continue;
			if (method_exists($funcObj, $func_rowBegin)) {
				list($row, $col, $error) = $funcObj->$func_rowBegin($params);
				if (strlen($error)) {
					return Array(0, 0, $error);
				}
			}
			$params['cols'] = 0;
			if ($params['rows']) {
				if ($debug) {
					echo "Has rows!";
					exit();
				}
				// We have to traverse the rowSpan Array
				$params['columnAr'] = reset($params['rowAr']);
				if (key($params['rowAr'])==='visible') {
					while (key($params['rowAr'])==='visible')	{
						$params['columnAr'] = next($params['rowAr']);
					}
				}
				$params['col'] = key($params['rowAr']);
				$colSpan = 0;
				foreach ($params['rowSpanAr'] as $key => $rowSpan) {
					$params['rowSpan'] = $rowSpan;
					if ($params['rowSpan'])	{
						if ($colSpan)	{
							return Array(-1, -1, 'Col-/Row-span overlap');		// Col-/Row-span overlap
						}
						if ($params['columnAr'])	{
							if (method_exists($funcObj, $func_rowspan))	{
								list($row, $col, $error) = $funcObj->$func_rowspan($params);
								if (strlen($error))	{
									return Array(0, 0, $error);
								}
							}
							$params['cols']++;
							$params['rowSpanAr'][$key]--;
							$params['columnAr'] = next($params['rowAr']);
							if (key($params['rowAr'])==='visible')	{
								while (key($params['rowAr'])==='visible')	{
									$params['columnAr'] = next($params['rowAr']);
								}
							}
							$params['col'] = key($params['rowAr']);
						} else	{
							return array(0, -1, 'No columns');	// Column error
						}
						// row-span ---- begin
						// row-span ---- end
					} else	{
						// Real column (no rowspan) ---- begin
						if ($colSpan)	{
							if ($params['columnAr'])	{
								if (method_exists($funcObj, $func_columnspan))	{
									list($row, $col, $error) = $funcObj->$func_columnspan($params);
									if (strlen($error))	{
										return Array(0, 0, $error);
									}
								}
								$params['rowSpanAr'][$key] = $continuedRowSpan;
								$colSpan--;
								$params['columnAr'] = next($params['rowAr']);
								if (key($params['rowAr'])==='visible')	{
									while (key($params['rowAr'])==='visible')	{
										$params['columnAr'] = next($params['rowAr']);
									}
								}
								$params['col'] = key($params['rowAr']);
							} else	{
								return array(0, -1, 'No columns');	// Column error
							}
						} else {
							if ($params['columnAr'])	{
								if (method_exists($funcObj, $func_column))	{
									list($row, $col, $error) = $funcObj->$func_column($params);
									if (strlen($error))	{
										return Array(0, 0, $error);
									}
								}
								$colSpan = (intval($params['columnAr']['colspan'])>1?intval($params['columnAr']['colspan']):1)-1;
								$params['rowSpanAr'][$key] = $continuedRowSpan = (intval($params['columnAr']['rowspan'])>1?intval($params['columnAr']['rowspan']):1)-1;
								$params['columnAr'] = next($params['rowAr']);
								if (key($params['rowAr'])==='visible')	{
									while (key($params['rowAr'])==='visible') {
										$params['columnAr'] = next($params['rowAr']);
									}
								}
								$params['col'] = key($params['rowAr']);
							} else	{
								return array(0, -1, 'No columns');	// Column error
							}
						}
						$params['cols']++;
						// Real column (no rowspan) ---- end
					}
				}
			} else	{ // if ($params['rows']) {
				// We traverse the Columns of the first row - so we create the rowspan array
				if ($debug) {
					echo "No rows!";
					exit();
				}
				$continuedColSpan = 0;
				$continuedRowSpan = 0;
				foreach ($params['rowAr'] as $column => $columnAr)	{
					if (!strcmp($column, 'visible')) continue;	// Skip the row-visible-property
					if (strpos($column, 'lockaction_')===0) continue;	// Skip the row-visible-property
					$params['col'] = $column;
					$params['columnAr'] = $columnAr;
					$colSpan = intval($params['columnAr']['colspan'])>1?intval($params['columnAr']['colspan']):1;
					if (!$continuedColSpan)	{
						$continuedColSpan = $colSpan-1;
						if (method_exists($funcObj, $func_column))	{
							list($row, $col, $error) = $funcObj->$func_column($params);
							if (strlen($error))	{
								return Array(0, 0, $error);
							}
						}
						$params['cols']++;
						$params['rowSpanAr'][$params['cols']] = $continuedRowSpan = (intval($params['columnAr']['rowspan'])>1?intval($params['columnAr']['rowspan']):1)-1;
					} else	{
						$continuedColSpan--;
						if (method_exists($funcObj, $func_columnspan))	{
							list($row, $col, $error) = $funcObj->$func_columnspan($params);
							if (strlen($error))	{
								return Array(0, 0, $error);
							}
						}
						$params['cols']++;
						$params['rowSpanAr'][$params['cols']] = $continuedRowSpan;
					}
				}
			} // if ($params['rows']) {
			if (method_exists($funcObj, $func_rowEnd))	{
				list($row, $col, $error) = $funcObj->$func_rowEnd($params);
				if (strlen($error))	{
					return Array(0, 0, $error);
				}
			}
			$params['rows']++;
		}
		foreach ($params['rowSpanAr'] as $key => $rowSpan)	{
			if ($rowSpan)	{
				return Array(-1, 0, 'No rows'); // Row error
			}
		}
		return Array($params['rows'], $params['cols'], '');	// OK
	}

	/**
	 * Implementation of the "optionSplit" feature in TypoScript (used eg. for MENU objects)
	 * What it does is to split the incoming TypoScript array so that the values are exploded by certain strings ("||" and "|*|") and each part distributed into individual TypoScript arrays with a similar structure, but individualized values.
	 * The concept is known as "optionSplit" and is rather advanced to handle but quite powerful, in particular for creating menus in TYPO3.
	 *
	 * @param	array		A TypoScript array
	 * @param	integer		The number of items for which to generated individual TypoScript arrays
	 * @return	array		The individualized TypoScript array.
	 * @see tslib_cObj::IMGTEXT(), tslib_menu::procesItemStates()
	 * @link http://typo3.org/doc.0.html?&tx_extrepmgm_pi1[extUid]=270&tx_extrepmgm_pi1[tocEl]=289&cHash=6604390b37
	 */
	function splitConfArray($conf,$splitCount)	{

			// Initialize variables:
		$splitCount = intval($splitCount);
		$conf2 = Array();

		if ($splitCount && is_array($conf))	{

				// Initialize output to carry at least the keys:
			for ($aKey=0;$aKey<$splitCount;$aKey++)	{
				$conf2[$aKey] = array();
			}

				// Recursive processing of array keys:
			foreach($conf as $cKey => $val)	{
				if (is_array($val))	{
					$tempConf = $this->splitConfArray($val,$splitCount);
					foreach($tempConf as $aKey => $val)	{
						$conf2[$aKey][$cKey] = $val;
					}
				}
			}

				// Splitting of all values on this level of the TypoScript object tree:
			foreach($conf as $cKey => $val)	{
				if (!is_array($val))	{
					if (!strstr($val,'|*|') && !strstr($val,'||'))	{
						for ($aKey=0;$aKey<$splitCount;$aKey++)	{
							$conf2[$aKey][$cKey] = $val;
						}
					} else	{
						$main = explode ('|*|',$val);
						$mainCount = count($main);

						$lastC = 0;
						$middleC = 0;
						$firstC = 0;

						if ($main[0])	{
							$first = explode('||',$main[0]);
							$firstC = count($first);
						}
						if ($main[1])	{
							$middle = explode('||',$main[1]);
							$middleC = count($middle);
						}
						if ($main[2])	{
							$last = explode('||',$main[2]);
							$lastC = count($last);
							$value = $last[0];
						}

						for ($aKey=0;$aKey<$splitCount;$aKey++)	{
							if ($firstC && isset($first[$aKey]))	{
								$value = $first[$aKey];
							} elseif ($middleC)	{
								$value = $middle[($aKey-$firstC)%$middleC];
							}
							if ($lastC && $lastC>=($splitCount-$aKey))	{
								$value = $last[$lastC-($splitCount-$aKey)];
							}
							$conf2[$aKey][$cKey] = trim($value);
						}
					}
				}
			}
		}
		return $conf2;
	}

	/**
	 * Sets all set datafields from the DS in the Flexform data array if they are not set (To their default)
	 *
	 * @param	array		Flexform DS
	 * @param	array		Flexform data
	 * @return	array		new Flexform data
	 */
	function setDataFields_byDS($flexDS, $flexData)	{
		$new_flexData = $flexData;
		foreach ($flexDS['sheets'] as $sheet => $sheet_ar)	{
			foreach ($sheet_ar['ROOT']['el'] as $element => $el_ar)	{
				if (!isset($flexData['data'][$sheet]['lDEF'][$element]['vDEF']))	{
					if ($el_ar['TCEforms']['config']['default'])	{
						$new_flexData['data'][$sheet]['lDEF'][$element]['vDEF'] = $el_ar['TCEforms']['config']['default'];
					} else	{
						$new_flexData['data'][$sheet]['lDEF'][$element]['vDEF'] = '';
					}
				}
			}
		}
		return $new_flexData;
	}

	/****************************************
	 *
	 * XML Generation
	 *
	 * This methods are used for generateing XML structures/PHP arrays
	 *
	 ****************************************/

	/**
	 * Returns the default Flex DS XML for a single cell
	 *
	 * @return	array		Cell Flexform DS XML
	 */
	function defaultCellDS()	{
		if (!strlen($this->defaultCellDS))	{
			$file = t3lib_div::getFileAbsFileName('EXT:kb_conttable/res/singlecell_flex_ds.xml');
			$this->defaultCellDS = t3lib_div::getURL($file);
		}
		return $this->defaultCellDS;
	}


	/**
	 * Returns the default Flex DS XML for a single cell in fast-mode
	 *
	 * @return	array		Cell Flexform DS XML
	 */
	function defaultCellDS_fast()	{
		if (!strlen($this->defaultCellDS_fast))	{
			$file = t3lib_div::getFileAbsFileName('EXT:kb_conttable/res/singlecell_flex_ds_fast.xml');
			$this->defaultCellDS_fast = t3lib_div::getURL($file);
		}
		return $this->defaultCellDS_fast;
	}

	/**
	 * Returns the default Flex DS XML for a single cell in normal-mode
	 *
	 * @return	array		Cell Flexform DS XML
	 */
	function defaultCellDS_normal()	{
		if (!strlen($this->defaultCellDS_fast))	{
			$file = t3lib_div::getFileAbsFileName('EXT:kb_conttable/res/singlecell_flex_ds_normal.xml');
			$this->defaultCellDS_fast = t3lib_div::getURL($file);
		}
		return $this->defaultCellDS_fast;
	}

	/**
	 * Returns the default Flex DS XML for a single cell in normal-mode
	 *
	 * @return	array		Cell Flexform DS XML
	 */
	function defaultCellDS_rte()	{
		if (!strlen($this->defaultCellDS_rte))	{
			$file = t3lib_div::getFileAbsFileName('EXT:kb_conttable/res/singlecell_flex_ds_rte.xml');
			$this->defaultCellDS_rte = t3lib_div::getURL($file);
		}
		return $this->defaultCellDS_rte;
	}


	/**
	 * Returns the default Flex DS array for a single cell
	 *
	 * @param	integer		Row which gets created
	 * @param	integer		Column which gets created
	 * @return	array		Resulting Flexform DS array
	 */
	function getDefaultTable_CellDS($row, $col)	{
		$xml_def = $this->defaultCellDS();
		if ($this->parent->fastMode)	{
			$xml = $this->defaultCellDS_fast();
		} else	{
			$xml = $this->defaultCellDS_normal();
		}
		$xml_def = str_replace('###ROW###', $row, $xml_def);
		$xml_def = str_replace('###COLUMN###', $col, $xml_def);
		$xml = str_replace('###ROW###', $row, $xml);
		$xml = str_replace('###COLUMN###', $col, $xml);
		$xmlAr_def = t3lib_div::xml2array($xml_def);
		$xmlAr = t3lib_div::xml2array($xml);
		$xmlAr = array_merge($xmlAr_def, $xmlAr);
		$xmlAr = $this->columnLabel($xmlAr, $col);
		return $xmlAr;
	}

	/**
	 * Returns the default Flex DS array for a Row (sheet)
	 *
	 * @param	integer		Row which gets created
	 * @param	integer		Number of columns
	 * @return	array		Resulting Flexform DS array
	 */
	function getDefaultTable_RowDS($row, $columns)	{
		$el_ar = Array();
		$columnsAr = array();
		for ($column = 1; $column <= $columns; $column++)	{
			$el_ar = array_merge($el_ar, $this->getDefaultTable_CellDS($row, $column));
			$columnsAr[] = $column;
		}
		$xmlPart = Array(
			'ROOT' => Array(
				'TCEforms' => Array(
					'sheetTitle' => $GLOBALS['LANG']->sL('LLL:EXT:kb_conttable/tt_content_tx_kbconttable_flex_ds/locallang.php:row_prefix').' '.$row,
				),
				'type' => 'array',
				'el' => $el_ar,
			),
		);
		return Array($xmlPart, implode(',', $columnsAr));
	}

	/**
	 * Appends the DS for the rows and columns for a default table to $xmlArray and returns it. Also sets some value in the by reference passed parameter $flexData
	 *
	 * @param	array		XML array where the required sheets shall be attached
	 * @param	array		Flexdata array in which some required fields get attached
	 * @return	array		Resulting Flexform DS array
	 */
	function getDefaultTable_DataDS($xmlArray, &$flexData)	{
		$defaultRows = intval($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['kb_conttable']['defaultRows'])>0?intval($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['kb_conttable']['defaultRows']):2;
		$defaultColumns = intval($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['kb_conttable']['defaultColumns'])>0?intval($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['kb_conttable']['defaultColumns']):2;
		$rowsAr = array();
		for ($row = 1; $row <= $defaultRows; $row++)	{
			$sheet = 's_row_'.$row;
			list($xmlPart, $columnsStr) = $this->getDefaultTable_RowDS($row, $defaultColumns);
			$xmlArray['sheets'][$sheet] = $xmlPart;
			$rowsAr[] = $row;
			if (!is_array($flexData['data'][$sheet]))	{
				$flexData['data'][$sheet] = Array();
			}
			$flexData['data'][$sheet]['lDEF']['columns']['vDEF'] = $columnsStr;
			$flexData['data'][$sheet]['lDEF']['visible_columns']['vDEF'] = $columnsStr;
		}
		if (!is_array($flexData['data']['sDEF']['lDEF']['rows']))	{
			$flexData['data']['sDEF']['lDEF']['rows']	= Array();
		}
		$flexData['data']['sDEF']['lDEF']['rows']['vDEF'] = $rowsStr = implode(',', $rowsAr);
		if (!is_array($flexData['data']['sDEF']['lDEF']['visible_rows']))	{
			$flexData['data']['sDEF']['lDEF']['visible_rows']['vDEF'] = $rowsStr;
		}
		$flexData['data']['sDEF']['lDEF']['hidden_columns']['vDEF'] = '';
		return $xmlArray;
	}

	/**
	 * Replaces all labels in an XML array structure with the correct label for this column
	 * Called recursively
	 *
	 * @param	array		XML array where the labels shall be substituted
	 * @param	integer		Column which should be prefixed
	 * @return	array		Label replaced XML array
	 */
	function &columnLabel(&$ar, $col)	{
		foreach ($ar as $key => $var)	{
			if ((strtolower($key)==='label')&&!is_array($var))	{
				if (substr($var, 0, 4)==='LLL:')	{
					$ar[$key] = $GLOBALS['LANG']->sL('LLL:EXT:kb_conttable/tt_content_tx_kbconttable_flex_ds/locallang.php:column_prefix').' '.$col.': '.$GLOBALS['LANG']->sL($var);
				}
			} else if (is_array($var))	{
				$ar[$key] = $this->columnLabel($var, $col);
			}
		}
		return $ar;
	}

	/**
	 * Returns all existing templates in all storage folders
	 *
	 * @return	string		Default content table flex XML
	 */
	function defaultFlexDS()	{
		$file = t3lib_div::getFileAbsFileName('EXT:kb_conttable/res/default_flex_ds.xml');
		$data = t3lib_div::getURL($file);
		return $data;
	}

	/****************************************
	 *
	 * Storage Folders and Templates
	 *
	 * This methods return the Storage folders and existing Templates
	 *
	 ****************************************/

	/**
	 * Generates $this->storageFolders with available sysFolders linked to as storageFolders for the user
	 *
	 * @return	void		Modification in $this->storageFolders array
	 */
	function findingStorageFolderIds()	{
		global $TYPO3_DB;

			// Init:
		$readPerms = $GLOBALS['BE_USER']->getPagePermsClause(1);
		$this->storageFolders=array();

			// Looking up all references to a storage folder:
		$res = $TYPO3_DB->exec_SELECTquery (
			'uid,storage_pid',
			'pages',
			'storage_pid>0'.t3lib_BEfunc::deleteClause('pages')
		);
		while($row = $TYPO3_DB->sql_fetch_assoc($res))	{
			if ($GLOBALS['BE_USER']->isInWebMount($row['storage_pid'],$readPerms))	{
				$storageFolder = t3lib_BEfunc::getRecord('pages',$row['storage_pid'],'uid,title');
				if ($storageFolder['uid'])	{
					$this->storageFolders[$storageFolder['uid']] = $storageFolder['title'];
				}
			}
		}

			// Compopsing select list:
		$sysFolderPIDs = array_keys($this->storageFolders);
		$sysFolderPIDs[]=0;
		$this->storageFolders_pidList = implode(',',$sysFolderPIDs);
	}


	/**
	 * Returns an array containing all storage folders
	 *
	 * @return	array		Storage folders
	 */
	function getStorageFolders()	{
		$folders = array();
		$ids = t3lib_div::trimExplode(',', $this->storageFolders_pidList, 1);
		foreach ($ids as $id)	{
			if (intval($id))	{
				$row = t3lib_BEfunc::getRecord('pages', $id);
				if (is_array($row))	{
					$folders[] = $row;
				}
			}
		}
		return $folders;
	}

	/**
	 * Returns all existing templates in all storage folders
	 *
	 * @param	array		Storage folders
	 * @return	array		Existing template
	 */
	function getExistingTemplates($folders)	{
		$templates = array();
		foreach ($folders as $folder)	{
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', 'tx_kbconttable_tmpl', 'pid='.$folder['uid'].' '.t3lib_BEfunc::deleteClause('tx_kbconttable_tmpl'), '', 'name');
			while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res))	{
				$row['_label'] = t3lib_BEfunc::getRecordTitle('tx_kbconttable_tmpl', $row).' ('.$row['uid'].') ('.$GLOBALS['LANG']->sL('LLL:EXT:kb_conttable/locallang_db.xml:page').' = '.$folder['uid'].' : '.t3lib_BEfunc::getRecordTitle('pages', $folder).')';
				$templates[] = $row;
			}
			$GLOBALS['TYPO3_DB']->sql_free_result($res);
		}
		return $templates;
	}

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/kb_conttable/class.tx_kbconttable_funcs.php'])    {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/kb_conttable/class.tx_kbconttable_funcs.php']);
}


?>
