<?php
session_cache_limiter ('private, must-revalidate'); 
session_start();
include_once("db_login.php"); 

print	'<!DOCTYPE HTML PUBLIC "-//W3C//DTDHTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">';
echo "<html>\r\n";
print "<head>\r\n";
print "<meta http-equiv='content-type' content='text/html; charset=utf-8'>\r\n";
print "<title>gedcom inlezen</title>\r\n";
echo "<link href='gedcom.css' rel='stylesheet' type='text/css'>\r\n";
echo "</head>\r\n";
echo "<body>\r\n";

echo "<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"100%\">\n"; 
echo "<tr><td bgcolor='#ffe599' valign='center' height='70' ><span style='font-size:18pt;font-weight:bold'><center>";
if (isset($_GET['page'])){ $page=$_GET['page']; }
if (isset($_POST['page'])){ $page=$_POST['page']; }
echo "<a href='index.php?page=tree&amp;step1=read_gedcom'>Gedcom inlezen</a>\n"; 
echo "</center></span></td></tr>\n"; 
echo '<td  valign="top" height="620">'; 
print '<div id="content">';
print '<DIV id=mainmenu_centerbox>';
echo '<div>';
if ($page=='tree'){ include_once ("bin/import.php"); }
echo '</div>';
echo '</td></tr>'; 
echo "</table>\n";
echo "</body>\n"; 
echo "</html>\n"; 
?>
