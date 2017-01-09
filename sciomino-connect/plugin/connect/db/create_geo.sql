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
