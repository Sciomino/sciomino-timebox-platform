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
# GRAPH
#
# fields:
# - smallint: 0 of 1
# - int: default
# - char 128: timestamp etc. (system defined)
# - char 256: title, name etc (user defined)
# - char 1024: url
# - char 4096: description



##########
# RELATIONS between Session and App
# - Sessions have access to Apps of a certain type
# - example: values(1, statsID-1, userID-2, appID-3) (user 2 heeft toegang tot de stats (id-1) van app 3)
# - stats wordt beschreven door AppType en heeft een naam en een begin datum.

CREATE TABLE AppType
(
 AppTypeId INT NOT NULL AUTO_INCREMENT,
 AppTypeName VARCHAR(128),
 AppTypeStartYear SMALLINT NOT NULL,
 AppTypeStartMonth SMALLINT NOT NULL,
 PRIMARY KEY (AppTypeId)
) ENGINE = INNODB;

CREATE TABLE SessionInApp
(
 SessionInAppId INT NOT NULL AUTO_INCREMENT,
 AppTypeId INT NOT NULL,
 SessionId INT NOT NULL,
 AuthAppId INT NOT NULL,
 PRIMARY KEY (SessionInAppId),
 INDEX AppIndex (SessionId, AuthAppId)
) ENGINE = INNODB;

##########
# storage of temporary api data

CREATE TABLE AppData
(
 AppDataTimestamp VARCHAR(128),
 AppDataText LONGTEXT,
 AppTypeId INT NOT NULL,
 AuthAppId INT NOT NULL,
 PRIMARY KEY (AppTypeId, AuthAppId)
) ENGINE = INNODB;
