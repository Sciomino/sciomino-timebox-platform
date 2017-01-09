<?php

function getImportMap() {
	$map = array();

	# init, all required
	$map['record'] = 'pssb-update-item';
	$map['id'] = 'pers_nummer'; #personeelsnr
	$map['idName'] = 'personeelsnummer';
	$map['action'] = 'actie';
	$map['actionAdd'] = 'toevoegen';
	$map['actionEdit'] = 'wijzigen';
	$map['actionRemove'] = 'verwijderen';
	# for sync
	$map['actionDate'] = 'last_modified';
	$map['actionBool'] = 'opnemen';
	$map['actionBoolYes'] = '1';

	# session, all required, remoteAccount/displayName is optional for SSO
	$map['user'] = 'pers_nummer'; #personeelsnr
	$map['wachtwoord'] = '';
	# wachtwoordAction:
	# - '': default
	# - 'insert': lees wachtwoord als standaard wachtwoord voor iedereen
	# - 'generate': genereer een random wachtwoord
	$map['wachtwoordAction'] = 'generate';
	$map['email'] = 'email'; #email-adres
	$map['remoteAccount'] = 'ntuser'; #
	$map['displayName'] = '';
	# displayNameAction:
	# - '': default
	# - 'fullName': displayname is "firstName lastName"
	$map['displayNameAction'] = 'fullName';

	# user
	$map['firstName'] = 'voornaam'; #roepnaam
	$map['middleName'] = 'voorvoegsels'; #voorvoegsels
	$map['lastName'] = 'achternaam'; #achternaam
	$map['title'] = 'titel'; #titel
	$map['dateOfBirthDay'] = 'gebdatum'; #geboorte-datum
	$map['dateOfBirthMonth'] = 'gebdatum'; #geboorte-datum
	$map['dateOfBirthYear'] = 'gebdatum'; #geboorte-datum
	# dateOfBirthAction:
	# - '': default
	# - 'substring': yyyymmdd (example: 19700909)
	# - 'splitleft': yyyy-mm-dd (example: 1970-09-09 | 1970-9-9)
	# - 'splitright': dd-mm-yyyy (example: 09-09-1970 | 9-9-1970)
	$map['dateOfBirthAction'] = 'splitleft';
	$map['gender'] = 'geslacht'; #

	# user about
	$map['description'] = '';
	$map['foto'] = '';
	$map['fotoStream'] = '';
	# TODO: import status
	$map['status'] = '';

	# personal contact
	$map['homeEmail'] = 'email_internet'; #email-prive
	$map['homeTel'] = 'telefoon_prive'; #tel-thuis
	$map['homeMobile'] = '';

	# personal address
	$map['homeAddress'] = '';
	$map['homePostalCode'] = '';
	$map['homeCity'] = '';
	$map['homeCountry'] = 'nl';
	# homeCountryAction:
	# - '': default
	# - 'insert': lees homeCountry als standaard land voor iedereen
	$map['homeCountryAction'] = 'insert';

	# work
	$map['currentIndustry'] = '';
	$map['currentCompany'] = 'bedrijf'; #bedrijf
	$map['currentBuilding'] = 'gebouw'; #tel-gebouw
	$map['currentRoom'] = 'kamer'; #tel-kamernr
	$map['currentRole'] = 'functie'; #functie
	$map['currentDivision'] = 'divisie'; #business-unit
	$map['currentSection'] = 'afdeling'; #afdeling
	$map['currentStartDate'] = ''; #datum-in-dienst
	$map['currentEndDate'] = ''; #datum-uit-dienst
	$map['currentParttime'] = 'werkdagen'; #tel-ikwerknietop

	# work contact
	$map['workEmail'] = 'email'; #email-adres
	$map['workTelExtern'] = 'telefoon_extern'; #tel-werk
	$map['workTelIntern'] = 'telefoon_intern'; #tel-werk-intern
	$map['workMobile'] = 'telefoon_mobiel'; #mobiel-tel-nr
	$map['workLync'] = '';
	$map['workPager'] = 'telefoon_semafoon'; #tel-semafoon
	$map['workFax'] = 'fax'; #fax
	$map['workPac'] = 'post_aflever_code'; #
	$map['workMyId'] = 'pers_nummer'; #
	$map['workAssistentId'] = 'pers_nummer_assistent'; #
	$map['workManagerId'] = 'manager_persnummer';

	# work address
	$map['workAddress'] = 'standplaats'; #standplaats-medewerker
	$map['workPostalCode'] = '';
	$map['workCity'] = 'standplaats'; #standplaats-medewerker
	# workCityAction:
	# - '': default
	# - 'split': city,address (voorbeeld: amsterdam, damstraat 1)
	$map['workCityAction'] = 'split';
	$map['workCountry'] = 'nl';
	# workCountryAction:
	# - '': default
	# - 'insert': lees workCountry als standaard land voor iedereen
	$map['workCountryAction'] = 'insert';

	# --- and more ---

	# knowledge, knowledgeField is required
	$map['knowledge'] = '';
	$map['knowledgeField'] = '';
	$map['knowledgeLevel'] = '';

	# hobby, hobbyField is required
	$map['hobby'] = '';
	$map['hobbyField'] = '';

	# tag, tagName is required
	$map['tag'] = '';
	$map['tagName'] = '';

	# TODO: import publications

	# experiences, subject & title required
	$map['company'] = '';
	$map['companySubject'] = '';
	$map['companyTitle'] = '';
	$map['companyLike'] = '';
	$map['education'] = '';
	$map['educationSubject'] = '';
	$map['educationTitle'] = '';
	$map['educationPublisher'] = '';
	$map['educationRelation'] = '';
	$map['educationLike'] = '';
	$map['event'] = '';
	$map['eventSubject'] = '';
	$map['eventTitle'] = '';
	$map['eventPublisher'] = '';
	$map['eventRelation'] = '';
	$map['eventLike'] = '';
	$map['product'] = '';
	$map['productSubject'] = '';
	$map['productTitle'] = '';
	$map['productAlternative'] = '';
	$map['productLike'] = '';

	return $map;
}

?>
