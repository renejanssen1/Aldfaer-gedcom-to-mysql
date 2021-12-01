<?php
@ini_set('auto_detect_line_endings', TRUE);
@set_time_limit(3000);
$phpself = $_SERVER['PHP_SELF'];

if (isset($_POST['step1'])){ $step1=$_POST['step1']; }
if (isset($_GET['step1'])){ $step1=$_GET['step1']; }
if (isset($step1)){
	print '<form method="post" action="'.$phpself.'">';
	echo '<p>Plaats een gedcom bestand in de map gedcom_files<br>';
	print '<p>Gedcom bestand selecteren:<br>';	
	echo '<input type="hidden" name="page" value="'.$page.'">';
	$gedcom_directory="./gedcom_files";

	$dh  = opendir($gedcom_directory);
	while (false !== ($filename = readdir($dh))) {
		if (strtolower(substr($filename, -3)) == "ged"){
			$filenames[]=$filename;
		}
	}

	if (isset($filenames)){ sort($filenames); }
		if ($filenames!=NULL){
			print '<select size="1" name="gedcom_file">';
			for ($i=0; $i<count($filenames); $i++){
				print '<option value="'.$filenames[$i].'">'.$filenames[$i].'</option>';
			}
			print '</select>';
			print '<p><input type="Submit" name="step2" value="Stap 2">';
			print '</form>';
		}else{
			print '<b>Geen gedcom gevonden!</b><br>';
		}	
}

if (isset($_POST['step2'])){
    include_once("tabel.php");
	include_once('gedcom_cls.php');
	$gedcom_cls = New gedcom_cls;
	echo '<b><form>Gedcom bestand verwerken:<br>';
	echo 'Het kan even duren voor de verwerking klaar is!!</b><br>';

	$start_time=time();
	$process_gedcom="";
	$buffer2="";
	$gedcom='./gedcom_files/'.$_POST["gedcom_file"].'';
	$_SESSION['save_total']='0';
	$pers = 0; $gez = 0; $text = 0; $bron = 0;

	if (!isset($_POST['show_gedcomnumbers'])){
		echo '<div id="progress" style="width:500px;border:1px solid #ccc;"></div>';
		echo '<!-- Progress information -->';
		echo '<div id="information" style="width"></div>';
		$total=0;
		if($_SESSION['save_total']=='0') { 
			$handle = fopen($gedcom, "r");
			while(!feof($handle)){
				$line = fgets($handle);
				$total++;
			}
			$_SESSION['save_total']=$total;
			fclose($handle);
		}
		$total = $_SESSION['save_total']; 
		$step = round($total/100); 
	}		
	$handle = fopen($gedcom, "r");
	$level0='';
	
	while (!feof($handle)) {
		$buffer = fgets($handle, 4096);
		$buffer=rtrim($buffer,"\n\r"); 
		$buffer=ltrim($buffer," ");  
	
		$start_gedcom="";
		if ($start_gedcom==''){
			if(substr($buffer,0,3) == pack("CCC",0xef,0xbb,0xbf)){
				$buffer = substr($buffer,3);
			}
		}
		
		if ( substr($buffer, 0, 3)=='0 @' OR $buffer=="0 TRLR"){ $start_gedcom=1; }
		if ($start_gedcom){
			if ($process_gedcom=="persoon"){
				$buffer2=addslashes($buffer2);
				$gedcom_cls -> process_persoon($buffer2); $pers++;
				$process_gedcom="";
				$buffer2="";
			}
	
			elseif ($process_gedcom=="gezin"){
				$buffer2=addslashes($buffer2);
				$gedcom_cls -> process_gezin($buffer2); $gez++;
				$process_gedcom="";
				$buffer2="";
			}
	
			elseif ($process_gedcom=="tekst"){
				$buffer2=addslashes($buffer2);
				$gedcom_cls -> process_tekst($buffer2); $text++;
				$process_gedcom="";
				$buffer2="";
			}
	
			elseif ($process_gedcom=="bron"){
				$buffer2=addslashes($buffer2);
				$gedcom_cls -> process_bron($buffer2); $bron++;
				$process_gedcom="";
				$buffer2="";
			}
		}
	
		if (substr($buffer, -6, 6)=='@ INDI'){
			$process_gedcom="persoon";
			$buffer2="";
		}
		elseif (substr($buffer, -5, 5)=='@ FAM'){
			$process_gedcom="gezin";
			$buffer2="";
		}
		elseif (substr($buffer, 0, 3)=='0 @'){
			if (strpos($buffer,'@ NOTE')>1){
				$process_gedcom="tekst";
				$buffer2="";
			}

			if (substr($buffer, -6, 6)=='@ SOUR'){
				$process_gedcom="bron";
				$buffer2="";
			}		
		}
	
		$buffer2=$buffer2.$buffer."\n";

		if (substr($buffer, 0, 1)=='0'){ $level0=substr($buffer,2,6); }
	
		if ($level0=='HEAD' AND substr($buffer,2,4)=='SOUR'){
			$gen_program=substr($buffer,7);
			$_SESSION['save_gen_program']=$gen_program;
			print "<br>Gedcom bestand: <b>$gen_program</b><br>";
		}

		if (!isset($_POST['show_gedcomnumbers'])) {
			$i++; 
			if($i%$step==0) {
				$perc+=1;
				echo '<script language="javascript">
				document.getElementById("progress").innerHTML="<div style=\"width:'.$perc."%".';background-color:#2986cc;\">&nbsp;</div>";
				document.getElementById("information").innerHTML="Voortgang '.$pers.' personen  '.$gez.' gezinnen '.$text.' teksten '.$bron.' bronnen ingelezen.";
				</script>';
				echo str_repeat(' ',1024*64);
				flush(); 
			}
		}				
	}
	fclose($handle);
	$end_time=time();
	echo '<br>Inlezen bestand kostte: '.($end_time-$start_time).' seconden<br>';
	print '<b>Klaar! </b><br>';
	print '</form>';
}
?>


