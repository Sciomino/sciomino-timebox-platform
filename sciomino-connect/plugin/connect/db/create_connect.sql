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
