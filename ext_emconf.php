<?php

########################################################################
# Extension Manager/Repository config file for ext "kb_conttable".
#
# Auto generated 13-07-2012 20:38
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
	'author' => 'Bernhard Kraft',
	'author_email' => 'kraftb@seicht.co.at',
	'author_company' => '',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'version' => '0.4.2',
	'constraints' => array(
		'depends' => array(
			'typo3' => '4.7.0-0.0.0',
			'php' => '5.0.0-0.0.0',
			'cms' => '',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:43:{s:9:"Changelog";s:4:"cac6";s:8:"TODO.txt";s:4:"e114";s:35:"class.tx_kbconttable_berenderCE.php";s:4:"9aa1";s:30:"class.tx_kbconttable_funcs.php";s:4:"e85f";s:31:"class.tx_kbconttable_layout.php";s:4:"44bf";s:32:"class.tx_kbconttable_preview.php";s:4:"4b94";s:31:"class.tx_kbconttable_tv_api.php";s:4:"75cb";s:38:"class.tx_kbconttable_tv_xmlrelhndl.php";s:4:"626f";s:32:"class.tx_kbconttable_wizicon.php";s:4:"63de";s:25:"class.tx_t3lib_befunc.php";s:4:"6978";s:26:"class.tx_t3lib_tcemain.php";s:4:"5207";s:28:"class.ux_t3lib_clipboard.php";s:4:"3b4e";s:21:"ext_conf_template.txt";s:4:"76b5";s:12:"ext_icon.gif";s:4:"1ab5";s:17:"ext_localconf.php";s:4:"d5bd";s:14:"ext_tables.php";s:4:"4e21";s:14:"ext_tables.sql";s:4:"718a";s:24:"ext_typoscript_setup.txt";s:4:"7a33";s:28:"icon_tx_kbconttable_tmpl.gif";s:4:"dc05";s:16:"locallang_db.xml";s:4:"f4d7";s:7:"tca.php";s:4:"1119";s:14:"doc/manual.sxw";s:4:"b8f6";s:32:"pi1/class.tx_kbconttable_pi1.php";s:4:"1d42";s:16:"res/clip_ref.gif";s:4:"6812";s:18:"res/clip_ref_h.gif";s:4:"ac5e";s:23:"res/default_flex_ds.xml";s:4:"5b5a";s:21:"res/makelocalcopy.gif";s:4:"ce99";s:19:"res/new_el_icon.gif";s:4:"7220";s:50:"res/selicon_tx_kbconttable_tmpl_content_mode_0.gif";s:4:"e703";s:50:"res/selicon_tx_kbconttable_tmpl_content_mode_1.gif";s:4:"0cc6";s:50:"res/selicon_tx_kbconttable_tmpl_content_mode_2.gif";s:4:"97a1";s:26:"res/singlecell_flex_ds.xml";s:4:"c770";s:31:"res/singlecell_flex_ds_fast.xml";s:4:"813a";s:33:"res/singlecell_flex_ds_normal.xml";s:4:"38ba";s:30:"res/singlecell_flex_ds_rte.xml";s:4:"7c52";s:43:"tt_content_tx_kbconttable_flex_ds/clear.gif";s:4:"cc11";s:42:"tt_content_tx_kbconttable_flex_ds/conf.php";s:4:"aaa8";s:55:"tt_content_tx_kbconttable_flex_ds/db_new_content_el.php";s:4:"f42c";s:43:"tt_content_tx_kbconttable_flex_ds/index.php";s:4:"4020";s:43:"tt_content_tx_kbconttable_flex_ds/jsfunc.js";s:4:"3b83";s:47:"tt_content_tx_kbconttable_flex_ds/locallang.xml";s:4:"926e";s:49:"tt_content_tx_kbconttable_flex_ds/ux_template.php";s:4:"8011";s:49:"tt_content_tx_kbconttable_flex_ds/wizard_icon.gif";s:4:"7b35";}',
	'suggests' => array(
	),
);

?>