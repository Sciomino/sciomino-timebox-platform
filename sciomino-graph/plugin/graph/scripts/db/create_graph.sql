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
