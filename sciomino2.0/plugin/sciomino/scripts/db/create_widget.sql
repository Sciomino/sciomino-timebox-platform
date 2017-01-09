#
# WIDGET
#
# fields:
# - smallint: 0 of 1
# - int: default
# - char 128: timestamp etc. (system defined)
# - char 256: title, name etc (user defined)
# - char 1024: url
# - char 4096: description

CREATE TABLE AuthWidget
(
 AuthWidgetId INT NOT NULL AUTO_INCREMENT,
 AuthWidgetWID VARCHAR(128),
 AuthWidgetOwner VARCHAR(128),
 AuthWidgetName VARCHAR(128),
 AuthWidgetKey VARCHAR(128),
 AuthWidgetNetwork VARCHAR(128),
 AuthWidgetLanguage VARCHAR(128),
 PRIMARY KEY (AuthWidgetId),
 UNIQUE INDEX WidgetIndex (AuthWidgetWID)
) ENGINE = INNODB;

