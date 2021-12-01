<?php
$tbldb = mysqli_query($db,"DROP TABLE persoon");
$tbldb = mysqli_query($db,"CREATE TABLE persoon (
	id mediumint(6) unsigned NOT NULL auto_increment,
	gedcomnummer varchar(20) CHARACTER SET utf8,
	famc varchar(50) CHARACTER SET utf8,
	fams varchar(150) CHARACTER SET utf8,
	perszeker text CHARACTER SET utf8,
	perssoort text CHARACTER SET utf8,
	voornaam varchar(50) CHARACTER SET utf8,
	roepnaam varchar(50) CHARACTER SET utf8,
	tussen varchar(20) CHARACTER SET utf8,
	achternaam varchar(50) CHARACTER SET utf8,
	geslacht varchar(1) CHARACTER SET utf8,
	eigen varchar(100) CHARACTER SET utf8,
	geboorteplaats varchar(75) CHARACTER SET utf8,
	geboortedatum varchar(35) CHARACTER SET utf8,
	geboortetijd varchar(25) CHARACTER SET utf8,
	geboortetekst text CHARACTER SET utf8,
	geboortebron text CHARACTER SET utf8,
	aangiftedatum varchar(35) CHARACTER SET utf8,
	aangiftebron text CHARACTER SET utf8,
	ovlaangiftedatum varchar(35) CHARACTER SET utf8,
	ovlaangiftebron text CHARACTER SET utf8,
	levenloos varchar(1) CHARACTER SET utf8 DEFAULT 'n',
	doopplaats varchar(75) CHARACTER SET utf8,
	doopdatum varchar(35) CHARACTER SET utf8,
	dooptekst text CHARACTER SET utf8,
	doopbron text CHARACTER SET utf8,
	sterfplaats varchar(75) CHARACTER SET utf8,
	sterfdatum varchar(35) CHARACTER SET utf8,
	sterftijd varchar(25) CHARACTER SET utf8,
	sterftekst text CHARACTER SET utf8,
	sterfbron text CHARACTER SET utf8,
	oorzaak varchar(50) CHARACTER SET utf8,
	grafplaats varchar(75) CHARACTER SET utf8,
	grafdatum varchar(35) CHARACTER SET utf8,
	graftekst text CHARACTER SET utf8,
	grafbron text CHARACTER SET utf8,
	crematie varchar(1) CHARACTER SET utf8,
	tekst text CHARACTER SET utf8,
	levend varchar(20) CHARACTER SET utf8,
	religie varchar(50) CHARACTER SET utf8,
	beroep text CHARACTER SET utf8,
	adres text CHARACTER SET utf8,
	foto text CHARACTER SET utf8,
	heerlijkheid text CHARACTER SET utf8,
	opleiding text CHARACTER SET utf8,
	titel text CHARACTER SET utf8,
	wijzig_datum varchar(35) CHARACTER SET utf8,
	wijzig_tijd varchar(25) CHARACTER SET utf8,	
	PRIMARY KEY (`id`),
	KEY (achternaam),
	KEY (gedcomnummer),
	KEY (tussen)
	) ENGINE=MyIsam DEFAULT CHARSET=utf8") or die(mysqli_error());

$tbldb = mysqli_query($db,"DROP TABLE getuigen");
$tbldb = mysqli_query($db,"CREATE TABLE getuigen (
	id mediumint(6) unsigned NOT NULL auto_increment,
	persoon varchar(20) CHARACTER SET utf8,
	gezin varchar(20) CHARACTER SET utf8,
	soort varchar(20) CHARACTER SET utf8,
	getuige text CHARACTER SET utf8,
	PRIMARY KEY (`id`),
	KEY (persoon),
	KEY (gezin),
	KEY (soort)
	) ENGINE=MyIsam DEFAULT CHARSET=utf8") or die(mysqli_error());

$tbldb = mysqli_query($db,"DROP TABLE gezin");
$tbldb = mysqli_query($db,"CREATE TABLE gezin (
	id mediumint(6) unsigned NOT NULL auto_increment,
	gezinsnr varchar(20) CHARACTER SET utf8,
	man varchar(20) CHARACTER SET utf8,
	vrouw varchar(20) CHARACTER SET utf8,
	kinderen text CHARACTER SET utf8,
	zeker text CHARACTER SET utf8,
	soort varchar(50) CHARACTER SET utf8,
	wetdatum varchar(35) CHARACTER SET utf8,
	wetplaats varchar(75) CHARACTER SET utf8,
	wettekst text CHARACTER SET utf8,
	wetbron text CHARACTER SET utf8,
	kerkdatum varchar(35) CHARACTER SET utf8,
	kerkplaats varchar(75) CHARACTER SET utf8,
	kerktekst text CHARACTER SET utf8,
	kerkbron text CHARACTER SET utf8,
	scheidingdatum varchar(35) CHARACTER SET utf8,
	scheidingplaats varchar(75) CHARACTER SET utf8,
	scheidingtekst text CHARACTER SET utf8,
	scheidingbron text CHARACTER SET utf8,
	relatiedatum varchar(35) CHARACTER SET utf8,
	relatieplaats varchar(75) CHARACTER SET utf8,
	relatietekst text CHARACTER SET utf8,
	relatiebron text CHARACTER SET utf8,
	relatieeinde varchar(35) CHARACTER SET utf8,
	onderdatum varchar(35) CHARACTER SET utf8,
	onderplaats varchar(75) CHARACTER SET utf8,
	ondertekst text CHARACTER SET utf8,
	onderbron text CHARACTER SET utf8,	
	PRIMARY KEY (`id`),
	KEY (gezinsnr)
	) ENGINE=MyIsam DEFAULT CHARSET=utf8") or die(mysqli_error());

$tbldb = mysqli_query($db,"DROP TABLE teksten");
$tbldb = mysqli_query($db,"CREATE TABLE teksten (
	id mediumint(6) unsigned NOT NULL auto_increment,
	bron varchar(20) CHARACTER SET utf8,
	tekst text CHARACTER SET utf8,
	KEY (id)
	) ENGINE=MyIsam DEFAULT CHARSET=utf8") or die(mysqli_error());

$tbldb = mysqli_query($db,"DROP TABLE bronnen"); 
$tbldb = mysqli_query($db,"CREATE TABLE bronnen (
	id mediumint(6) unsigned NOT NULL auto_increment,
	bron varchar(20) CHARACTER SET utf8,
	tekst text CHARACTER SET utf8,
	KEY (`id`)	) ENGINE=MyIsam DEFAULT CHARSET=utf8") or die(mysqli_error());
?>
