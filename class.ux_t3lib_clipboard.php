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
 *   57: class ux_t3lib_clipboard extends t3lib_clipboard
 *   64:     function initializeClipboard()
 *  111:     function pasteUrl($table,$uid,$setRedirect=1, $colPos = 0, $baseArray = Array())
 *  142:     function makePasteCmdArray($ref,$CMD)
 *  237:     function cleanCurrent()
 *  268:     function printClipboard()
 *  369:     function printContentFromTab($pad)
 *  450:     function getSelectedRecord($table='',$uid='')
 *  474:     function elFromTable($matchTable='',$pad='')
 *  511:     function selUrlDB($table,$uid,$copy=0,$deselect=0,$baseArray=array())
 *  530:     function setCmd($cmd)
 *  576:     function currentMode()
 *  587:     function padTitleWrap($str,$pad)
 *
 * TOTAL FUNCTIONS: 12
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

require_once(t3lib_extMgm::extPath('kb_conttable').'class.tx_kbconttable_tv_xmlrelhndl.php');

class ux_t3lib_clipboard extends t3lib_clipboard	{

	/**
	 * Initialize the clipboard from the be_user session
	 *
	 * @return	void
	 */
	function initializeClipboard()	{
		global $BE_USER;


			// Get data
		$clipData = $BE_USER->getModuleData('clipboard',$BE_USER->getTSConfigVal('options.saveClipboard')?'':'ses');

			// NumberTabs
		$clNP = $BE_USER->getTSConfigVal('options.clipboardNumberPads');
		if (t3lib_div::testInt($clNP) && $clNP>=0)	{
			$this->numberTabs = t3lib_div::intInRange($clNP,0,20);
		}

			// Resets/reinstates the clipboard pads
		$this->clipData['normal'] = is_array($clipData['normal']) ? $clipData['normal'] : array();
		for ($a=1;$a<=$this->numberTabs;$a++)	{
			$this->clipData['tab_'.$a] = is_array($clipData['tab_'.$a]) ? $clipData['tab_'.$a] : array();
		}

			// Setting the current pad pointer ($this->current) and _setThumb (which determines whether or not do show file thumbnails)
		$this->clipData['current'] = $this->current = isset($this->clipData[$clipData['current']]) ? $clipData['current'] : 'normal';
		$this->clipData['_setThumb'] = $clipData['_setThumb'];

		$this->xmlhandler = t3lib_div::makeInstance('tx_kbconttable_tv_xmlrelhndl');
		$this->xmlhandler->init($this->altRoot);
		$this->colPos = isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['kb_conttable']['colPos'])?intval($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['kb_conttable']['colPos']):10;
		$this->xmlhandler->colPos = $this->colPos;

	}






	/**
	 * pasteUrl of the element (database and file)
	 * For the meaning of $table and $uid, please read from ->makePasteCmdArray!!!
	 * The URL will point to tce_file or tce_db depending in $table
	 *
	 * @param	string		Tablename (_FILE for files)
	 * @param	mixed		"destination": can be positive or negative indicating how the paste is done (paste into / paste after)
	 * @param	boolean		If set, then the redirect URL will point back to the current script, but with CB reset.
	 * @param	array		Set of parameters which should be set in the redirect URL
	 * @param	[type]		$baseArray: ...
	 * @return	string
	 */
	function pasteUrl($table,$uid,$setRedirect=1, $colPos = 0, $baseArray = Array())	{
		$baseArray['CB'] = '';
		$rU = $this->backPath.($table=='_FILE'?'tce_file.php':'tce_db.php').'?'.
			($setRedirect ? 'redirect='.rawurlencode(t3lib_div::linkThisScript($baseArray)) : '').
			'&vC='.$GLOBALS['BE_USER']->veriCode().
			'&prErr=1&uPT=1'.
			'&CB[paste]='.rawurlencode($table.'|'.$uid.'|'.$colPos).
			'&CB[pad]='.$this->current;
		return $rU;
	}


	/**
	 * Applies the proper paste configuration in the $cmd array send to tce_db.php.
	 * $ref is the target, see description below. Extended with the fucntionality that the Items
	 * from the clipboard can be in the form XML|table:uid:sheet:lDEF:field:vDEF:counter and also
	 * the target $ref
	 * The current pad is pasted
	 *
	 * 		$ref: [tablename]:[paste-uid].
	 * 		tablename is the name of the table from which elements *on the current clipboard* is pasted with the 'pid' paste-uid.
	 * 		No tablename means that all items on the clipboard (non-files) are pasted. This requires paste-uid to be positive though.
	 * 		so 'tt_content:-3'	means 'paste tt_content elements on the clipboard to AFTER tt_content:3 record
	 * 		'tt_content:30'	means 'paste tt_content elements on the clipboard into page with id 30
	 * 		':30'	means 'paste ALL database elements on the clipboard into page with id 30
	 * 		':-30'	not valid.
	 *
	 * @param	string		[tablename]:[paste-uid], see description
	 * @param	array		Command-array
	 * @return	array		Modified Command-array
	 */
	function makePasteCmdArray($ref,$CMD)	{

		list($pTable,$pUid, $pColPos) = $refArr = explode('|',$ref);
		if ($pTable=='XML'&&strlen($pUid))	{
			$this->xmlhandler->colPos = intval($pColPos);

			$mode = $this->currentMode()=='copy' ? 'copy' : ($this->currentMode()=='ref' ? 'ref' : 'cut');
			$elements = array_merge($this->elFromTable('tt_content'), $this->elFromTable('XML'));
			$elements = array_reverse($elements);	// So the order is preserved.

			reset($elements);
			$source = '';
			while(list($tP)=each($elements))	{
				list($table,$uid) = explode('|',$tP);
				if ($table=='XML')	{
					$this->xmlhandler->pasteRecord($mode, $uid, $pUid);		// The XML destination path is stored in $pUid
				} else	{
					$this->xmlhandler->pasteRecord($mode, $tP, $pUid);		// The XML destination path is stored in $pUid
				}
				// STOP : Why doesn't the element get removed when cutted
				if ($mode=='cut')	$this->removeElement($tP);
		 	}
			return Array();
		} else	{
			$pUid = intval($pUid);

			$mode = $this->currentMode()=='copy' ? 'copy' : ($this->currentMode()=='ref' ? 'ref' : 'move');
			if ($pTable || $pUid>=0)	{	// pUid must be set and if pTable is not set (that means paste ALL elements) the uid MUST be positive/zero (pointing to page id)
				$elements = $this->elFromTable($pTable);

				$elements = array_reverse($elements);	// So the order is preserved.

					// Traverse elements and make CMD array
				reset($elements);
				while(list($tP)=each($elements))	{
					list($table,$uid) = explode('|',$tP);
					$origTable = $table;
					if ($table=='XML')	{
						$origUid = $uid;
						list($table, $uid, $rec) = $this->xmlhandler->getRecord($uid);		// The XML Path is stored in $uid
					}
					if (!is_array($CMD[$table]))	$CMD[$table]=array();
					if (($table=='tt_content')&&($mode=='ref'))	{
						$hash = substr(md5(time().getmypid().rand(0,0x7fffffff)), 0, 10);
						$dataArray = Array();
						$dataArray['tt_content']['NEW'.$hash]['pid'] = $pUid;
						$dataArray['tt_content']['NEW'.$hash]['CType'] = 'shortcut';
						$dataArray['tt_content']['NEW'.$hash]['records'] = $table.'_'.$uid;
						$dataArray['tt_content']['NEW'.$hash]['colPos'] = intval($pColPos);
						$tce = t3lib_div::makeInstance('t3lib_TCEmain');
						$tce->start($dataArray, Array());
						$tce->process_datamap();
					} else	{
						$CMD[$table][$uid][$mode]=$pUid;
					}
					if (($mode=='copy')&&($table=='tt_content'))	{
						if ($pUid > 0)	{
							// Move onto a page. Fix colPos
							$tce = t3lib_div::makeInstance('t3lib_TCEmain');
							$tce->start(Array(), $CMD);
							$tce->process_cmdmap();
							$CMD = '';
							$dataArray = Array();
							$dataArray['tt_content'][$tce->copyMappingArray[$table][$uid]]['colPos'] = intval($pColPos);
							$tce = t3lib_div::makeInstance('t3lib_TCEmain');
							$tce->start($dataArray, Array());
							$tce->process_datamap();
						}
					} elseif ($mode=='move')	{
						if ($table=='tt_content')	{
							$dataArray = Array();
							$dataArray['tt_content'][$uid]['colPos'] = intval($pColPos);
							$tce = t3lib_div::makeInstance('t3lib_TCEmain');
							$tce->start($dataArray, Array());
							$tce->process_datamap();
						}
						if($origTable=='XML')	{
							// We move and the source is XML. Unlink it.
							$this->xmlhandler->pasteRecord('unlink', $origUid, '');
						}
						$this->removeElement($tP);
					}
				}
				$this->endClipboard();
			}
			return $CMD;
		}
	}

	/**
	 * This traverses the elements on the current clipboard pane
	 * and unsets elements which does not exist anymore or are disabled.
	 *
	 * @return	void
	 */
	function cleanCurrent()	{
		if (is_array($this->clipData[$this->current]['el']))	{
			reset($this->clipData[$this->current]['el']);
			while(list($k,$v)=each($this->clipData[$this->current]['el']))	{
				list($table,$uid) = explode('|',$k);
				if ($table!='_FILE')	{
					if ($table=='XML')	{
						list($table, $uid, $rec) = $this->xmlhandler->getRecord($uid);		// The XML Path is stored in $uid
						if (!$v || !(strlen($table)&&intval($uid)&&is_array($rec)))	{
							unset($this->clipData[$this->current]['el'][$k]);
							$this->changed=1;
						}
					} else if (!$v || !is_array(t3lib_BEfunc::getRecord($table,$uid,'uid')))	{
						unset($this->clipData[$this->current]['el'][$k]);
						$this->changed=1;
					}
				} else	{
					if (!$v || !@file_exists($v))	{
						unset($this->clipData[$this->current]['el'][$k]);
						$this->changed=1;
					}
				}
			}
		}
	}

	/**
	 * Prints the clipboard
	 *
	 * @return	string		HTML output
	 */
	function printClipboard()	{
		global $TBE_TEMPLATE,$LANG;

		$out=array();
		$elCount = count($this->elFromTable($this->fileMode?'_FILE':''));

			// Upper header
		$out[]='
			<tr class="bgColor2">
				<td colspan="3" nowrap="nowrap" align="center"><span class="uppercase"><strong>'.$this->clLabel('clipboard','buttons').'</strong></span></td>
			</tr>';

			// Button/menu header:
		$thumb_url = t3lib_div::linkThisScript(array('CB'=>array('setThumb'=>$this->clipData['_setThumb']?0:1)));
			// Circulate : cut -> copy -> ref -> cut -> ...
		switch ($this->currentMode())	{
			case 'cut':
				$copymode_url = t3lib_div::linkThisScript(array('CB'=>array('setCopyMode'=> 'copy')));
			break;
			case 'copy':
				$copymode_url = t3lib_div::linkThisScript(array('CB'=>array('setCopyMode'=> 'ref')));
			break;
			default:
			case 'ref':
				$copymode_url = t3lib_div::linkThisScript(array('CB'=>array('setCopyMode'=> 'cut')));
			break;
		}
		$rmall_url = t3lib_div::linkThisScript(array('CB'=>array('removeAll'=>$this->current)));

			// Selector menu + clear button
		$opt=array();
		$opt[]='<option value="" selected="selected">'.$this->clLabel('menu','rm').'</option>';
		if (!$this->fileMode && $elCount)	$opt[]='<option value="'.htmlspecialchars("document.location='".$this->editUrl()."&returnUrl='+top.rawurlencode(document.location);").'">'.$this->clLabel('edit','rm').'</option>';
		if ($elCount)	$opt[]='<option value="'.htmlspecialchars("
			if(confirm(".$GLOBALS['LANG']->JScharCode(sprintf($LANG->sL('LLL:EXT:lang/locallang_core.php:mess.deleteClip'),$elCount)).")){
				document.location='".$this->deleteUrl(0,$this->fileMode?1:0)."&redirect='+top.rawurlencode(document.location);
			}
			").'">'.$this->clLabel('delete','rm').'</option>';
		$selector_menu = '<select name="_clipMenu" onchange="eval(this.options[this.selectedIndex].value);this.selectedIndex=0;">'.implode('',$opt).'</select>';

		$out[]='
			<tr class="typo3-clipboard-head">
				<td>'.
				'<a href="'.htmlspecialchars($thumb_url).'#clip_head">'.
					'<img'.t3lib_iconWorks::skinImg($this->backPath,'gfx/thumb_'.($this->clipData['_setThumb']?'s':'n').'.gif','width="21" height="16"').' vspace="2" border="0" title="'.$this->clLabel('thumbmode_clip').'" alt="" />'.
					'</a>'.
				'<a href="'.htmlspecialchars($copymode_url).'#clip_head">'.
					'<img'.t3lib_iconWorks::skinImg($this->backPath,'gfx/copymode_'.($this->currentMode()=='ref'?'h':($this->currentMode()=='copy'?'s':'n')).'.gif','width="21" height="16"').' vspace="2" border="0" title="'.$this->clLabel('copymode').'" alt="" />'.
					'</a>'.
				'</td>
				<td width="95%">'.$selector_menu.'</td>
				<td><a href="'.htmlspecialchars($rmall_url).'#clip_head">'.$LANG->sL('LLL:EXT:lang/locallang_core.php:buttons.clear',1).'</a></td>
			</tr>';


			// Print header and content for the NORMAL tab:
		$out[]='
			<tr class="bgColor5">
				<td colspan="3"><a href="'.htmlspecialchars(t3lib_div::linkThisScript(array('CB'=>array('setP'=>'normal')))).'#clip_head">'.
					'<img'.t3lib_iconWorks::skinImg($this->backPath,'gfx/ol/'.($this->current=='normal'?'minus':'plus').'bullet.gif','width="18" height="16"').' border="0" align="top" alt="" />'.
					$this->padTitleWrap('Normal','normal').
					'</a></td>
			</tr>';
		if ($this->current=='normal')	$out=array_merge($out,$this->printContentFromTab('normal'));

			// Print header and content for the NUMERIC tabs:
		for ($a=1;$a<=$this->numberTabs;$a++)	{
			$out[]='
				<tr class="bgColor5">
					<td colspan="3"><a href="'.htmlspecialchars(t3lib_div::linkThisScript(array('CB'=>array('setP'=>'tab_'.$a)))).'#clip_head">'.
						'<img'.t3lib_iconWorks::skinImg($this->backPath,'gfx/ol/'.($this->current=='tab_'.$a?'minus':'plus').'bullet.gif','width="18" height="16"').' border="0" align="top" alt="" />'.
						$this->padTitleWrap($this->clLabel('cliptabs').$a,'tab_'.$a).
						'</a></td>
				</tr>';
			if ($this->current=='tab_'.$a)	$out=array_merge($out,$this->printContentFromTab('tab_'.$a));
		}

			// Wrap accumulated rows in a table:
		$output = '<a name="clip_head"></a>

			<!--
				TYPO3 Clipboard:
			-->
			<table cellpadding="0" cellspacing="1" border="0" width="290" id="typo3-clipboard">
				'.implode('',$out).'
			</table>';

			// Wrap in form tag:
		$output = '<form action="">'.$output.'</form>';

			// Return the accumulated content:
		return $output;
	}

	/**
	 * Print the content on a pad. Called from ->printClipboard()
	 *
	 * @param	string		Pad reference
	 * @return	array		Array with table rows for the clipboard.
	 * @access private
	 */
	function printContentFromTab($pad)	{
		global $TBE_TEMPLATE;

		$lines=array();
		if (is_array($this->clipData[$pad]['el']))	{
			reset($this->clipData[$pad]['el']);
			while(list($k,$v)=each($this->clipData[$pad]['el']))	{
				if ($v)	{
					list($table,$uid) = explode('|',$k);
					$bgColClass = ($table=='_FILE'&&$this->fileMode)||($table!='_FILE'&&!$this->fileMode) ? 'bgColor4-20' : 'bgColor4';

					if ($table=='_FILE')	{	// Rendering files/directories on the clipboard:
						if (@file_exists($v) && t3lib_div::isAllowedAbsPath($v))	{
							$fI = pathinfo($v);
							$icon = is_dir($v) ? 'folder.gif' : t3lib_BEfunc::getFileIcon(strtolower($fI['extension']));
							$size = ' ('.t3lib_div::formatSize(filesize($v)).'bytes)';
							$icon = '<img'.t3lib_iconWorks::skinImg($this->backPath,'gfx/fileicons/'.$icon,'width="18" height="16"').' border="0" hspace="20" class="absmiddle" title="'.htmlspecialchars($fI['basename'].$size).'" alt="" />';
							$thumb = $this->clipData['_setThumb'] ? (t3lib_div::inList($GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'],$fI['extension']) ? t3lib_BEfunc::getThumbNail($this->backPath.'thumbs.php',$v,' vspace="4"') : '') :'';

							$lines[]='
								<tr>
									<td class="'.$bgColClass.'">'.$icon.'</td>
									<td class="'.$bgColClass.'" nowrap="nowrap" width="95%">&nbsp;'.$this->linkItemText(htmlspecialchars(t3lib_div::fixed_lgd_cs(basename($v),$GLOBALS['BE_USER']->uc['titleLen'])),$v).
										($pad=='normal'?(' <strong>('.($this->clipData['normal']['mode']=='copy'?$this->clLabel('copy','cm'):$this->clLabel('cut','cm')).')</strong>'):'').'&nbsp;'.($thumb?'<br />'.$thumb:'').'</td>
									<td class="'.$bgColClass.'" align="center">'.
									'<a href="#" onclick="'.htmlspecialchars('top.launchView(\''.$v.'\', \'\'); return false;').'"><img'.t3lib_iconWorks::skinImg($this->backPath,'gfx/zoom2.gif','width="12" height="12"').' hspace="2" border="0" title="'.$this->clLabel('info','cm').'" alt="" /></a>'.
									'<a href="'.htmlspecialchars($this->removeUrl('_FILE',t3lib_div::shortmd5($v))).'#clip_head"><img'.t3lib_iconWorks::skinImg($this->backPath,'gfx/close_12h.gif','width="11" height="12"').' border="0" title="'.$this->clLabel('removeItem').'" alt="" /></a>'.
									'</td>
								</tr>';
						} else	{
								// If the file did not exist (or is illegal) then it is removed from the clipboard immediately:
							unset($this->clipData[$pad]['el'][$k]);
							$this->changed=1;
						}
					} else	{	// Rendering records:
						if ($table=='XML')	{
							$old_uid = $uid;
							list($table, $uid, $rec) = $this->xmlhandler->getRecord($uid);		// The XML Path is stored in $uid
						} else	{
							$rec=t3lib_BEfunc::getRecord($table,$uid);
						}
						if (is_array($rec))	{
							$lines[]='
								<tr>
									<td class="'.$bgColClass.'">'.$this->linkItemText(t3lib_iconWorks::getIconImage($table,$rec,$this->backPath,'hspace="20" title="'.htmlspecialchars(t3lib_BEfunc::getRecordIconAltText($rec,$table)).'"'),$rec,$table).'</td>
									<td class="'.$bgColClass.'" nowrap="nowrap" width="95%">&nbsp;'.$this->linkItemText(htmlspecialchars(t3lib_div::fixed_lgd_cs(t3lib_BEfunc::getRecordTitle($table,$rec),$GLOBALS['BE_USER']->uc['titleLen'])),$rec,$table).
										($pad=='normal'?' <strong>('.($this->clipData['normal']['mode']=='ref'?$GLOBALS['LANG']->sL('LLL:EXT:kb_conttable/locallang_db.xml:clipboard.ref_short'):($this->clipData['normal']['mode']=='copy'?$this->clLabel('copy','cm'):$this->clLabel('cut','cm'))).')</strong>':'').'&nbsp;</td>
									<td class="'.$bgColClass.'" align="center">'.
									'<a href="#" onclick="'.htmlspecialchars('top.launchView(\''.$table.'\', \''.intval($uid).'\'); return false;').'"><img'.t3lib_iconWorks::skinImg($this->backPath,'gfx/zoom2.gif','width="12" height="12"').' hspace="2" border="0" title="'.$this->clLabel('info','cm').'" alt="" /></a>'.
									'<a href="'.htmlspecialchars($this->removeUrl('XML', $old_uid)).'#clip_head"><img'.t3lib_iconWorks::skinImg($this->backPath,'gfx/close_12h.gif','width="11" height="12"').' border="0" title="'.$this->clLabel('removeItem').'" alt="" /></a>'.
									'</td>
								</tr>';
						} else	{
							unset($this->clipData[$pad]['el'][$k]);
							$this->changed=1;
						}
					}
				}
			}
		}
		if (!count($lines))	{
			$lines[]='
								<tr>
									<td class="bgColor4"><img src="clear.gif" width="56" height="1" alt="" /></td>
									<td colspan="2" class="bgColor4" nowrap="nowrap" width="95%">&nbsp;<em>('.$this->clLabel('clipNoEl').')</em>&nbsp;</td>
								</tr>';
		}

		$this->endClipboard();
		return $lines;
	}

	/**
	 * Returns item record $table,$uid if selected on current clipboard
	 * If table and uid is blank, the first element is returned.
	 * Makes sense only for DB records - not files!
	 *
	 * @param	string		Table name
	 * @param	integer		Element uid
	 * @return	array		Element record with extra field _RECORD_TITLE set to the title of the record...
	 */
	function getSelectedRecord($table='',$uid='')	{
		if (!$table && !$uid)	{
			$elArr = $this->elFromTable('');
			reset($elArr);
			list($table,$uid) = explode('|',key($elArr));
		}
		if ($this->isSelected($table,$uid))	{
			if ($table=='XML')	{
				$selRec = $this->xmlhandler->getRecord($uid);
			} else	{
				$selRec = t3lib_BEfunc::getRecord($table,$uid);
			}
			$selRec['_RECORD_TITLE'] = t3lib_BEfunc::getRecordTitle($table,$selRec);
			return $selRec;
		}
	}

	/**
	 * Counts the number of elements from the table $matchTable. If $matchTable is blank, all tables (except '_FILE' of course) is counted.
	 *
	 * @param	string		Table to match/count for.
	 * @param	string		$pad can optionally be used to set another pad than the current.
	 * @return	array		Array with keys from the CB.
	 */
	function elFromTable($matchTable='',$pad='')	{
		$pad = $pad ? $pad : $this->current;
		$list=array();
		if (is_array($this->clipData[$pad]['el']))	{
			reset($this->clipData[$pad]['el']);
			while(list($k,$v)=each($this->clipData[$pad]['el']))	{
				if ($v)	{
					list($table,$uid) = explode('|',$k);
					if ($table!='_FILE')	{
						if ($table=='XML')	{
							$origUid = $uid;
							list($table, $uid, $rec) = $this->xmlhandler->getRecord($uid);
						}
						if ((!$matchTable || (((string)$table==(string)$matchTable)) && $GLOBALS['TCA'][$table]))	{
							$list[$k]= ($pad=='normal'?$v:($origUid?$origUiD:$uid));
						}
					} else	{
						if ((string)$table==(string)$matchTable)	{
							$list[$k]=$v;
						}
					}
				}
			}
		}
		return $list;
	}

	/**
	 * Returns the select-url for database elements
	 *
	 * @param	string		Table name
	 * @param	integer		Uid of record
	 * @param	boolean		If set, copymode will be enabled
	 * @param	boolean		If set, the link will deselect, otherwise select.
	 * @param	array		The base array of GET vars to be sent in addition. Notice that current GET vars WILL automatically be included.
	 * @return	string		URL linking to the current script but with the CB array set to select the element with table/uid
	 */
	function selUrlDB($table,$uid,$copy=0,$deselect=0,$baseArray=array())	{
		$CB = array('el'=>array(rawurlencode($table.'|'.$uid)=>$deselect?0:1));
		if ($copy)	$CB['setCopyMode'] = $copy;
		$baseArray['CB'] = $CB;
		return t3lib_div::linkThisScript($baseArray);
	}

	/**
	 * The array $cmd may hold various keys which notes some action to take.
	 * Normally perform only one action at a time.
	 * In scripts like db_list.php / file_list.php the GET-var CB is used to control the clipboard.
	 *
	 * 		Selecting / Deselecting elements
	 * 		Array $cmd['el'] has keys = element-ident, value = element value (see description of clipData array in header)
	 * 		Selecting elements for 'copy' should be done by simultaneously setting setCopyMode.
	 *
	 * @param	array		Array of actions, see function description
	 * @return	void
	 */
	function setCmd($cmd)	{
		if (is_array($cmd['el']))	{
			reset($cmd['el']);
			while(list($k,$v)=each($cmd['el']))	{
				if ($this->current=='normal')	{
					unset($this->clipData['normal']);
				}
				if ($v)	{
					$this->clipData[$this->current]['el'][$k]=$v;
				} else	{
					$this->removeElement($k);
				}
				$this->changed=1;
			}
		}
			// Change clipboard pad (if not locked to normal)
		if ($cmd['setP'])	{
			$this->setCurrentPad($cmd['setP']);
		}
			// Remove element	(value = item ident: DB; '[tablename]|[uid]'    FILE: '_FILE|[shortmd5 hash of path]'
		if ($cmd['remove'])	{
			$this->removeElement($cmd['remove']);
			$this->changed=1;
		}
			// Remove all on current pad (value = pad-ident)
		if ($cmd['removeAll'])	{
			$this->clipData[$cmd['removeAll']]=array();
			$this->changed=1;
		}
			// Set copy mode of the tab
		if (isset($cmd['setCopyMode']))	{
			$this->clipData[$this->current]['mode']=$this->isElements()?(($cmd['setCopyMode']=='ref')||($cmd['setCopyMode']>1)?'ref':(($cmd['setCopyMode']>0)||($cmd['setCopyMode']=='copy')?'copy':'')):'';
			$this->changed=1;
		}
			// Toggle thumbnail display for files on/off
		if (isset($cmd['setThumb']))	{
			$this->clipData['_setThumb']=$cmd['setThumb'];
			$this->changed=1;
		}
	}

	/**
	 * Returns the current mode, 'copy', 'cut' or 'ref'
	 *
	 * @return	string		"copy" or "cut"
	 */
	function currentMode()	{
		return $this->clipData[$this->current]['mode']=='ref' ? 'ref' : ($this->clipData[$this->current]['mode']=='copy' ? 'copy' : 'cut');
	}

	/**
	 * Wraps title of pad in bold-tags and maybe the number of elements if any.
	 *
	 * @param	string		String (already htmlspecialchars()'ed)
	 * @param	string		Pad reference
	 * @return	string		HTML output (htmlspecialchar'ed content inside of tags.)
	 */
	function padTitleWrap($str,$pad)	{
		$el = count($this->elFromTable($this->fileMode?'_FILE':'',$pad));
		if ($el)	{
			return '<strong>'.$str.'</strong> ('.($pad=='normal'?($this->clipData['normal']['mode']=='ref'?$GLOBALS['LANG']->sL('LLL:EXT:kb_conttable/locallang_db.xml:clipboard.ref_short'):($this->clipData['normal']['mode']=='copy'?$this->clLabel('copy','cm'):$this->clLabel('cut','cm'))):htmlspecialchars($el)).')';
		} else	{
			return $GLOBALS['TBE_TEMPLATE']->dfw($str);
		}
	}

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/kb_conttable/class.ux_t3lib_clipboard.php'])    {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/kb_conttable/class.ux_t3lib_clipboard.php']);
}

?>
