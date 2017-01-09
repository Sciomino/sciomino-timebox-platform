#
# AVAILABILITY
#
# fields:
# - smallint: 0 of 1
# - int: default
# - char 128: timestamp etc. (system defined)
# - char 256: title, name etc (user defined)
# - char 1024: url
# - char 4096: description

# Create table with groups that are valid for an authorized app
#
CREATE TABLE AuthAppGroup
(
 AuthAppGroupId INT NOT NULL AUTO_INCREMENT,
 AuthAppGroupName VARCHAR(256),
 AuthAppId INT NOT NULL,
 PRIMARY KEY (AuthAppGroupId),
 FOREIGN KEY (AuthAppId) REFERENCES AuthApp (AuthAppId) ON DELETE CASCADE
) ENGINE = INNODB;

# Activity: activity information
# - examples:
#   - 10-9-2010, new relation, herman and edwin became friends, 1, /activity/1
#
CREATE TABLE AuthAppActivity
(
 AuthAppActivityId INT NOT NULL AUTO_INCREMENT,
 AuthAppActivityTimestamp VARCHAR(128),
 AuthAppActivityTitle VARCHAR(256),
 AuthAppActivityDescription VARCHAR(4096),
 AuthAppActivityPriority SMALLINT NOT NULL DEFAULT 0,
 AuthAppActivityUrl VARCHAR(1024),
 AuthAppId INT NOT NULL,
 PRIMARY KEY (AuthAppActivityId),
 FOREIGN KEY (AuthAppId) REFERENCES AuthApp (AuthAppId) ON DELETE CASCADE
) ENGINE = INNODB;

# Usage: usage counter
# - examples:
#   - startpeople,2015, 05, 15, 59832
#
CREATE TABLE AuthAppUsage
(
 AuthAppGroupId INT NOT NULL,
 AuthAppUsageYear INT NOT NULL,
 AuthAppUsageMonth INT NOT NULL,
 AuthAppUsageDay INT NOT NULL,
 AuthAppUsageCount INT NOT NULL,
 PRIMARY KEY (AuthAppGroupId, AuthAppUsageYear, AuthAppUsageMonth, AuthAppUsageDay),
 FOREIGN KEY (AuthAppGroupId) REFERENCES AuthAppGroup (AuthAppGroupId) ON DELETE CASCADE
) ENGINE = INNODB;
