#
# STATS
#
# fields:
# - smallint: 0 of 1
# - int: default
# - char 128: timestamp etc. (system defined)
# - char 256: title, name etc (user defined)
# - char 1024: url
# - char 4096: description



##########
# STATS
# - stats is the main table
# - this table only defines the necessary fields for a statistic: the date
# - every other stats characteristic is stored in an annotation table in (1) a single record or (2) a plural attribute
#
CREATE TABLE Stats
(
 StatsId INT NOT NULL AUTO_INCREMENT,
 StatsTimestamp VARCHAR(128),
 PRIMARY KEY (StatsId),
 UNIQUE INDEX TimeIndex (StatsTimestamp)
) ENGINE = INNODB;

# Single attribute = value characteristics are stored in StatsAnnotation. Examples:
# - UserCount = 1234
#
CREATE TABLE StatsAnnotation
(
 AnnotationId INT NOT NULL AUTO_INCREMENT,
 AnnotationAttribute VARCHAR(256),
 AnnotationValue VARCHAR(4096),
 AnnotationType VARCHAR(128),
 StatsId INT NOT NULL,
 PRIMARY KEY (AnnotationId),
 FOREIGN KEY (StatsId) REFERENCES Stats (StatsId) ON DELETE CASCADE
) ENGINE = INNODB;

# Plural attribute = value characteristics are stored in UserProfile & UserProfileAnnotation. Examples:
# - language profile 		(profileGroup=language)
#   - speaks = dutch 		(profileName=mothertongue)
#   - speaks = english 		(profileName=second language)
# - job interests profile
#   - interest = programming & skill = 4
#   - interest = architecture & skill = 5
#
CREATE TABLE StatsProfile
(
 ProfileId INT NOT NULL AUTO_INCREMENT,
 ProfileGroup VARCHAR(256),
 ProfileName VARCHAR(256),
 StatsId INT NOT NULL,
 PRIMARY KEY (ProfileId),
 FOREIGN KEY (StatsId) REFERENCES Stats (StatsId) ON DELETE CASCADE
) ENGINE = INNODB;

CREATE TABLE StatsProfileAnnotation
(
 AnnotationId INT NOT NULL AUTO_INCREMENT,
 AnnotationAttribute VARCHAR(256),
 AnnotationValue VARCHAR(4096),
 AnnotationType VARCHAR(128),
 ProfileId INT NOT NULL,
 PRIMARY KEY (AnnotationId),
 FOREIGN KEY (ProfileId) REFERENCES StatsProfile (ProfileId) ON DELETE CASCADE
) ENGINE = INNODB;



##########
# UPTIME
# in StatsUptime values for uptime statistics are stored, these are only timestamps of valid uptime checks
#
CREATE TABLE StatsUptime
(
 StatsUptimeId INT NOT NULL AUTO_INCREMENT,
 StatsUptimeTimestamp VARCHAR(128),
 PRIMARY KEY (StatsUptimeId)
) ENGINE = INNODB;

