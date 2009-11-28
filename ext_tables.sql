#
# Table structure for table 'tt_content'
#
CREATE TABLE tt_content (
	tx_kbconttable_flex_ds mediumtext NOT NULL,
	tx_kbconttable_flex mediumtext NOT NULL
);



#
# Table structure for table 'tx_kbconttable_tmpl'
#
CREATE TABLE tx_kbconttable_tmpl (
	uid int(11) unsigned DEFAULT '0' NOT NULL auto_increment,
	pid int(11) unsigned DEFAULT '0' NOT NULL,
	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	name tinytext NOT NULL,
	tsconfig_name tinytext NOT NULL,
	allowed_users blob NOT NULL,
	allowed_groups blob NOT NULL,
	content_mode int(11) unsigned DEFAULT '0' NOT NULL,
	flex mediumtext NOT NULL,
	flex_ds mediumtext NOT NULL,
	
	PRIMARY KEY (uid),
	KEY parent (pid)
);