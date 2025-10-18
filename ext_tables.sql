#
# Table structure for table 'tx_news_domain_model_news'
#
CREATE TABLE tx_news_domain_model_news (
    sorting int(11) DEFAULT '0' NOT NULL,
	title varchar(255) DEFAULT '' NOT NULL,
	teaser text,
	bodytext mediumtext,
	datetime bigint(20) DEFAULT '0' NOT NULL,
	archive bigint(20) DEFAULT '0' NOT NULL,
	author tinytext,
	author_email tinytext,
	categories int(11) DEFAULT '0' NOT NULL,
	related int(11) DEFAULT '0' NOT NULL,
	related_from int(11) DEFAULT '0' NOT NULL,
	related_files tinytext,
	fal_related_files int(11) unsigned DEFAULT '0',
	related_links int(11) DEFAULT '0' NOT NULL,
	type varchar(100) NOT NULL DEFAULT '0',
	keywords text,
	description text,
	tags int(11) DEFAULT '0' NOT NULL,
	media text,
	fal_media int(11) unsigned DEFAULT '0',
	internalurl text,
	externalurl text,
	istopnews int(11) DEFAULT '0' NOT NULL,
	content_elements int(11) DEFAULT '0' NOT NULL,
	path_segment varchar(2048),
	alternative_title tinytext,
	notes text,
	sitemap_changefreq varchar(10) DEFAULT '' NOT NULL,
	sitemap_priority decimal(2,1) DEFAULT '0.5' NOT NULL,

	import_id varchar(100) DEFAULT '' NOT NULL,
	import_source varchar(100) DEFAULT '' NOT NULL,

	KEY path_segment (path_segment(185), uid),
	KEY import (import_id,import_source)
);


#
# Extend table structure of table 'sys_category'
#
CREATE TABLE sys_category (
	images int(11) unsigned DEFAULT '0',
	single_pid int(11) unsigned DEFAULT '0' NOT NULL,
	shortcut int(11) DEFAULT '0' NOT NULL,

	import_id varchar(100) DEFAULT '' NOT NULL,
	import_source varchar(100) DEFAULT '' NOT NULL,

	seo_title varchar(255) NOT NULL DEFAULT '',
	seo_description text,
	seo_headline varchar(255) NOT NULL DEFAULT '',
	seo_text text,
	slug varchar(2048),

	KEY import (import_id,import_source)
);

#
# Table structure for table 'tx_news_domain_model_news_related_mm'
#
#
CREATE TABLE tx_news_domain_model_news_related_mm (
	uid_local int(11) DEFAULT '0' NOT NULL,
	uid_foreign int(11) DEFAULT '0' NOT NULL,
	sorting int(11) DEFAULT '0' NOT NULL,
	sorting_foreign int(11) DEFAULT '0' NOT NULL,
	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_news_domain_model_link'
#
CREATE TABLE tx_news_domain_model_link (
	parent int(11) DEFAULT '0' NOT NULL,
	title tinytext,
	description text,
	uri text
);

#
# Table structure for table 'tx_news_domain_model_tag'
#
CREATE TABLE tx_news_domain_model_tag (
	title tinytext,
	slug varchar(2048),
	seo_title varchar(255) NOT NULL DEFAULT '',
	seo_description text,
	seo_headline varchar(255) NOT NULL DEFAULT '',
	seo_text text,
	notes text
);

#
# Table structure for table 'tx_news_domain_model_news_tag_mm'
#
CREATE TABLE tx_news_domain_model_news_tag_mm (
	uid_local int(11) DEFAULT '0' NOT NULL,
	uid_foreign int(11) DEFAULT '0' NOT NULL,
	sorting int(11) DEFAULT '0' NOT NULL,
	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tt_content'
#
CREATE TABLE tt_content (
	tx_news_related_news int(11) DEFAULT '0' NOT NULL,
	KEY index_newscontent (tx_news_related_news)
);

#
# Table structure for table 'sys_file_reference'
#
CREATE TABLE sys_file_reference (
	showinpreview tinyint(4) DEFAULT '0' NOT NULL
);
