# Aldfaer-gedcom-to-mysql

# Inleiding
Het origineel van dit programma stamt af van Huub Mons zie [Humogen](https://www.humogen.com).  
Aangezien het programma steeds meer uitgebreid werd met overige stamboomprogramma's heb ik in 2010 besloten om de parser van alle overige programma's te ontdoen, zodat het alleen nog geschikt is om een Aldfaer gedcom bestand in te lezen.  
De parser is geschikt voor de Aldfaer versies vanaf versie 8 tot heden.

# Installatie
1. Plaats alle bestanden in de root van je server, zet je eigen gedcom bestand in de map "gedcom_files".
2. Maak in phpmyadmin of nog beter met [adminer editor](https://www.adminer.org) een database aan met de naam stamboom.
3. Open het db_login bestand en pas je inloggegevens aan.
4. Tijdens het inlezen worden vanzelf de betreffende tabellen aangemaakt.
5. Wil je naderhand een nieuw bestand inlezen dan worden ook vanzelf de oude tabellen verwijderd en weer leeg opnieuw aangemaakt.
