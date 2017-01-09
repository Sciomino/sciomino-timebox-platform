#
# Inverted Index
#
# fields:
# - smallint: 0 of 1
# - int: default
# - char 128: timestamp etc. (system defined)
# - char 256: title, name etc (user defined)
# - char 1024: url
# - char 4096: description

##########
# SEARCH
# - an inverted index to speed up searches
#   1. reference id's are connected to search words
#   2. a search word consists of the word itself AND the context of this word.
#
CREATE TABLE SearchWord
(
 SearchWordId INT NOT NULL AUTO_INCREMENT,
 SearchWordWord VARCHAR(256),
 SearchWordContext VARCHAR(256),
 SearchWordCount INT NOT NULL,
 PRIMARY KEY (SearchWordId),
 KEY (SearchWordWord)
) ENGINE = INNODB;

CREATE TABLE SearchIndex
(
 ReferenceId INT NOT NULL,
 SearchWordId INT NOT NULL,
 PRIMARY KEY (ReferenceId, SearchWordId),
 KEY (ReferenceId),
 FOREIGN KEY (SearchWordId) REFERENCES SearchWord (SearchWordId) on DELETE CASCADE
) ENGINE = INNODB;

