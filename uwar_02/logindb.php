<?
/*** Database settings ***/

//www.universawar.net alpha db settings
/*	$_DB_host = '62.70.14.32';
	$_DB_username = 'universalwar';
	$_DB_password = 'shipstats';
	$_DB_database = 'dor';

	//www.universawar.net  beta db settings
	$_DB_host = '62.70.14.32';
	$_DB_username = 'univers_beta';
	$_DB_password = 'betatest';
	$_DB_database = 'univers_beta';

	//localhost db settings
	$_DB_host = 'localhost';
	$_DB_username = 'root';
	$_DB_password = '';
	$_DB_database = 'uwar';
*/
	$_DB_host = 'localhost';
	$_DB_username = 'root';
	$_DB_password = '';
	$_DB_database = 'universalwar';
	
	# Syntax:
	# mysql_connect("host", "username", "password");
	$db = mysql_connect($_DB_host, $_DB_username, $_DB_password);
	mysql_select_db($_DB_database,$db);

?>