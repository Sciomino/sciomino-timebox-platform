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
