#
# Xcow base uses sessions
#
# fields:
# - smallint: 0 of 1
# - int: default
# - char 128: timestamp etc. (system defined)
# - char 256: title, name etc (user defined)
# - char 1024: url
# - char 4096: description

CREATE TABLE SessionAnonymous
(
 SessionAnonymousId INT NOT NULL AUTO_INCREMENT,
 SessionAnonymousKey VARCHAR(128),
 SessionAnonymousCreated VARCHAR(128),
 SessionAnonymousTimestamp VARCHAR(128),
 SessionAnonymousIpAddress VARCHAR(128),
 SessionAnonymousUserAgent VARCHAR(128),
 PRIMARY KEY (SessionAnonymousId),
 INDEX KeyIndex (SessionAnonymousKey)
);

CREATE TABLE Session
(
 SessionId INT NOT NULL AUTO_INCREMENT,
 SessionUser VARCHAR(256),
 SessionPass VARCHAR(256),
 SessionEmail VARCHAR(256),
 SessionRemoteUser VARCHAR(256),
 SessionDisplay VARCHAR(256),
 SessionKey VARCHAR(128),
 SessionAccessLevel SMALLINT,
 SessionActive SMALLINT,
 SessionCreated VARCHAR(128),
 SessionTimestamp VARCHAR(128),
 SessionIpAddress VARCHAR(128),
 SessionUserAgent VARCHAR(128),
 PRIMARY KEY (SessionId),
 INDEX UserIndex (SessionUser),
 INDEX KeyIndex (SessionKey)
) ENGINE = INNODB;

#
# The session connector lists oauth client connections
#

CREATE TABLE SessionConnector
(
 SessionConnectorId INT NOT NULL AUTO_INCREMENT,
 SessionConnectorApp VARCHAR(256),
 SessionConnectorType VARCHAR(128),
 SessionConnectorToken VARCHAR(256),
 SessionConnectorSecret VARCHAR(256),
 SessionConnectorTimestamp VARCHAR(128),
 SessionConnectorReference VARCHAR(256),
 SessionId INT NOT NULL,
 PRIMARY KEY (SessionConnectorId),
 FOREIGN KEY (SessionId) REFERENCES Session (SessionId) ON DELETE CASCADE
) ENGINE = INNODB;

#
# Oauth
#

CREATE TABLE OauthClient
(
 OauthClientId INT NOT NULL AUTO_INCREMENT,
 OauthClientKey VARCHAR(256),
 OauthClientSecret VARCHAR(256),
 OauthClientName VARCHAR(256),
 OauthClientDescription VARCHAR(4096),
 PRIMARY KEY (OauthClientId)
) ENGINE = INNODB;

#
# OauthAccessStatus = 1, init state, 2, authorize state, 3, token state
#

CREATE TABLE OauthAccess
(
 OauthAccessId INT NOT NULL AUTO_INCREMENT,
 OauthAccessTempToken VARCHAR(256),
 OauthAccessTempSecret VARCHAR(256),
 OauthAccessTempTimestamp VARCHAR(128),
 OauthAccessCallback VARCHAR(1024),
 OauthAccessVerifier VARCHAR(256),
 OauthAccessAuthorized SMALLINT,
 OauthAccessToken VARCHAR(256),
 OauthAccessSecret VARCHAR(256),
 OauthAccessTimestamp VARCHAR(128),
 OauthAccessStatus SMALLINT,
 UserId VARCHAR(256),
 OauthClientId INT NOT NULL,
 PRIMARY KEY (OauthAccessId),
 FOREIGN KEY (OauthClientId) REFERENCES OauthClient (OauthClientId) ON DELETE CASCADE
) ENGINE = INNODB;

CREATE TABLE OauthUse
(
 OauthUseId INT NOT NULL AUTO_INCREMENT,
 OauthUseTimestamp VARCHAR(128),
 OauthUseNonce VARCHAR(256),
 OauthAccessId INT NOT NULL,
 PRIMARY KEY (OauthUseId),
 FOREIGN KEY (OauthAccessId) REFERENCES OauthAccess (OauthAccessId) ON DELETE CASCADE
) ENGINE = INNODB;

#
# app auth
#

CREATE TABLE AuthApp
(
 AuthAppId INT NOT NULL AUTO_INCREMENT,
 AuthAppName VARCHAR(128),
 AuthAppSecret VARCHAR(128),
 AuthAppSuffix VARCHAR(128),
 PRIMARY KEY (AuthAppId)
) ENGINE = INNODB;

#
# mobile
# - do not use a foreign key for SessionId, because the mobile access table requires two steps, first pin , then token
# - on the first step the session is not yet created.

CREATE TABLE MobileAccess
(
 MobileAccessId INT NOT NULL AUTO_INCREMENT,
 MobileAccessApp VARCHAR(128),
 MobileAccessEmail VARCHAR(256),
 MobileAccessSecret VARCHAR(256),
 MobileAccessToken VARCHAR(128),
 MobileAccessActive SMALLINT,
 MobileAccessCreated VARCHAR(128),
 MobileAccessTimestamp VARCHAR(128),
 MobileAccessIpAddress VARCHAR(128),
 MobileAccessUserAgent VARCHAR(128),
 SessionId INT NOT NULL,
 PRIMARY KEY (MobileAccessId),
 INDEX EmailIndex (MobileAccessEmail),
 INDEX TokenIndex (MobileAccessToken)
) ENGINE = INNODB;

#
# CONNECT
#
# fields:
# - smallint: 0 of 1
# - int: default
# - char 128: timestamp etc. (system defined)
# - char 256: title, name etc (user defined)
# - char 1024: url
# - char 4096: description



##########
# CONNECT
# - connect is the main table
# - this table only defines the necessary fields for a connection
# - every other connect characteristic is stored in an annotation table in a single record
#
CREATE TABLE Connection
(
 ConnectionId INT NOT NULL AUTO_INCREMENT,
 ConnectionType VARCHAR(256),
 ConnectionName VARCHAR(256),
 ConnectionTimestamp VARCHAR(128),
 Reference VARCHAR(128) NOT NULL,
 PRIMARY KEY (ConnectionId)
) ENGINE = INNODB;

# Single attribute = value characteristics are stored in ConnectionAnnotation. Examples:
# - url = http://wikipedia.org/Programmeren
#
CREATE TABLE ConnectionAnnotation
(
 AnnotationId INT NOT NULL AUTO_INCREMENT,
 AnnotationAttribute VARCHAR(256),
 AnnotationValue VARCHAR(4096),
 AnnotationType VARCHAR(128),
 ConnectionId INT NOT NULL,
 PRIMARY KEY (AnnotationId),
 FOREIGN KEY (ConnectionId) REFERENCES Connection (ConnectionId) ON DELETE CASCADE
) ENGINE = INNODB;

# Plural attribute = value characteristics are stored in ConnectionProfile & ConnectionProfileAnnotation. Examples:
# - language profile 		(profileGroup=language)
#   - speaks = dutch 		(profileName=mothertongue)
#   - speaks = english 		(profileName=second language)
# - job interests profile
#   - interest = programming & skill = 4
#   - interest = architecture & skill = 5
#
CREATE TABLE ConnectionProfile
(
 ProfileId INT NOT NULL AUTO_INCREMENT,
 ProfileGroup VARCHAR(256),
 ProfileName VARCHAR(256),
 ConnectionId INT NOT NULL,
 PRIMARY KEY (ProfileId),
 FOREIGN KEY (ConnectionId) REFERENCES Connection (ConnectionId) ON DELETE CASCADE
) ENGINE = INNODB;

CREATE TABLE ConnectionProfileAnnotation
(
 AnnotationId INT NOT NULL AUTO_INCREMENT,
 AnnotationAttribute VARCHAR(256),
 AnnotationValue VARCHAR(4096),
 AnnotationType VARCHAR(128),
 ProfileId INT NOT NULL,
 PRIMARY KEY (AnnotationId),
 FOREIGN KEY (ProfileId) REFERENCES ConnectionProfile (ProfileId) ON DELETE CASCADE
) ENGINE = INNODB;



##########
# CACHE
# - cache of remote content
#
CREATE TABLE ConnectionCache
(
 ConnectionCacheId INT NOT NULL AUTO_INCREMENT,
 ConnectionCacheUrl VARCHAR(1024),
 ConnectionCacheContent MEDIUMTEXT,
 ConnectionCacheTimestamp VARCHAR(128),
 PRIMARY KEY (ConnectionCacheId)
) ENGINE = INNODB;



##########
# ACCESS
# - only access from trusted sources
#
CREATE TABLE AccessApp
(
 AccessAppId INT NOT NULL AUTO_INCREMENT,
 AccessAppName VARCHAR(256),
 AccessAppKey VARCHAR(256),
 PRIMARY KEY (AccessAppId)
) ENGINE = INNODB;
#
# GEO
#
# fields:
# - smallint: 0 of 1
# - int: default
# - char 128: timestamp etc. (system defined)
# - char 256: title, name etc (user defined)
# - char 1024: url
# - char 4096: description



##########
# GEOcities
# - this table is used to lookup lat & lon from a city name
#
CREATE TABLE GEOcities
(
 GEOcitiesId INT NOT NULL AUTO_INCREMENT,
 GEOcitiesCC VARCHAR(2),
 GEOcitiesCA VARCHAR(200),
 GEOcitiesName VARCHAR(200),
 GEOcitiesLat VARCHAR(12),
 GEOcitiesLon VARCHAR(12),
 GEOcitiesPrimary SMALLINT,
 PRIMARY KEY (GEOcitiesId),
 KEY (GEOcitiesCC, GEOcitiesCA, GEOcitiesName),
 KEY (GEOcitiesCC, GEOcitiesName),
 KEY (GEOcitiesName)
) ENGINE = INNODB;
