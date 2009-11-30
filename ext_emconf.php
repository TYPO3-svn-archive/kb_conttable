<?php

########################################################################
# Extension Manager/Repository config file for ext "kb_conttable".
#
# Auto generated 30-11-2009 12:17
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'KB Content Table',
	'description' => 'Contentelement for enhanced table editing and content element integration',
	'category' => 'fe',
	'shy' => 0,
	'dependencies' => 'cms',
	'conflicts' => '',
	'priority' => '',
	'loadOrder' => '',
	'module' => 'tt_content_tx_kbconttable_flex_ds',
	'state' => 'stable',
	'uploadfolder' => 0,
	'createDirs' => 'uploads/tx_kbconttable/rte/',
	'modify_tables' => 'tt_content',
	'clearCacheOnLoad' => 1,
	'lockType' => '',
	'author' => 'Kraft Bernhard',
	'author_email' => 'kraftb@kraftb.at',
	'author_company' => '',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'version' => '0.3.1',
	'constraints' => array(
		'depends' => array(
			'typo3' => '4.0.0-0.0.0',
			'php' => '5.0.0-0.0.0',
			'cms' => '',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:42:{s:9:"Changelog";s:4:"b492";s:8:"TODO.txt";s:4:"9e36";s:35:"class.tx_kbconttable_berenderCE.php";s:4:"8217";s:30:"class.tx_kbconttable_funcs.php";s:4:"546b";s:31:"class.tx_kbconttable_layout.php";s:4:"b172";s:32:"class.tx_kbconttable_wizicon.php";s:4:"6780";s:35:"class.tx_kbconttable_xmlrelhndl.php";s:4:"516f";s:25:"class.tx_t3lib_befunc.php";s:4:"49b8";s:26:"class.tx_t3lib_tcemain.php";s:4:"98a0";s:35:"class.tx_templavoila_xmlrelhndl.php";s:4:"ba99";s:28:"class.ux_t3lib_clipboard.php";s:4:"5d56";s:21:"ext_conf_template.txt";s:4:"0a63";s:12:"ext_icon.gif";s:4:"1ab5";s:17:"ext_localconf.php";s:4:"f09b";s:14:"ext_tables.php";s:4:"3f78";s:14:"ext_tables.sql";s:4:"718a";s:24:"ext_typoscript_setup.txt";s:4:"7a33";s:28:"icon_tx_kbconttable_tmpl.gif";s:4:"dc05";s:16:"locallang_db.php";s:4:"9899";s:28:"patch_t3lib_befunc_hook.diff";s:4:"2866";s:7:"tca.php";s:4:"e031";s:14:"doc/manual.sxw";s:4:"624e";s:32:"pi1/class.tx_kbconttable_pi1.php";s:4:"ee57";s:16:"res/clip_ref.gif";s:4:"6812";s:18:"res/clip_ref_h.gif";s:4:"ac5e";s:23:"res/default_flex_ds.xml";s:4:"5b5a";s:21:"res/makelocalcopy.gif";s:4:"ce99";s:19:"res/new_el_icon.gif";s:4:"7220";s:50:"res/selicon_tx_kbconttable_tmpl_content_mode_0.gif";s:4:"e703";s:50:"res/selicon_tx_kbconttable_tmpl_content_mode_1.gif";s:4:"0cc6";s:50:"res/selicon_tx_kbconttable_tmpl_content_mode_2.gif";s:4:"97a1";s:26:"res/singlecell_flex_ds.xml";s:4:"c770";s:31:"res/singlecell_flex_ds_fast.xml";s:4:"813a";s:33:"res/singlecell_flex_ds_normal.xml";s:4:"38ba";s:30:"res/singlecell_flex_ds_rte.xml";s:4:"7c52";s:43:"tt_content_tx_kbconttable_flex_ds/clear.gif";s:4:"cc11";s:42:"tt_content_tx_kbconttable_flex_ds/conf.php";s:4:"aaa8";s:55:"tt_content_tx_kbconttable_flex_ds/db_new_content_el.php";s:4:"6399";s:43:"tt_content_tx_kbconttable_flex_ds/index.php";s:4:"b12a";s:43:"tt_content_tx_kbconttable_flex_ds/jsfunc.js";s:4:"cce8";s:47:"tt_content_tx_kbconttable_flex_ds/locallang.php";s:4:"9309";s:49:"tt_content_tx_kbconttable_flex_ds/wizard_icon.gif";s:4:"7b35";}',
	'suggests' => array(
	),
);

?>