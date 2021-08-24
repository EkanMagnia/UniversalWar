<?php

/*** Configuration file - Universal War Project Manager***/

$_CFG = array();

/*** Absolute url to project ***/
$_CFG["absolute"] = "http://cepe-offer.mine.nu/portal/";

/*** Paths, change to correct values. Note: add a trailing slash. ***/
$_CFG["paths"]["img"] = "images/";
$_CFG["paths"]["pages"] = "pages/";
$_CFG["paths"]["styles"] = "styles/";
$_CFG["paths"]["screens"] = "screens/";
$_CFG["paths"]["includes"] = "includes/";
$_CFG["paths"]["scripts"] = "scripts/";

/*** Database settings, change to correct values ***/
$_CFG["db"]["host"] = "localhost";
$_CFG["db"]["username"] = "root";
$_CFG["db"]["password"] = "";
$_CFG["db"]["dbname"] = "portal";

$_CFG["db"]["host2"] = "localhost";
$_CFG["db"]["username2"] = "root";
$_CFG["db"]["password2"] = "";
$_CFG["db"]["dbname2"] = "uwar_portal";

/*** Database tables, change to correct values ***/
$_CFG["db"]["announcements"] = "announcements";
$_CFG["db"]["admins"] = "admins";
$_CFG["db"]["events"] = "events";
$_CFG["db"]["schedule"] = "schedule";
$_CFG["db"]["articles"] = "articles";
$_CFG["db"]["comments"] = "comments";
$_CFG["db"]["columns"] = "columns_posts";
$_CFG["db"]["poll"] = "poll";
//$_CFG["db"]["users"] = "users";
$_CFG["db"]["users"] = "phpbb_users";


?>
