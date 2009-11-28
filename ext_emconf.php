<?php

########################################################################
# Extension Manager/Repository config file for ext "kb_conttable".
#
# Auto generated 28-11-2009 11:37
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
	'version' => '0.3.0',
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
	'_md5_values_when_last_written' => '',
	'suggests' => array(
	),
);

?>
