# Aldfaer-gedcom-to-mysql

# Inleiding
Het origineel van dit programma stamt af van Huub Mons zie humogen.com. Aangezien het programma steeds meer uitgebreidt werd met overige stamboomprogramma's heb ik in 2010 besloten om de parser van alle overige programma's te ontdoen, zodat het alleen nog geschikt is om een Aldfaer gedcom bestand in te lezen. De parser is geschikt voor de Aldfaer versies vanaf versie 8 tot heden.

# Installatie
Plaats alle bestanden in de root van je server, zet je eigen gedcom bestand in de map "gedcom_files".
Maak in phpmyadmin of nog beter met adminer-editor zie adminer.org een database aan met de naam stamboom.
Open het db_login bestand en pas je inloggegevens aan.
Tijdens het inlezen worden vanzelf de betreffende tabellen aangemaakt.
Wil je naderhand een nieuw bestand inlezen dan worden ook vanzelf de oude tabellen verwijderd en weer leeg opnieuw aangemaakt.
