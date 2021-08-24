<!DOCtype html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>Universal War Portal</title>
<meta http-equiv="Content-type" content="text/html; charset=iso-8859-1">
<link href="<?=$_CFG["paths"]["styles"]?>main.css" rel="stylesheet" type="text/css">
<script src="<?=$_CFG["paths"]["scripts"]?>main.js" type="text/javascript"></script>

</head>
<body topmargin="0" leftmargin="0">

<table cellspacing="0" cellpadding="0" style="width: 100%; height: 100%;" border="0">
	<tr>
		<td width="523" height="33" style="text-align: left;">
			<img src="<?=$_CFG["paths"]["img"]?>toprow_left.gif" width="523" height="33" alt="Universal War" />
		</td>
		<td height="33" style="text-align: left; background-image: url(<?=$_CFG["paths"]["img"]?>toprow_right.gif); background-repeat: repeat-x;" colspan="3">
			<img src="<?=$_CFG["paths"]["img"]?>toprow_middle.gif" width="94" height="33" alt="" />
		</td>
	</tr>
	<tr>
		<td width="523" height="124" style="text-align: left;">
			<img src="<?=$_CFG["paths"]["img"]?>logorow_left.gif" width="523" height="124" alt="" />
		</td>
		<td height="124" width="100%" style="text-align: left; background-image: url(<?=$_CFG["paths"]["img"]?>logorow_middle_left.gif); background-repeat: repeat-x;">
			<img src="<?=$_CFG["paths"]["img"]?>pix.gif" width="1" height="1" alt="" />
		</td>
		<td width="16" height="124" style="text-align: left;">
			<img src="<?=$_CFG["paths"]["img"]?>logorow_middle_right.gif" width="16" height="124" alt="" />
		</td>
		<td width="200" height="124" style="text-align: left; background-image: url(<?=$_CFG["paths"]["img"]?>logorow_right.gif); background-repeat: repeat-x;">
			<form action="<?=$PHP_SELF?>?page=login&action=login" method="post">
			<table border="0" cellspacing="5" cellpadding="0" style="width: 191px;">
				<?
					if ($_SESSION["userlogged"] ==1 ) {
						?>
						<tr>
							<td style="text-align: center;">You are logged in as <b/><?=$_SESSION["username"]?></b></td>
						</tr>
						<tr>
							<td style="text-align: center;"><a href="<?=$PHP_SELF?>?page=logout">Logout</a></b></td>
						</tr>						
						
						<?
					} else {
				?>	
				<tr>
					<td><font class="font">username:</font></td>
					<td><input type="text" name="username" value="" /></td>
				</tr>
				<tr>
					<td><font class="font">password:</font></td>
					<td><input type="password" name="password" value="" /></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td><input type="submit" value="Portal Login" style="width: 110px; height: 20px; border: 1px #444444;" /></td>
				</tr>
				<tr>
					<td colspan="2" style="text-align: center;">Not registered? <a href="http://universalwar.pixelrage.ro/forum/profile.php?mode=register">Signup here!</a></td>
				</tr>
				<?
					}
				?>
			</table>
			</form>			
		</td>
	</tr>
	<tr>
		<td colspan="4" height="13" style="text-align: left; background-image: url(<?=$_CFG["paths"]["img"]?>bluerow_bg.gif); background-repeat: repeat-x;">
			<img src="<?=$_CFG["paths"]["img"]?>pix.gif" width="1" height="1" alt="" />
		</td>
	</tr>
	<tr>
		
	</tr>
	<tr>
		<!-- Left column contents-->
		<td colspan="4" height="100%">
			<table border="0" style="width: 100%; height: 100%;" cellspacing="0" cellpadding="0">
				<tr>
					<td width="130" height="100%" style="text-align: left; vertical-align: top; background-image: url(<?=$_CFG["paths"]["img"]?>menurow_bg.gif); background-repeat: repeat-y;">
						<br /><br />
						<table border="0" cellspacing="0" cellpadding="0" align="right" style="width: 130px;">
						<!-- Menu -->
							<tr>
								<td width="9">
									<img src="<?=$_CFG["paths"]["img"]?>pix.gif" width="1" height="1" alt="" />
								</td>
								<td width="4" height="21" style="text-align: right;">
									<img src="<?=$_CFG["paths"]["img"]?>menu_topleft.gif" width="4" height="21" alt="" />
								</td>
								<td width="109" height="21">
									<img src="<?=$_CFG["paths"]["img"]?>menu_menu.gif" width="109" height="21" alt="" />
								</td>
								<td width="6" height="21" style="text-align: left;">
									<img src="<?=$_CFG["paths"]["img"]?>menu_topright.gif" width="6" height="21" alt="" />
								</td>
								<td width="2">
									<img src="<?=$_CFG["paths"]["img"]?>pix.gif" width="1" height="1" alt="" />
								</td>
							</tr>
							<tr>
								<td width="9">
									<img src="<?=$_CFG["paths"]["img"]?>pix.gif" width="1" height="1" alt="" />
								</td>
								<td width="4" style="background-image: url(<?=$_CFG["paths"]["img"]?>menu_contentleft.gif); background-repeat: repeat-y;">
									<img src="<?=$_CFG["paths"]["img"]?>pix.gif" width="1" height="1" alt="" />
								</td>
								<td width="109" style="background-image: url(<?=$_CFG["paths"]["img"]?>menu_contentbg.gif); background-repeat: repeat-y;">
									<table border="0" cellspacing="2" cellpadding="0" width="90%">
									<?
										/*if ($_SESSION["userlogged"] == 1) {
											?>
											<tr>
												<td width="7" height="6">	
													<img src="<?=$_CFG["paths"]["img"]?>arrow.gif" width="7" height="6" alt="">
												</td>
												<td><a href="<?=$PHP_SELF?>?page=profile">My settings</a></td>
											</tr>		
											<?
										}*/
									
												
										if ($_SESSION["userlogged"] != 1) {
											?>
										<tr>
											<td width="7" height="6">	
												<img src="<?=$_CFG["paths"]["img"]?>arrow.gif" width="7" height="6" alt="">
											</td>
											<td><a href="<?=$PHP_SELF?>?page=register">Signup</a></td>
										</tr>
										<?
										
										}
										?>										
										<tr>
											<td width="7" height="6">	
												<img src="<?=$_CFG["paths"]["img"]?>arrow.gif" width="7" height="6" alt="">
											</td>
											<td><a href="<?=$PHP_SELF?>?page=main">Announcements</a></td>
										</tr>
										<tr>
											<td width="7" height="6">	
												<img src="<?=$_CFG["paths"]["img"]?>arrow.gif" width="7" height="6" alt="">
											</td>
											<td><a href="<?=$PHP_SELF?>?page=screenshots">Screenshots</a></td>
										</tr>
										<tr>
											<td width="7" height="6">	
												<img src="<?=$_CFG["paths"]["img"]?>arrow.gif" width="7" height="6" alt="">
											</td>
											<td><a href="<?=$PHP_SELF?>?page=features">Features</a></td>
										</tr>
										<tr>
											<td width="7" height="6">	
												<img src="<?=$_CFG["paths"]["img"]?>arrow.gif" width="7" height="6" alt="">
											</td>
											<td><a href="<?=$PHP_SELF?>?page=calendar">Calendar</a></td>
										</tr>
										<tr>
											<td width="7" height="6">	
												<img src="<?=$_CFG["paths"]["img"]?>arrow.gif" width="7" height="6" alt="">
											</td>
											<td><a href="<?=$PHP_SELF?>?page=schedule">Schedule</a></td>
										</tr>
										<tr>
											<td width="7" height="6">	
												<img src="<?=$_CFG["paths"]["img"]?>arrow.gif" width="7" height="6" alt="">
											</td>
											<td><a href="<?=$PHP_SELF?>?page=articles">Articles</a></td>
										</tr>
										<tr>
											<td width="7" height="6">	
												<img src="<?=$_CFG["paths"]["img"]?>arrow.gif" width="7" height="6" alt="">
											</td>
											<td><a href="<?=$PHP_SELF?>?page=columns">Columns</a></td>
										</tr>
										<tr>
											<td width="7" height="6">	
												<img src="<?=$_CFG["paths"]["img"]?>arrow.gif" width="7" height="6" alt="">
											</td>
											<td><font class="font">F.A.Q</font></td>
										</tr>
										<tr>
											<td width="7" height="6">	
												<img src="<?=$_CFG["paths"]["img"]?>arrow.gif" width="7" height="6" alt="">
											</td>
											<td><a href="http://universalwar.pixelrage.ro/game-manual/" target="_blank">Manual</a></td>
										</tr>
										<tr>
											<td width="7" height="6">	
												<img src="<?=$_CFG["paths"]["img"]?>arrow.gif" width="7" height="6" alt="">
											</td>
											<td><a href="http://universalwar.pixelrage.ro/forum/" target="_blank">Forums</a></td>
										</tr>
										<tr>
											<td width="7" height="6">	
												<img src="<?=$_CFG["paths"]["img"]?>arrow.gif" width="7" height="6" alt="">
											</td>
											<td><font class="font">History</font></td>
										</tr>
										<tr>
											<td width="7" height="6">	
												<img src="<?=$_CFG["paths"]["img"]?>arrow.gif" width="7" height="6" alt="">
											</td>
											<td><a href="<?=$PHP_SELF?>?page=statistics">Statistics</a></td>
										</tr>
										<tr>
											<td width="7" height="6">	
												<img src="<?=$_CFG["paths"]["img"]?>arrow.gif" width="7" height="6" alt="">
											</td>
											<td><a href="<?=$PHP_SELF?>?page=poll">Poll</a></td>
										</tr>
										<tr>
											<td width="7" height="6">	
												<img src="<?=$_CFG["paths"]["img"]?>arrow.gif" width="7" height="6" alt="">
											</td>
											<td><font class="font">Battle Calc.</font></td>
										</tr>
										<tr>
											<td width="7" height="6">	
												<img src="<?=$_CFG["paths"]["img"]?>arrow.gif" width="7" height="6" alt="">
											</td>
											<td><a href="<?=$PHP_SELF?>?page=credits">Credits</a></td>
										</tr>
									</table>
								</td>
								<td width="6" style="background-image: url(<?=$_CFG["paths"]["img"]?>menu_contentright.gif); background-repeat: repeat-y;">
									<img src="<?=$_CFG["paths"]["img"]?>pix.gif" width="1" height="1" alt="" />
								</td>
								<td width="2">
									<img src="<?=$_CFG["paths"]["img"]?>pix.gif" width="1" height="1" alt="" />
								</td>
							</tr>
							<tr>
								<td width="9">
									<img src="<?=$_CFG["paths"]["img"]?>pix.gif" width="1" height="1" alt="" />
								</td>
								<td width="4" height="12" style="text-align: right;">
									<img src="<?=$_CFG["paths"]["img"]?>menu_bottomleft.gif" width="4" height="12" alt="" />
								</td>
								<td width="109" height="12">
									<img src="<?=$_CFG["paths"]["img"]?>menu_bottom.gif" width="109" height="12" alt="" />
								</td>
								<td width="6" height="12" style="text-align: left;">
									<img src="<?=$_CFG["paths"]["img"]?>menu_bottomright.gif" width="6" height="12" alt="" />
								</td>
								<td width="2">
									<img src="<?=$_CFG["paths"]["img"]?>pix.gif" width="1" height="1" alt="" />
								</td>
							</tr>
							<tr>
								<td colspan="5" height="20">&nbsp;</td>
							</tr>
							<!-- poll -->
							<tr>
								<td width="9">
									<img src="<?=$_CFG["paths"]["img"]?>pix.gif" width="1" height="1" alt="" />
								</td>
								<td width="4" height="21" style="text-align: right;">
									<img src="<?=$_CFG["paths"]["img"]?>menu_topleft.gif" width="4" height="21" alt="" />
								</td>
								<td width="109" height="21">
									<img src="<?=$_CFG["paths"]["img"]?>menu_poll.gif" width="109" height="21" alt="" />
								</td>
								<td width="6" height="21" style="text-align: left;">
									<img src="<?=$_CFG["paths"]["img"]?>menu_topright.gif" width="6" height="21" alt="" />
								</td>
								<td width="2">
									<img src="<?=$_CFG["paths"]["img"]?>pix.gif" width="1" height="1" alt="" />
								</td>
							</tr>
							<tr>
								<td width="9">
									<img src="<?=$_CFG["paths"]["img"]?>pix.gif" width="1" height="1" alt="" />
								</td>
								<td width="4" style="background-image: url(<?=$_CFG["paths"]["img"]?>menu_contentleft.gif); background-repeat: repeat-y;">
									<img src="<?=$_CFG["paths"]["img"]?>pix.gif" width="1" height="1" alt="" />
								</td>
								<td width="109" style="background-image: url(<?=$_CFG["paths"]["img"]?>menu_contentbg.gif); background-repeat: repeat-y;">
									<table border="0" cellspacing="2" cellpadding="0" width="90%">
										<tr>
											<td style="text-align: center;">
											
												<?
													$pollQuery = mysql_query("SELECT * FROM ".$_CFG["db"]["poll"]." WHERE active=1");
													$poll = mysql_fetch_array($pollQuery);
													if ($poll["option2"]) $options = 2;
													if ($poll["option3"]) $options = 3;
													if ($poll["option4"]) $options = 4;
													if ($poll["option5"]) $options = 5;
												?>
											
												<font class="font"><?=$poll["question"]?></font><br /><br />
											</td>
										</tr>
										<tr>
											<td>
												<form action="<?=$PHP_SELF?>?page=poll&action=vote" method="post">
												
												<?
													for( $x = 1; $x <= $options; $x++ ) {
													
															echo '<input type="radio" value="'.$x.'" name="option" style="background-color: #454545; width: 10px;" /><font class="font">   '.$poll["option$x"].'</font><br />';
														
													}
												?>
												<p style="text-align: center;"><input type="image" src="<?=$_CFG["paths"]["img"]?>vote.gif" style="width: 42px; height: 14px; "/></p>
											</form>
											</td>
										</tr>
									</table>
								</td>
								<td width="6" style="background-image: url(<?=$_CFG["paths"]["img"]?>menu_contentright.gif); background-repeat: repeat-y;">
									<img src="<?=$_CFG["paths"]["img"]?>pix.gif" width="1" height="1" alt="" />
								</td>
								<td width="2">
									<img src="<?=$_CFG["paths"]["img"]?>pix.gif" width="1" height="1" alt="" />
								</td>
							</tr>
							<tr>
								<td width="9">
									<img src="<?=$_CFG["paths"]["img"]?>pix.gif" width="1" height="1" alt="" />
								</td>
								<td width="4" height="12" style="text-align: right;">
									<img src="<?=$_CFG["paths"]["img"]?>menu_bottomleft.gif" width="4" height="12" alt="" />
								</td>
								<td width="109" height="12">
									<img src="<?=$_CFG["paths"]["img"]?>menu_bottom.gif" width="109" height="12" alt="" />
								</td>
								<td width="6" height="12" style="text-align: left;">
									<img src="<?=$_CFG["paths"]["img"]?>menu_bottomright.gif" width="6" height="12" alt="" />
								</td>
								<td width="2">
									<img src="<?=$_CFG["paths"]["img"]?>pix.gif" width="1" height="1" alt="" />
								</td>
							</tr>
							<tr>
								<td colspan="5" height="20">&nbsp;</td>
							</tr>
						</table>
					</td>
					<td width="46" style="text-align: left; vertical-align: top; background-image: url(<?=$_CFG["paths"]["img"]?>contentrow_left.gif); background-repeat: repeat-y;">
						&nbsp;		
					</td>
					<td style="background-color: #000000; vertical-align: top;">
						<br /><br />