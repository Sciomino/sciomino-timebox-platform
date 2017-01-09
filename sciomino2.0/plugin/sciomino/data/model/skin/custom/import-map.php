<?php

function getImportMap() {
	$map = array();

	# init, all required
	$map['record'] = 'user';
	$map['id'] = 'id';
	$map['idName'] = 'Personeelsnummer';
	$map['action'] = 'action';
	$map['actionAdd'] = 'add';
	$map['actionEdit'] = 'edit';
	$map['actionRemove'] = 'remove';
	# for sync
	$map['actionDate'] = 'last_modified';
	$map['actionBool'] = 'opnemen';
	$map['actionBoolYes'] = '1';

	# session, all required, remoteAccount/displayName is optional for SSO
	$map['user'] = 'emailadres';
	$map['wachtwoord'] = '';
	# wachtwoordAction:
	# - '': default
	# - 'insert': lees wachtwoord als standaard wachtwoord voor iedereen
	# - 'generate': genereer een random wachtwoord
	$map['wachtwoordAction'] = 'generate';
	$map['email'] = 'emailadres';
	$map['remoteAccount'] = 'emailadres';
	$map['displayName'] = '';
	# displayNameAction:
	# - '': default
	# - 'fullName': displayname is "firstName lastName"
	$map['displayNameAction'] = 'fullName';

	# user
	$map['firstName'] = 'voornaam';
	$map['middleName'] = '';
	$map['lastName'] = 'achternaam';
	$map['title'] = 'titel';
	$map['dateOfBirthDay'] = 'geboortedatum';
	$map['dateOfBirthMonth'] = 'geboortedatum';
	$map['dateOfBirthYear'] = 'geboortedatum';
	# dateOfBirthAction:
	# - '': default
	# - 'substring': yyyymmdd (example: 19700909)
	# - 'splitleft': yyyy-mm-dd (example: 1970-09-09 | 1970-9-9)
	# - 'splitright': dd-mm-yyyy (example: 09-09-1970 | 9-9-1970)
	$map['dateOfBirthAction'] = 'splitleft';
	$map['gender'] = 'geslacht';

	# user about
	$map['description'] = '';
	$map['foto'] = 'foto';
	$map['fotoStream'] = 'fotoStream';
	# TODO: import status
	$map['status'] = '';

	# personal contact
	$map['homeEmail'] = '';
	$map['homeTel'] = '';
	$map['homeMobile'] = '';

	# personal address
	$map['homeAddress'] = '';
	$map['homePostalCode'] = '';
	$map['homeCity'] = '';
	$map['homeCountry'] = '';
	# homeCountryAction:
	# - '': default
	# - 'insert': lees homeCountry als standaard land voor iedereen
	$map['homeCountryAction'] = '';

	# work
	$map['currentIndustry'] = '';
	$map['currentCompany'] = 'bedrijf';
	$map['currentBuilding'] = 'gebouw';
	$map['currentRoom'] = '';
	$map['currentRole'] = 'functie';
	$map['currentDivision'] = 'divisie';
	$map['currentSection'] = 'afdeling';
	$map['currentStartDate'] = '';
	$map['currentEndDate'] = '';
	$map['currentParttime'] = '';

	# work contact
	$map['workEmail'] = 'emailadres';
	$map['workTelExtern'] = 'telefoon';
	$map['workTelIntern'] = '';
	$map['workMobile'] = '';
	$map['workLync'] = '';
	$map['workPager'] = '';
	$map['workFax'] = '';
	$map['workPac'] = '';
	$map['workMyId'] = '';
	$map['workAssistentId'] = '';
	$map['workManagerId'] = '';

	# work address
	$map['workAddress'] = 'adres';
	$map['workPostalCode'] = 'postcode';
	$map['workCity'] = 'plaats';
	# workCityAction:
	# - '': default
	# - 'split': city,address (voorbeeld: amsterdam, damstraat 1)
	$map['workCityAction'] = '';
	$map['workCountry'] = 'land';
	# workCountryAction:
	# - '': default
	# - 'insert': lees workCountry als standaard land voor iedereen
	$map['workCountryAction'] = '';

	# --- and more ---

	# knowledge, knowledgeField is required
	$map['knowledge'] = 'knowledge';
	$map['knowledgeField'] = 'field';
	$map['knowledgeLevel'] = 'level';

	# hobby, hobbyField is required
	$map['hobby'] = 'hobby';
	$map['hobbyField'] = 'field';

	# tag, tagName is required
	$map['tag'] = 'tag';
	$map['tagName'] = 'name';

	# TODO: import publications

	# experiences, subject & title required
	$map['company'] = 'company';
	$map['companySubject'] = 'subject';
	$map['companyTitle'] = 'title';
	$map['companyLike'] = 'like';
	$map['education'] = 'education';
	$map['educationSubject'] = 'subject';
	$map['educationTitle'] = 'title';
	$map['educationPublisher'] = '';
	$map['educationRelation'] = '';
	$map['educationLike'] = 'like';
	$map['event'] = 'event';
	$map['eventSubject'] = 'subject';
	$map['eventTitle'] = 'title';
	$map['eventPublisher'] = '';
	$map['eventRelation'] = '';
	$map['eventLike'] = 'like';
	$map['product'] = 'product';
	$map['productSubject'] = 'subject';
	$map['productTitle'] = 'title';
	$map['productAlternative'] = 'alternative';
	$map['productLike'] = 'like';

	return $map;
}

?>
