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

# - every field in the database MUST be protected with an access rule
# - access rules consist of attribute=value pairs, for example: name=work OR level=1
#
CREATE TABLE AccessRule
(
 AccessRuleId INT NOT NULL AUTO_INCREMENT,
 AccessRuleAttribute VARCHAR(128),
 AccessRuleValue VARCHAR(128),
 PRIMARY KEY (AccessRuleId)
) ENGINE = INNODB;



##########
# ACT
# - act is the main table
# - this table only defines the necessary fields for an action
# - every other act characteristic is stored in an annotation table in a single record
#
CREATE TABLE Act
(
 ActId INT NOT NULL AUTO_INCREMENT,
 ActDescription VARCHAR(4096),
 ActTimestamp VARCHAR(128),
 ActExpiration VARCHAR(128),
 ActActive INT NOT NULL,
 ActParent INT NOT NULL,
 Reference VARCHAR(128) NOT NULL,
 PRIMARY KEY (ActId)
) ENGINE = INNODB;

# Single attribute = value characteristics are stored in ActAnnotation. Examples:
# - foto = /upload/abc.jpg
#
CREATE TABLE ActAnnotation
(
 AnnotationId INT NOT NULL AUTO_INCREMENT,
 AnnotationAttribute VARCHAR(256),
 AnnotationValue VARCHAR(4096),
 AnnotationType VARCHAR(128),
 ActId INT NOT NULL,
 PRIMARY KEY (AnnotationId),
 FOREIGN KEY (ActId) REFERENCES Act (ActId) ON DELETE CASCADE
) ENGINE = INNODB;

# Plural attribute = value characteristics are stored in ActProfile & ActProfileAnnotation. Examples:
# - language profile 		(profileGroup=language)
#   - speaks = dutch 		(profileName=mothertongue)
#   - speaks = english 		(profileName=second language)
# - job interests profile
#   - interest = programming & skill = 4
#   - interest = architecture & skill = 5
#
CREATE TABLE ActProfile
(
 ProfileId INT NOT NULL AUTO_INCREMENT,
 ProfileGroup VARCHAR(256),
 ProfileName VARCHAR(256),
 ActId INT NOT NULL,
 PRIMARY KEY (ProfileId),
 FOREIGN KEY (ActId) REFERENCES Act (ActId) ON DELETE CASCADE
) ENGINE = INNODB;

CREATE TABLE ActProfileAnnotation
(
 AnnotationId INT NOT NULL AUTO_INCREMENT,
 AnnotationAttribute VARCHAR(256),
 AnnotationValue VARCHAR(4096),
 AnnotationType VARCHAR(128),
 ProfileId INT NOT NULL,
 PRIMARY KEY (AnnotationId),
 FOREIGN KEY (ProfileId) REFERENCES ActProfile (ProfileId) ON DELETE CASCADE
) ENGINE = INNODB;


##########
# Review
# - A review is a user review of an ACT
# - different reviews are possible, its up to the application
#   - score=1 (like)
#   - score=[0|1] (thumbs down, thumbs up)
#   - score=[1..5] (rating 1 to 5)
#
CREATE TABLE ActReview
(
 ActReviewId INT NOT NULL AUTO_INCREMENT,
 ActReviewScore INT NOT NULL,
 Reference VARCHAR(128) NOT NULL,
 ActId INT NOT NULL,
 PRIMARY KEY (ActReviewId),
 FOREIGN KEY (ActId) REFERENCES Act (ActId) ON DELETE CASCADE
) ENGINE = INNODB;


##########
# MailBlock
# - A mail is send to all people involved in the ACT
# - unless someone indicates otherwise
# 	- foreach entry in this table the mail is blocked
#
CREATE TABLE ActMailblock
(
 ActMailblockId INT NOT NULL AUTO_INCREMENT,
 Reference VARCHAR(128) NOT NULL,
 ActId INT NOT NULL,
 PRIMARY KEY (ActMailblockId),
 FOREIGN KEY (ActId) REFERENCES Act (ActId) ON DELETE CASCADE
) ENGINE = INNODB;


##########
# STATISTICS
# - statistics are stored seperately to get the scores quickly, two types:
#   - statistics per Act, like the react count per act
#   - statistics overall, like the number of acts in the app
#
CREATE TABLE ActStats
(
 ActStatsId INT NOT NULL AUTO_INCREMENT,
 ActStatsUpCount INT NOT NULL,
 ActStatsDownCount INT NOT NULL,
 ActStatsChildrenCount INT NOT NULL,
 ActId INT NOT NULL,
 PRIMARY KEY (ActStatsId),
 FOREIGN KEY (ActId) REFERENCES Act (ActId) ON DELETE CASCADE
) ENGINE = INNODB;

CREATE TABLE Stats
(
 StatsId INT NOT NULL AUTO_INCREMENT,
 ActCount INT NOT NULL,
 ActOpenCount INT NOT NULL,
 ActClosedCount INT NOT NULL,
 ReactCount INT NOT NULL,
 PRIMARY KEY (StatsId)
) ENGINE = INNODB;


