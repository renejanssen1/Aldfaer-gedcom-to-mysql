<?php
define("DATABASE_HOST",     "localhost");
define("DATABASE_USERNAME", "user"); 
define("DATABASE_PASSWORD", "password"); 
define("DATABASE_NAME",     "stamboom"); 

@$db=mysqli_connect(DATABASE_HOST,DATABASE_USERNAME,DATABASE_PASSWORD,DATABASE_NAME);
if (!$db ){
	die('<br><center><font color=red><b>
	De database is nog niet bereikbaar! 
	Of de database is nog niet aangemaakt.<br>
	Of de gegevens in db_login.php zijn niet juist ingevuld.
	</b></font></center>');
}

?>