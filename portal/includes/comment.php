<?php

class Comment {

    var $section, $belongto;

    //class constructor
    function Comment($section, $belongto) {
		$this->section = $section;
		$this->belongto = $belongto;
    }

    //prints
    function printComments() {
		global $_CFG;
		$commentsSQL = mysql_query('SELECT * FROM '.$_CFG["db"]["comments"].' WHERE section="'.$this->section.'" AND belongto='.$this->belongto.' ');
        if (mysql_num_rows($commentsSQL) > 0) {
        	echo '<table border="0" cellspacing="2" cellpadding="5" width="100%">';
        	
        	while($comment = mysql_fetch_array($commentsSQL)) {
				
        		echo '<tr><td class="rowTopic" style="text-align: left;">';	
	            echo '<sb>#'.$comment["authorid"].' wrote on '.date("Y.m.d H:i:s", $comment["date"]).':</b><br />';
	            echo '</td></tr><tr><td class="row1">';
	            echo $comment["comment"].'<br /><br />';
	            echo '</td></tr>';
	            
	        }
	        echo '</table>';
	        
		} else {
			echo 'No comments posted.';	
		}
    }

    function addComments($comment) {
    	
		global $_CFG;
		
    	$request = mysql_query('SELECT MAX(authorid) FROM '.$_CFG["db"]["comments"].' WHERE section="'.$this->section.'" AND belongto='.$this->belongto.' LIMIT 1');
    	$authorid = mysql_fetch_array($request);
    	$posts = $authorid[0] + 1;
    	$date = time();
        
        mysql_query("INSERT INTO ".$_CFG["db"]["comments"]." (authorid, belongto, section, date, comment) VALUES ('$posts', '$this->belongto', '$this->section', '$date', '$comment' ) ");
        echo '<p>&nbsp;&nbsp;Comment posted, thank you!</p>';

    }

}

?> 