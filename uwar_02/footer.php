<?
error_reporting(1);
include("logindb.php");
$request = mysql_query("SELECT * FROM uwar_users WHERE id='$Userid'",$db);
$myrow = mysql_fetch_array($request);
if ($myrow["design"] == 2)
{
	?>
</TD>
</TR>
</TABLE>
						</TD>
						<TD bgcolor=#000000>&nbsp;&nbsp;&nbsp;&nbsp;</TD>
					</TR>
				</TABLE>
			</TD>
		</TR>
	</TABLE>
</TD>
</TR>
<TR>
	<TD vAlign=top align=center width=792 bgColor=#333333 colSpan=5>
		<SPAN style="LETTER-SPACING: 2px">
		<FONT style="FONT-SIZE: 7pt" face=Verdana><BR>Copyright © 2003-2004 Xperience, subdivision of pixelrage.ro . All rights reserved.
</SPAN><BR><BR>
	</TD>
</TR>
</TABLE>
<CENTER><BR>
<!-- BEGIN trafic.ro code v2.0 -->
<script>t_rid="pixelragero";</script>
<script src="http://storage.trafic.ro/js/trafic.js"></script>
<noscript><a href="http://www.trafic.ro/top/?rid=pixelragero">
<img src="http://log.trafic.ro/cgi-bin/pl.dll?rid=pixelragero"
border=0 alt="trafic ranking"></a></noscript>
<!-- END trafic.ro code v2.0 --> 
</CENTER>
</BODY>
</HTML>

<?
}
else if ($myrow["design"] == 1)
{
	?>
	     </td></tr>
       </table><br />

      </td>
     </tr></table>
    </td>
    <td background="images2/right-bg.gif"><img alt="" src="images2/right-bg.gif"></td>
   </tr></table>
  </td></tr></table>
 </td></tr>
 <tr><td>
  <table cellspacing="0" cellpadding="0" width="100%"><tr>
   <td width="100%" background="images2/bottom-bg.gif"><table cellspacing="0" cellpadding="0" width="100%"><tr><td><a href="http://www.scarlet-moon.net" target="_blank"><img src="images2/bottom-left.gif" border="0" alt="A Scarlet Moon Media Design"></a></td><td width="100%" align="right">All content herein is copyright © <a href="http://www.universalwar.net" target="_blank">Universal War</a> 2002-2003</td></tr></table>
   </td>
   <td><img alt="" src="images2/bottom-right.gif"></td>
  </tr></table>
 </td></tr></table>

 <center><font color="#FFFFFF" size="2"><b>Copyright © 2003-2004 Xperience, subdivision of pixelrage.ro . All rights reserved.</center>
 <CENTER><BR>
<!-- BEGIN trafic.ro code v2.0 -->
<script>t_rid="pixelragero";</script>
<script src="http://storage.trafic.ro/js/trafic.js"></script>
<noscript><a href="http://www.trafic.ro/top/?rid=pixelragero">
<img src="http://log.trafic.ro/cgi-bin/pl.dll?rid=pixelragero"
border=0 alt="trafic ranking"></a></noscript>
<!-- END trafic.ro code v2.0 --> 
</CENTER>

</body>
</html>
<?
}
else if ($myrow["design"] == 0)
{
	?>
	     								</td>
							</tr>
						</table>
					
					</td>
					<td class="bar"></td>
				</tr>
				<tr>
					<td class="bottom" colspan="5"><img src="images3/bottom.jpg" alt="" /></td>
				</tr>
			</table>
			 <center><br />
			<!-- BEGIN trafic.ro code v2.0 -->
			<script>t_rid="pixelragero";</script>
			<script src="http://storage.trafic.ro/js/trafic.js"></script>
			<noscript><a href="http://www.trafic.ro/top/?rid=pixelragero">
			<img src="http://log.trafic.ro/cgi-bin/pl.dll?rid=pixelragero"
			border=0 alt="trafic ranking"></a></noscript>
			<!-- END trafic.ro code v2.0 --> 
			</center>
			</body>
			</html>


</body>
</html>
<?
}
ob_end_flush();
?>