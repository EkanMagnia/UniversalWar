<?php
//sessions are destroyed in index.php in order to be able to print the correct menu links
drawHeader($_GET["page"]);
?>

<table border="0" cellspacing="20" cellpadding="0" style="width: 100%; text-align: center;">
	<tr>
		<td style="text-align: left;">
			<font class="font">All sessions are cleared, you are now logged out.</font>
		</td>
	</tr>
</table>

<?

drawFooter();

?>