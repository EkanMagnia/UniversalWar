# phpMyAdmin SQL Dump
# version 2.5.3-rc2
# http://www.phpmyadmin.net
#
# Värd: 62.70.14.32
# Skapad: 29 september 2003 kl 19:41
# Serverversion: 3.23.56
# PHP-version: 4.3.2
# 
# Databas : `univers_beta`
# 

# --------------------------------------------------------

#
# Struktur för tabell `uwar_agovernment`
#

CREATE TABLE `uwar_agovernment` (
  `id` int(15) unsigned NOT NULL auto_increment,
  `userid` mediumint(8) unsigned NOT NULL default '0',
  `time` bigint(15) unsigned default NULL,
  `subject` varchar(40) NOT NULL default '',
  `content` text NOT NULL,
  `threadstarter` mediumint(8) unsigned NOT NULL default '0',
  `tagid` smallint(5) unsigned NOT NULL default '0',
  `timereply` bigint(15) unsigned default NULL,
  `replyid` int(15) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=4 ;

# --------------------------------------------------------

#
# Struktur för tabell `uwar_allyfund`
#

CREATE TABLE `uwar_allyfund` (
  `tagid` int(15) NOT NULL default '0',
  `sysmercury` bigint(12) NOT NULL default '0',
  `syscobalt` bigint(12) NOT NULL default '0',
  `syshelium` bigint(12) NOT NULL default '0',
  PRIMARY KEY  (`tagid`)
) TYPE=MyISAM;

# --------------------------------------------------------

#
# Struktur för tabell `uwar_announcements`
#

CREATE TABLE `uwar_announcements` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `subject` varchar(80) NOT NULL default '',
  `author` varchar(50) NOT NULL default '0',
  `time` bigint(15) unsigned default NULL,
  `motd` text NOT NULL,
  `ann_modif_usr` mediumint(5) unsigned NOT NULL default '0',
  `grp_modif_time` timestamp(14) NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=3 ;

# --------------------------------------------------------

#
# Struktur för tabell `uwar_bugs`
#

CREATE TABLE `uwar_bugs` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(60) NOT NULL default '',
  `section` varchar(50) NOT NULL default '',
  `description` text NOT NULL,
  `links` tinytext NOT NULL,
  `creator` int(10) unsigned NOT NULL default '0',
  `CreatTime` timestamp(14) NOT NULL,
  `modif` int(10) unsigned NOT NULL default '0',
  `modiftime` timestamp(14) NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

# --------------------------------------------------------

#
# Struktur för tabell `uwar_bugsforum`
#

CREATE TABLE `uwar_bugsforum` (
  `id` int(15) unsigned NOT NULL auto_increment,
  `userid` mediumint(8) unsigned NOT NULL default '0',
  `time` bigint(15) unsigned default NULL,
  `subject` varchar(40) NOT NULL default '',
  `content` text NOT NULL,
  `threadstarter` mediumint(8) unsigned NOT NULL default '0',
  `timereply` bigint(15) unsigned default NULL,
  `replyid` int(15) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=8 ;

# --------------------------------------------------------

#
# Struktur för tabell `uwar_constructions`
#

CREATE TABLE `uwar_constructions` (
  `userid` mediumint(8) unsigned NOT NULL default '0',
  `constructionid` smallint(2) unsigned NOT NULL default '0',
  `complete` smallint(1) unsigned NOT NULL default '0',
  `eta` smallint(3) unsigned NOT NULL default '0',
  `activated` tinyint(1) unsigned NOT NULL default '0'
) TYPE=MyISAM;

# --------------------------------------------------------

#
# Struktur för tabell `uwar_fships`
#

CREATE TABLE `uwar_fships` (
  `userid` mediumint(8) unsigned NOT NULL default '0',
  `shipid` smallint(3) unsigned NOT NULL default '0',
  `fleetnum` tinyint(1) unsigned NOT NULL default '0',
  `amount` mediumint(7) unsigned NOT NULL default '0',
  `ops` char(1) NOT NULL default ''
) TYPE=MyISAM;

# --------------------------------------------------------

#
# Struktur för tabell `uwar_mail`
#

CREATE TABLE `uwar_mail` (
  `mailid` int(11) unsigned NOT NULL default '0',
  `userid` mediumint(8) unsigned NOT NULL default '0',
  `authorid` mediumint(8) unsigned NOT NULL default '0',
  `header` varchar(80) NOT NULL default '',
  `mail` text NOT NULL,
  `time` int(11) NOT NULL default '0',
  `seen` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`mailid`)
) TYPE=MyISAM;

# --------------------------------------------------------

#
# Struktur för tabell `uwar_modes`
#

CREATE TABLE `uwar_modes` (
  `id` tinyint(3) unsigned NOT NULL auto_increment,
  `signup` tinyint(1) unsigned NOT NULL default '0',
  `havoc` tinyint(1) unsigned NOT NULL default '0',
  `pause` tinyint(1) unsigned NOT NULL default '0',
  `gametype` char(1) NOT NULL default '',
  `sleep` int(11) NOT NULL default '0',
  `vacation` int(11) NOT NULL default '0',
  `protection` int(11) NOT NULL default '0',
  `tickertime` smallint(6) NOT NULL default '0',
  `tickerpass` varchar(50) NOT NULL default '',
  `adminpass` varchar(15) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=3 ;

# --------------------------------------------------------

#
# Struktur för tabell `uwar_news`
#

CREATE TABLE `uwar_news` (
  `newsid` int(20) NOT NULL auto_increment,
  `userid` mediumint(8) unsigned NOT NULL default '0',
  `header` varchar(255) NOT NULL default '',
  `news` text NOT NULL,
  `time` int(11) unsigned NOT NULL default '0',
  `seen` tinyint(1) unsigned NOT NULL default '0',
  `deletetime` smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`newsid`)
) TYPE=MyISAM AUTO_INCREMENT=805 ;

# --------------------------------------------------------

#
# Struktur för tabell `uwar_pscans`
#

CREATE TABLE `uwar_pscans` (
  `userid` mediumint(8) unsigned NOT NULL default '0',
  `scanid` smallint(3) unsigned NOT NULL default '0',
  `amount` mediumint(6) unsigned NOT NULL default '0',
  `eta` smallint(2) unsigned NOT NULL default '0'
) TYPE=MyISAM;

# --------------------------------------------------------

#
# Struktur för tabell `uwar_pships`
#

CREATE TABLE `uwar_pships` (
  `userid` mediumint(8) unsigned NOT NULL default '0',
  `shipid` smallint(3) unsigned NOT NULL default '0',
  `amount` mediumint(6) unsigned NOT NULL default '0',
  `eta` smallint(2) unsigned NOT NULL default '0'
) TYPE=MyISAM;

# --------------------------------------------------------

#
# Struktur för tabell `uwar_scans`
#

CREATE TABLE `uwar_scans` (
  `userid` mediumint(8) unsigned NOT NULL default '0',
  `scanid` smallint(3) unsigned NOT NULL default '0',
  `stock` mediumint(8) unsigned NOT NULL default '0'
) TYPE=MyISAM;

# --------------------------------------------------------

#
# Struktur för tabell `uwar_sgovernment`
#

CREATE TABLE `uwar_sgovernment` (
  `id` int(15) unsigned NOT NULL auto_increment,
  `userid` mediumint(8) unsigned NOT NULL default '0',
  `time` bigint(15) unsigned default NULL,
  `subject` varchar(40) NOT NULL default '',
  `content` text NOT NULL,
  `threadstarter` mediumint(8) unsigned NOT NULL default '0',
  `sysid` smallint(5) unsigned NOT NULL default '0',
  `timereply` bigint(15) unsigned default NULL,
  `replyid` int(15) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=8 ;

# --------------------------------------------------------

#
# Struktur för tabell `uwar_ships`
#

CREATE TABLE `uwar_ships` (
  `userid` mediumint(8) unsigned NOT NULL default '0',
  `shipid` smallint(3) unsigned NOT NULL default '0',
  `stock` mediumint(8) unsigned NOT NULL default '0',
  PRIMARY KEY  (`userid`)
) TYPE=MyISAM;

# --------------------------------------------------------

#
# Struktur för tabell `uwar_sysfund`
#

CREATE TABLE `uwar_sysfund` (
  `sysid` int(15) NOT NULL default '0',
  `sysmercury` bigint(12) NOT NULL default '0',
  `syscobalt` bigint(12) NOT NULL default '0',
  `syshelium` bigint(12) NOT NULL default '0'
) TYPE=MyISAM;

# --------------------------------------------------------

#
# Struktur för tabell `uwar_systems`
#

CREATE TABLE `uwar_systems` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `x` smallint(3) unsigned NOT NULL default '0',
  `y` tinyint(1) unsigned NOT NULL default '0',
  `syscreator` varchar(50) NOT NULL default '0',
  `systype` tinyint(1) unsigned NOT NULL default '0',
  `syspword` varchar(30) NOT NULL default '',
  `sysname` varchar(40) NOT NULL default '',
  `sysmotd` text NOT NULL,
  `syspic` varchar(80) NOT NULL default '',
  `syssize` bigint(15) unsigned NOT NULL default '0',
  `sysscore` bigint(20) unsigned NOT NULL default '0',
  `sysrank` smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=11 ;

# --------------------------------------------------------

#
# Struktur för tabell `uwar_tags`
#

CREATE TABLE `uwar_tags` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `allyname` varchar(50) NOT NULL default '',
  `tag` varchar(15) NOT NULL default '',
  `description` text NOT NULL,
  `motd` text NOT NULL,
  `members` smallint(3) unsigned NOT NULL default '0',
  `password` varchar(30) NOT NULL default '',
  `score` bigint(20) unsigned NOT NULL default '0',
  `size` bigint(20) unsigned NOT NULL default '0',
  `allyrank` bigint(20) NOT NULL default '0',
  `creator` int(10) unsigned NOT NULL default '0',
  `public` int(11) NOT NULL default '0',
  `creatTime` timestamp(14) NOT NULL,
  `modif` int(10) unsigned NOT NULL default '0',
  `modifTime` timestamp(14) NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=17 ;

# --------------------------------------------------------

#
# Struktur för tabell `uwar_tick`
#

CREATE TABLE `uwar_tick` (
  `id` tinyint(1) unsigned NOT NULL default '0',
  `number` int(5) unsigned default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

# --------------------------------------------------------

#
# Struktur för tabell `uwar_tships`
#

CREATE TABLE `uwar_tships` (
  `userid` mediumint(8) unsigned NOT NULL default '0',
  `fleetnum` smallint(2) unsigned NOT NULL default '0',
  `eta` smallint(2) unsigned NOT NULL default '0',
  `r_eta` smallint(5) unsigned NOT NULL default '0',
  `action` char(1) NOT NULL default '0',
  `howlong` tinyint(1) unsigned NOT NULL default '0',
  `targetid` mediumint(9) NOT NULL default '0'
) TYPE=MyISAM;

# --------------------------------------------------------

#
# Struktur för tabell `uwar_users`
#

CREATE TABLE `uwar_users` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `username` varchar(30) NOT NULL default '',
  `password` varchar(30) NOT NULL default '',
  `nick` varchar(30) NOT NULL default '',
  `planet` varchar(30) NOT NULL default '',
  `name` varchar(50) NOT NULL default '',
  `email` varchar(100) NOT NULL default '',
  `city` varchar(30) NOT NULL default '',
  `ip` varchar(16) NOT NULL default '',
  `country` varchar(30) NOT NULL default '',
  `phone` varchar(30) NOT NULL default '',
  `gender` tinyint(1) unsigned NOT NULL default '0',
  `sysid` smallint(5) unsigned NOT NULL default '0',
  `z` tinyint(2) unsigned NOT NULL default '0',
  `mercury` int(10) unsigned NOT NULL default '0',
  `cobalt` int(10) unsigned NOT NULL default '0',
  `helium` int(10) unsigned NOT NULL default '0',
  `score` bigint(11) unsigned NOT NULL default '0',
  `size` int(11) NOT NULL default '0',
  `asteroid_mercury` mediumint(8) unsigned NOT NULL default '0',
  `asteroid_cobalt` mediumint(8) unsigned NOT NULL default '0',
  `asteroid_helium` mediumint(8) unsigned NOT NULL default '0',
  `ui_roids` mediumint(8) unsigned NOT NULL default '0',
  `rank` mediumint(8) unsigned NOT NULL default '0',
  `tag` varchar(15) NOT NULL default '0',
  `tagid` tinyint(5) unsigned NOT NULL default '0',
  `timer` int(11) unsigned NOT NULL default '0',
  `sleep` smallint(5) unsigned NOT NULL default '0',
  `lastsleep` smallint(5) unsigned NOT NULL default '0',
  `closed` smallint(5) unsigned NOT NULL default '0',
  `protection` smallint(5) unsigned NOT NULL default '0',
  `commander` tinyint(1) unsigned NOT NULL default '0',
  `leader` tinyint(2) unsigned NOT NULL default '0',
  `vote` varchar(255) NOT NULL default '',
  `vacation` smallint(5) unsigned NOT NULL default '0',
  `activity` smallint(5) unsigned NOT NULL default '0',
  `online` tinyint(1) NOT NULL default '0',
  `access` tinyint(2) unsigned NOT NULL default '0',
  `deletemode` smallint(6) NOT NULL default '0',
  `design` tinyint(4) NOT NULL default '0',
  `blendingoff` tinyint(4) NOT NULL default '0',
  `stealth` smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=36 ;
