<?php

#mysql_connect(host, user, pass)
#mysql_select_db(db)


if (!@mysql_connect($_CFG["db"]["host"], $_CFG["db"]["username"], $_CFG["db"]["password"])) {
	$errorMsg = "Unable to connect to database!"; 
	fatalError($errorMsg);
}
if (!@mysql_select_db($_CFG["db"]["dbname"])) {
	$errorMsg = "Unable to select database!"; 
	fatalError($errorMsg);
}
	

?>