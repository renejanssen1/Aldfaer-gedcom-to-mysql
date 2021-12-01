<?php
class gedcom_cls {
// ************************************************************************************************
// *** Personen inlezen ***
// ************************************************************************************************
function process_persoon($person_array){
	global $level1, $db;
	$line2=explode("\n",$person_array);
	unset ($person); 
	$tussen=''; $tekst=''; $eigen=''; $soort=''; $voornaam=''; $achternaam=''; $roepnaam=''; $fams=''; $famc='';
	$geboorte_datum=''; $geboorte_tijd=''; $geboorte_plaats=''; $geboorte_tekst=''; $geboorte_bron=''; $levenloos='';
	$geboorte_reg_datum=''; $geboorte_reg_bron=''; $doop_datum=''; $doop_plaats=''; $doop_tekst=''; $doop_bron=''; $religie='';
	$sterf_datum=''; $sterf_tijd=''; $sterf_plaats=''; $sterf_tekst=''; $sterf_bron='';
	$sterf_reg_datum=''; $sterf_reg_bron='';
	$graf_datum=''; $graf_plaats=''; $graf_tekst=''; $graf_bron=''; $crematie='';
	$sterf_reden=''; $geslacht=''; $foto=''; $wijzig_datum=''; $wijzig_tijd='';
	$levend=''; $beroep=''; $straat=''; $plaats=''; $datum=''; $adres='';
	$event=''; $heerlijk=''; $opleid=''; $titel=''; $event_nr=0; $event_items=0;
	$event_qry=mysqli_query($db,"SELECT id FROM getuigen ORDER BY id DESC LIMIT 0,1");
	$eventDb=mysqli_fetch_object($event_qry);
	if ($eventDb){ $event_items=$eventDb->id; }

	// *** Gedcomnummer ***
	$buffer=$line2[0];
	$buffer = str_replace("_", "", $buffer); 
	$gedomnr=substr($buffer,3,-6);

	$level1=""; 


	$loop=count($line2)-2;
	for ($z=2; $z<=$loop; $z++){
		$buffer=$line2[$z];		
		$buffer=rtrim($buffer,"\n\r");  
		$buffer1=substr($buffer,0,1);
		$buffer5=substr($buffer,0,5);
		$buffer6=substr($buffer,0,6);
		$buffer7=substr($buffer,0,7);
		$buffer8=substr($buffer,0,8);
 		if ($buffer1=='1'){
			$level1=rtrim(substr($buffer,2,5));
			$event=''; $event2='1';
		}

		// *** Wijzigings datum / tijd ***
		if ($level1=='_NEW'){
			if ($buffer6=='1 _NEW'){  }
			if ($buffer6=='2 DATE'){  $wijzig_datum=substr($buffer,7); }
			if ($buffer6=='3 TIME'){  $wijzig_tijd=substr($buffer,7); }
		}

		// *** Ouders ***
		if ($buffer8=='1 FAMC @'){
			if (!$famc) {$famc=substr($buffer,8,-1);}
		}
		if ($buffer7=='2 PEDI '){ // legal, steph, adopted, foster
			$soort=substr($buffer,7);
			if ($soort=='legal') { $soort='wettig';}
			if ($soort=='steph') { $soort='stief';}
			if ($soort=='adopted') { $soort='adaptie';}
			if ($soort=='foster') { $soort='pleeg';}
		}		
		// *** Eigen gezin ***
		if ($buffer8=='1 FAMS @'){
			$fams.=substr($buffer,8,-1);
			$fams.=';';
		}

		// *** Voor tussen en achternaam en roepnaam***
		if ($level1=='NAME'){
			if ($buffer6=='1 NAME'){			
				$name = str_replace("_", " ", $buffer);
				$name = str_replace("~", " ", $name);
				$position = strpos($name,"/");
				$voornaam=substr($name,7,$position-7);
				$achternaam=substr($name,$position+1,-1);
			}
			// *** tussen ***
			if ($buffer6=='2 SPFX'){ $tussen=substr($buffer,7); 
				$length=strlen($tussen); $length=($length + 1);
				$achternaam=substr($achternaam,$length);}			
			// *** Roepnaam ***
			if ($buffer6=='2 NICK'){ $roepnaam=substr($buffer,7); }			
		}

		// *** Eigen code ***
		if ($buffer6=='1 REFN'){ $eigen=substr($buffer,7); }

		// *** Notitie bij persoon ***
		if ($level1=='NOTE'){ $tekst=substr($buffer,8,-1); }

		// *** Geboorte ***
		if ($level1=='BIRT'){
			if ($buffer6=='2 DATE'){ $geboorte_datum=substr($buffer, 7); }
			if (substr($buffer,0,15)=='2 _ALDFAER_TIME'){$geboorte_tijd=substr($buffer, 16); }
			if ($buffer6=='2 PLAC'){$geboorte_plaats=substr($buffer, 7); }
			if ($buffer6=='2 NOTE') {$geboorte_tekst=substr($buffer,8,-1);}
			if ($buffer6=='2 SOUR') {$geboorte_bron=substr($buffer,8,-1);}
			if (substr($buffer,0,16)=='2 TYPE stillborn'){$levenloos='y';}
		}

		// *** Geboorte aangifte***
		if ($level1=='EVEN' AND substr($buffer,0,25)=='2 TYPE birth registration'){ 
      $level2=2; } 
			if ($level1=='EVEN' AND $level2=='2'){
				if ($buffer6=='2 DATE'){ $geboorte_reg_datum=substr($buffer, 7); }
        if ($buffer6=='2 SOUR') {$geboorte_reg_bron=substr($buffer,8,-1);}
      } else {
        $level2='';
      }

		// *** Doop ***
		if ($buffer6=='1 RELI'){ $religie=substr($buffer, 7);}

		if ($level1=='CHR'){
			if ($buffer6=='2 DATE'){  $doop_datum=substr($buffer, 7); }
			if ($buffer6=='2 PLAC'){  $doop_plaats=substr($buffer, 7); }
			if ($buffer6=='2 NOTE'){$doop_tekst=substr($buffer,8,-1);}
			if ($buffer6=='2 SOUR'){$doop_bron=substr($buffer,8,-1);}
		}

		// *** Gestorven ***
		if ($level1=='DEAT'){
			$levend='deceased';
			if ($buffer6=='2 DATE'){  $sterf_datum=substr($buffer, 7); }
			if (substr($buffer,0,15)=='2 _ALDFAER_TIME'){  $sterf_tijd=substr($buffer, 16); }
			if ($buffer6=='2 PLAC'){ $sterf_plaats=substr($buffer, 7); }
			if ($buffer6=='2 NOTE'){$sterf_tekst=substr($buffer,8,-1);	}
			if ($buffer6=='2 SOUR'){$sterf_bron=substr($buffer,8,-1);}
			if ($buffer6=='2 CAUS'){  $sterf_reden=rtrim(substr($buffer, 7)); }
		}

		// *** Overlijden aangifte***
		if ($level1=='EVEN' AND substr($buffer,0,25)=='2 TYPE death registration'){ 
      $level3=2; } 
			if ($level1=='EVEN' AND $level3=='2'){
				if ($buffer6=='2 DATE'){ $sterf_reg_datum=substr($buffer, 7); }
        if ($buffer6=='2 SOUR') {$sterf_reg_bron=substr($buffer,8,-1);}
      } else {
        $level3='';
      }
      
		// *** Begraven ***
		if ($buffer6=='1 CREM'){ $level1='BURI'; $buffer='2 TYPE cremation'; }
		if ($level1=='BURI'){
			$levend='deceased';
			if ($buffer6=='2 DATE'){  $graf_datum=substr($buffer, 7); }
			if ($buffer6=='2 PLAC'){$graf_plaats=substr($buffer, 7); }
			if ($buffer6=='NOTE'){$graf_tekst=substr($buffer,8,-1);}
			if ($buffer6=='SOUR'){$graf_bron=substr($buffer,8,-1);	}
			if (substr($buffer,0,16)=='2 TYPE cremation'){  $crematie='1'; }
		}

		// *** Getuigen ***
		if ($level1=='ASSO'){
			if ($buffer6=='1 ASSO'){				
				$event_nr++;
				$event_person_id[$event_nr]=substr($buffer,8,-1);
				$event_family_id[$event_nr]='';
				$event_kind[$event_nr]='getuige';
				$event_event[$event_nr]=''.$gedomnr.'';
			}
			if ($buffer=='2 TYPE INDI'){				
			}
			if ($buffer=='2 TYPE FAM'){				
				$event_family_id[$event_nr]=$event_person_id[$event_nr];
				$event_person_id[$event_nr]='';
			}
			if ($buffer=='2 RELA birth registration'){$event_kind[$event_nr]='geboorte';}
			if ($buffer=='2 RELA baptize'){  $event_kind[$event_nr]='doop'; }
			if ($buffer=='2 RELA death registration'){ $event_kind[$event_nr]='overleden';}
			if ($buffer=='2 RELA burial'){  $event_kind[$event_nr]='begraven'; }
			if ($buffer=='2 RELA civil'){  $event_kind[$event_nr]='huwelijk'; }
			if ($buffer=='2 RELA religious'){  $event_kind[$event_nr]='kerk'; }
			if ($buffer=='2 RELA registered'){  $event_kind[$event_nr]='relatie'; }	
			if ($buffer=='2 RELA licence'){  $event_kind[$event_nr]='ondertrouw'; }			
		}

		// *** Beroep ***
		if ($level1=='OCCU'){
			if (substr($buffer, 0, 6)=='1 OCCU'){
			  $beroep=substr($buffer,7);
			}  
		}
		// *** Adres ***
		if ($level1=='RESI'){
			if (substr($buffer, 0, 6)=='2 ADDR'){
			  $straat=substr($buffer,7); //$adres=$straat;
			}  
			if (substr($buffer,0,6)=='3 CONT'){
				$plaats=substr($buffer, 7); //$adres=$straat.'<br>'.$plaats;
			}
			if (substr($buffer,0,6)=='2 DATE'){
				$datum=substr($buffer, 7); $adres.=$datum.'<br>'.$straat.'<br>'.$plaats.'<br>';
			}			
		}	
		// *** Heerlijkheid ***
		if ($level1=='PROP'){
			if (substr($buffer, 0, 6)=='1 PROP'){
			  $heerlijk=substr($buffer,7); 
			} 
		}
		// *** Opleiding ***
		if ($level1=='EDUC'){
			if (substr($buffer, 0, 6)=='1 EDUC'){
			  $opleid=substr($buffer,7); 
			} 
		}	
		// *** Titel ***
		if ($level1=='TITL'){
			if (substr($buffer, 0, 6)=='1 TITL'){
			  $titel=substr($buffer,7); 
			} 
		}		
		// *** Foto ***
		if ($level1=='OBJE'){
			if ($buffer6=='2 FILE'){
				$foto=substr($buffer,7);
				if (strpos(' '.$foto,"\\")>0){
					$foto=substr(strrchr(' '.$foto, "\\"), 1 );
				}
			}
		}
		// *** Geslacht: F or M ***
		if (substr($level1,0,3)=='SEX'){ 
			if ($buffer5=='1 SEX'){  $geslacht=substr($buffer, 6); }
		}
	}  


	// *** Data opslaan ***
	$sql="INSERT INTO persoon SET
	gedcomnummer='".$gedomnr."',
	famc='".$famc."', fams='".substr($fams,0,-1)."', perszeker='', perssoort='".$soort."',
	voornaam='".$voornaam."',	roepnaam='".$roepnaam."', tussen='".$tussen."', achternaam='".$achternaam."',
	geslacht='".$geslacht."', eigen='".$eigen."',
	geboorteplaats='".$geboorte_plaats."', geboortedatum='".$geboorte_datum."', geboortetijd='".$geboorte_tijd."',
	geboortetekst='".$geboorte_tekst."', geboortebron='".$geboorte_bron."', levenloos='".$levenloos."',
	aangiftedatum='".$geboorte_reg_datum."', aangiftebron='".$geboorte_reg_bron."', 
	doopplaats='".$doop_plaats."', doopdatum='".$doop_datum."',
	dooptekst='".$doop_tekst."', doopbron='".$doop_bron."',
	ovlaangiftedatum='".$sterf_reg_datum."', ovlaangiftebron='".$sterf_reg_bron."',
	sterfplaats='".$sterf_plaats."', sterfdatum='".$sterf_datum."',	sterftijd='".$sterf_tijd."',
	sterftekst='".$sterf_tekst."', sterfbron='".$sterf_bron."', oorzaak='".$sterf_reden."',
	grafplaats='".$graf_plaats."', grafdatum='".$graf_datum."',	graftekst='".$graf_tekst."',
	grafbron='".$graf_bron."',	crematie='".$crematie."',
	tekst='".$tekst."',	levend='".$levend."', 	religie='".$religie."',
	foto='".$foto."', beroep='".$beroep."', adres='".$adres."', heerlijkheid='".$heerlijk."',
	opleiding='".$opleid."', titel='".$titel."', wijzig_datum='".$wijzig_datum."',
	wijzig_tijd='".$wijzig_tijd."'";
	$result=mysqli_query($db,$sql) or die(mysqli_error());

	
	if ($event_nr>0){
		$event_order=0;
		$check_event_kind=$event_kind['1'];
		for ($i=1; $i<=$event_nr; $i++){
			$event_order++;
			if ( $check_event_kind!=$event_kind[$i] ){
				$event_order=1;
				$check_event_kind=$event_kind[$i];
			}
			$gebeurtsql="INSERT INTO getuigen SET
				persoon='".$event_person_id[$i]."',
				gezin='".$event_family_id[$i]."',
				soort='".$event_kind[$i]."',
				getuige='".$event_event[$i]."'";
			$result=mysqli_query($db,$gebeurtsql) or die(mysqli_error());
		}
	}
} //end persoon


// ************************************************************************************************
// *** Families inlezen ***
// ************************************************************************************************
function process_gezin($family_array){
	global $level1, $db;
	$line=$family_array;
	$line2=explode("\n",$line);
	unset ($family);
	$soort=''; $zeker='';
	$kerk_datum=''; $kerk_plaats='';
	$kerk_tekst=''; $kerk_bron='';
	$rel_datum=''; $rel_plaats='';
	$rel_tekst=''; $rel_bron=''; $rel_einddatum='';
	$onder_datum=''; $onder_plaats='';
	$onder_tekst=''; $onder_bron='';
	$wet_datum=''; $wet_plaats='';
	$wet_tekst=''; $wet_bron='';
	$scheiding_datum=''; $scheiding_plaats='';
	$scheiding_tekst=''; $scheiding_bron='';
	$kinderen=''; $levend=''; $temp_kind=''; $tempnum='';
	$man=0; $vrouw=0;
	$buffer=$line2[0];
	$gedcomnr=substr($buffer,3,-5);
	$level1="";

	for ($z=1; $z<=count($line2)-2; $z++){
		$buffer=$line2[$z];
		$buffer=rtrim($buffer,"\n\r");

		// *** Save level1 ***
		if (substr($buffer, 0, 1)=='1'){ $level1=rtrim(substr($buffer,2,5)); }

		// *** Type relatie ***
		if (substr($buffer,0,6)=='1 TYPE'){  $soort=substr($buffer,7); }

		// *** Gedcomnumber man ***
		if (substr($buffer,0,8)=='1 HUSB @'){ $man=substr($buffer,8,-1); }
		
		// *** Gedcomnummer vrouw ***
		if (substr($buffer,0,8)=='1 WIFE @'){ $vrouw=substr($buffer,8,-1); }
		
		if (substr($buffer,0,9)=='2 _QUAY 1'){$zeker='Waarschijnlijk';}
		elseif (substr($buffer,0,9)=='2 _QUAY 2'){$zeker='Twijfelachtig';}
		elseif (substr($buffer,0,9)=='2 _QUAY 3'){$zeker='Onbetrouwbaar';}
		
		// *** Gedcomnummers kinderen ***
		if (substr($buffer,0,8)=='1 CHIL @'){ 			 
			$kinderen.= substr($buffer,8,-1);
			$tempnum=substr($buffer,8,-1);
			$kinderen.=';';
		}
		if (substr($buffer,0,13)=='2 _QUAYHUSB 1' OR substr($buffer,0,13)=='2 _QUAYWIFE 1'){
			$kind_zeker='Waarschijnlijk';
			$sql1="UPDATE persoon SET perszeker ='".$kind_zeker."' WHERE gedcomnummer ='".$tempnum."'";$result=mysqli_query($db,$sql1) or die(mysqli_error());
		}elseif (substr($buffer,0,13)=='2 _QUAYHUSB 2' OR substr($buffer,0,13)=='2 _QUAYWIFE 2'){
			$kind_zeker='Twijfelachtig';
			$sql1="UPDATE persoon SET perszeker='".$kind_zeker."' WHERE gedcomnummer='".$tempnum."'";$result=mysqli_query($db,$sql1) or die(mysqli_error());			
		}elseif (substr($buffer,0,13)=='2 _QUAYHUSB 3' OR substr($buffer,0,13)=='2 _QUAYWIFE 3'){
			$kind_zeker='Onbetrouwbaar';
			$sql1="UPDATE persoon SET perszeker='".$kind_zeker."' WHERE gedcomnummer='".$tempnum."'";$result=mysqli_query($db,$sql1) or die(mysqli_error());			
		}
		
		// *** Ondertrouw ***
		if ($level1=='MARL' AND $temp_kind!='religious'){
			if (substr($buffer,0,6)=='2 DATE'){  $onder_datum= substr($buffer,7); }
			if (substr($buffer,0,6)=='2 PLAC'){  $onder_plaats= substr($buffer,7); }
			if (substr($buffer,0,6)=='2 NOTE'){ $onder_tekst= substr($buffer,8,-1); }
			if (substr($buffer,0,6)=='2 SOUR'){ $onder_bron=substr($buffer,8,-1); }
		}			

		// *** Kerk of wettelijk ***
		if ($level1=='MARR'){
			if (substr($buffer,0,6)=='2 TYPE'){
				 $temp_kind=strtolower(substr($buffer,7));
				if ($soort==''){ $soort=$temp_kind; }
			}
		}

		// *** Kerkelijk huwelijk ***
		if ($level1=='MARR' AND $temp_kind=='religious'){
			if (substr($buffer,0,6)=='1 MARR'){  }
			if (substr($buffer,0,6)=='2 DATE'){  $kerk_datum= substr($buffer,7); }
			if (substr($buffer,0,6)=='2 PLAC'){  $kerk_plaats= substr($buffer,7); }
			if (substr($buffer,0,6)=='2 NOTE'){ $kerk_tekst= substr($buffer,8,-1); }
			if (substr($buffer,0,6)=='2 SOUR'){ $kerk_bron=substr($buffer,8,-1); }
		}

		// *** Wettelijk huwelijk ***
		if ($level1=='MARR' AND $temp_kind!='religious'){
			if (substr($buffer,0,6)=='1 MARR'){}
			if (substr($buffer,0,6)=='2 DATE'){ $wet_datum= substr($buffer,7); }
			if (substr($buffer,0,6)=='2 PLAC'){ $wet_plaats= substr($buffer,7); }
			if (substr($buffer,0,6)=='2 NOTE'){ $wet_tekst= substr($buffer,8,-1); }
			if (substr($buffer,0,6)=='2 SOUR'){ $wet_bron=substr($buffer,8,-1); }
		}

		// *** Partners samenleven enz ***
		if ($level1=='MARR'){
			if (substr($buffer,0,6)=='2 TYPE'){ $temp_kind=strtolower(substr($buffer,7)); }
			if ($temp_kind=='partners'){$soort='partners';}
			if ($temp_kind=='registered'){$soort='registered';}
			if ($temp_kind=='unknown'){$soort='unknown';}
			if ($temp_kind=='partners' OR $temp_kind=='registered' OR $temp_kind=='unknown'){
				$buffer='1 _LIV';
				$level1='_LIV';
				$levend='1';				
			}
		}
		
		if ($level1=='_LIV'){
			if (substr($buffer,0,6)=='1 _LIV') {}
			if (substr($buffer,0,6)=='2 DATE'){$rel_datum= substr($buffer,7); }
			if (substr($buffer,0,6)=='2 PLAC'){ $rel_plaats= substr($buffer,7); }
			if (substr($buffer,0,6)=='2 NOTE'){ $rel_tekst= substr($buffer,8,-1); }
			if (substr($buffer,0,6)=='2 SOUR'){ $rel_bron=substr($buffer,8,-1); }
		}
		
		// *** Scheiding ***
		if ($level1=='DIV'){
			if (substr($buffer,0,6)=='2 DATE'){ $scheiding_datum= substr($buffer,7); }
			if (substr($buffer,0,6)=='2 PLAC'){ $scheiding_plaats= substr($buffer,7); }
			if (substr($buffer,0,6)=='2 NOTE'){ $scheiding_tekst= substr($buffer,8,-1); }
			if (substr($buffer,0,6)=='2 SOUR'){	$scheiding_bron=substr($buffer,8,-1); }
		}
	}  
	if ($levend=='1'){
	  $rel_einddatum=$scheiding_datum;
	  $scheiding_datum='';
	  $scheiding_plaats='';
	  $scheiding_tekst='';
	  $scheiding_bron='';
	  $levend='';
	}  
	$sql="INSERT INTO gezin SET
	gezinsnr='".$gedcomnr."',
	man='".$man."',
	vrouw='".$vrouw."',
	kinderen='".substr($kinderen,0,-1)."',
	zeker='".$zeker."',
	soort='".$soort."',
	relatiedatum='".$rel_datum."',
	relatieplaats='".$rel_plaats."',
	relatietekst='".$rel_tekst."',
	relatiebron='".$rel_bron."',
	relatieeinde='".$rel_einddatum."',
	onderdatum='".$onder_datum."',
	onderplaats='".$onder_plaats."',
	ondertekst='".$onder_tekst."',
	onderbron='".$onder_bron."',
	wetdatum='".$wet_datum."',
	wetplaats='".$wet_plaats."',
	wettekst='".$wet_tekst."',
	wetbron='".$wet_bron."',
	kerkdatum='".$kerk_datum."',
	kerkplaats='".$kerk_plaats."',
	kerktekst='".$kerk_tekst."',
	kerkbron='".$kerk_bron."',
	scheidingdatum='".$scheiding_datum."',
	scheidingplaats='".$scheiding_plaats."',
	scheidingtekst='".$scheiding_tekst."',
	scheidingbron='".$scheiding_bron."'";	
	$result=mysqli_query($db,$sql) or die(mysqli_error());
}

// ************************************************************************************************
// *** Teksten inlezen ***
// ************************************************************************************************
function process_tekst($text_array){
	global $db;
	$line=$text_array;
	$line2=explode("\n",$line);
	$buffer=$line2[0];
	$tekst_tekst='';
	$second_char=strpos($buffer, '@', 3);
	$tekst_gedcomnr=substr($buffer, 2, $second_char);
	$tekst_gedcomnr=substr($tekst_gedcomnr,1,-2);
	if (strlen($buffer) > $second_char+7){
		$tekst_tekst=substr($buffer,$second_char+7);
	}

	for ($z=1; $z<=count($line2)-2; $z++){
		$buffer=$line2[$z];
		$buffer=rtrim($buffer,"\n\r");
		if (substr($buffer,2,4)=='CONC'){ $tekst_tekst=$tekst_tekst.substr($buffer,7); }
		if (substr($buffer,2,4)=='CONT'){ $tekst_tekst=$tekst_tekst."\n".substr($buffer,7); }
	} 

	$tekst_tekst = str_replace('@@', '@', $tekst_tekst);

	$sql="INSERT INTO teksten SET
		bron='".$tekst_gedcomnr."',
		tekst='".$tekst_tekst."'";
	$result=mysqli_query($db,$sql) or die(mysqli_error());
}

// ************************************************************************************************
// *** Bronnen inlezen ***
// ************************************************************************************************
function process_bron($source_array){
	global $db;
	$line=$source_array;
	$line2=explode("\n",$line);
	$buffer=$line2[0];
	unset ($source);
	$bron_tekst="";
	$bron_id=substr($buffer,3,-6);

	for ($z=1; $z<=count($line2)-2; $z++){
		$buffer=$line2[$z];
		$buffer=rtrim($buffer,"\n\r");
		if (substr($buffer,2,4)=='CONC'){ $bron_tekst.=substr($buffer,7); }
        if (substr($buffer,2,4)=='CONT'){ $bron_tekst=substr($buffer,7); }
	} 

	$sql="INSERT INTO bronnen SET
	bron='".$bron_id."',
	tekst='".$bron_tekst."'";
	$result=mysqli_query($db,$sql) or die(mysqli_error());
} // end function
} // end class
?>