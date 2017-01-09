Dit is de readme file behorende bij release 1.2o

In deze docs directory vind je
- changes.txt: met de grootste veranderingen van de afgelopen versies op een rij.
- installatie-document.txt: een stap voor stap handleiding voor installatie van sciomino.
- Releasenotes: een overzicht van de features
- example files: een directory met configuratie files
- import-notes.txt: een overzicht van de data beheer tools


BELANGRIJK VOOR DEZE RELEASE
----------------------------

- sciomino.ini config is uitgebreidt
- xcow_base.ini config is uitgebreidt
- nieuwe beheer tool: syncCheckup om entries in de mysql table te checken tegen de Sciomino API


sciomino.ini
============

[1]
$XCOW_B['sciomino']['version'] = "1-2-o-20131209";

-> Dit versie nummer wordt gebruikt als versie nummer in de aanroep naar .css en .js files, zodat deze niet gecached blijven op de browser na de update.

[2]
$XCOW_B['sciomino']['skin-network'] = "no"; # toon network funtionality, yes|no
$XCOW_B['sciomino']['skin-insights'] = "yes"; # toon insights tab, yes|no
$XCOW_B['sciomino']['skin-privacy'] = "yes"; # toon prive personalia velden, yes|no
$XCOW_B['sciomino']['skin-notify'] = 0; # notification default, 0|1 => 0:off and 1:on

-> dit zijn configuratie opties voor nieuwe functionaliteit. Met bovenstaande instellingen is de functionaliteit gelijk aan versie 1.2n

[3]
# - work: industry, company, building, room, role, division, section, parttime
# - work contact: email, telIntern, telExtern, mobile, lync, pager, fax, pac, myId, assistentId, managerId

-> Er zijn twee nieuwe velden: industry & lync. Industry is de branche waar een bedrijf in werkt voor als het bedrijf in meerdere branches werkzaam is. Lync is een verwijzing naar een lync-nummer, zodat direct vanuit Sciomino met sip:// een lync oproep gedaan kan worden.

-> Om deze uit te zetten (zoals in versie 1.2n) moeten ze toegevoegd worden aan de exclude_list, bijvoorbeeld:
SC_SKIN_PERSONALIA_EXCLUDE_LIST = "industry", "company", "lync", "pager", "myId"

[4]
# size in pixels, defines both width & height of the original photo, for example: 256
# if size=0 => keep original size
$XCOW_B['sciomino']['original-photo-size'] = 0;

-> Dit is een configuratie optie om de grootte van de geuploade foto te beheren 


xcow_base.ini
=============

[1]
$XCOW_B['session_keep'] 		= 0; #session is valid for 30 days
$XCOW_B['session_cookie_domain']	= "";

-> Dit is een configuratie optie om langer ingelogd te blijven. Als er geen SSO is, dan moet iemand elke kaar bij Sciomino inloggen. Met session_keep=1 en session_cookie_domain=.YOURDOMAIN.COM kun je nu de sessie voor 30 dagen vasthouden in jouw domein.


/import/syncCheckup
===================

url: import.syncCheckup?id=[ID]

met deze url kan van een ID de synchronisatie gechecked worden. Met de optie debug=1 kan helemaal gezien worden hoe de informatie vanuit de mysql table is gematched op de Sciomino API.

zie voor meer uitleg de import-notes.txt file.

