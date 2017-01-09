#
# USER
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
# - every field in the database MUST be protected with an access rule
# - access rules consist of attribute=value pairs, for example: name=work OR level=1
# - attribute: name matches on AccessGroup.AccessGroupName
#              level matches on AccessGroup.AccessGroupLevel
#
CREATE TABLE AccessRule
(
 AccessRuleId INT NOT NULL AUTO_INCREMENT,
 AccessRuleAttribute VARCHAR(128),
 AccessRuleValue VARCHAR(128),
 PRIMARY KEY (AccessRuleId)
) ENGINE = INNODB;



##########
# USER
# - user is the main table
# - this table only defines the necessary fields for an user
# - every other user characteristic is stored in an annotation table in a single record to support AccessRules per characteristic.
#
CREATE TABLE User
(
 UserId INT NOT NULL AUTO_INCREMENT,
 UserFirstName VARCHAR(256),
 UserLastName VARCHAR(256),
 UserLoginName VARCHAR(128),
 UserPageName VARCHAR(1024),
 UserTimestamp VARCHAR(128),
 UserViews INT NOT NULL,
 Reference VARCHAR(128) NOT NULL,
 AccessRuleId INT NOT NULL,
 PRIMARY KEY (UserId),
 FOREIGN KEY (AccessRuleId) REFERENCES AccessRule (AccessRuleId),
 UNIQUE INDEX ReferenceIndex (Reference)
) ENGINE = INNODB;

# Single attribute = value characteristics are stored in UserAnnotation. Examples:
# - gender = male
# - dateofbirth = 9-9-1970
#
CREATE TABLE UserAnnotation
(
 AnnotationId INT NOT NULL AUTO_INCREMENT,
 AnnotationAttribute VARCHAR(256),
 AnnotationValue VARCHAR(4096),
 AnnotationType VARCHAR(128),
 UserId INT NOT NULL,
 AccessRuleId INT NOT NULL,
 PRIMARY KEY (AnnotationId),
 FOREIGN KEY (UserId) REFERENCES User (UserId) ON DELETE CASCADE,
 FOREIGN KEY (AccessRuleId) REFERENCES AccessRule (AccessRuleId)
) ENGINE = INNODB;

# Plural attribute = value characteristics are stored in UserProfile & UserProfileAnnotation. Examples:
# - language profile 		(profileGroup=language)
#   - speaks = dutch 		(profileName=mothertongue)
#   - speaks = english 		(profileName=second language)
# - job interests profile
#   - interest = programming & skill = 4
#   - interest = architecture & skill = 5
#
CREATE TABLE UserProfile
(
 ProfileId INT NOT NULL AUTO_INCREMENT,
 ProfileGroup VARCHAR(256),
 ProfileName VARCHAR(256),
 UserId INT NOT NULL,
 AccessRuleId INT NOT NULL,
 PRIMARY KEY (ProfileId),
 FOREIGN KEY (UserId) REFERENCES User (UserId) ON DELETE CASCADE,
 FOREIGN KEY (AccessRuleId) REFERENCES AccessRule (AccessRuleId)
) ENGINE = INNODB;

CREATE TABLE UserProfileAnnotation
(
 AnnotationId INT NOT NULL AUTO_INCREMENT,
 AnnotationAttribute VARCHAR(256),
 AnnotationValue VARCHAR(4096),
 AnnotationType VARCHAR(128),
 ProfileId INT NOT NULL,
 PRIMARY KEY (AnnotationId),
 FOREIGN KEY (ProfileId) REFERENCES UserProfile (ProfileId) ON DELETE CASCADE
) ENGINE = INNODB;



##########
# SETTINGS
# - settings and preferences of the user in the context of the website that uses this api
#
CREATE TABLE UserSettings
(
 UserSettingsId INT NOT NULL AUTO_INCREMENT,
 UserSettingsAttribute VARCHAR(256),
 UserSettingsValue VARCHAR(4096),
 UserId INT NOT NULL,
 PRIMARY KEY (UserSettingsId),
 FOREIGN KEY (UserId) REFERENCES User (UserId) ON DELETE CASCADE
) ENGINE = INNODB;

# extra data of the user in the context of the website that uses this api
#
CREATE TABLE UserData
(
 UserDataId INT NOT NULL AUTO_INCREMENT,
 UserDataAttribute VARCHAR(256),
 UserDataValue VARCHAR(4096),
 UserId INT NOT NULL,
 PRIMARY KEY (UserDataId),
 FOREIGN KEY (UserId) REFERENCES User (UserId) ON DELETE CASCADE
) ENGINE = INNODB;



##########
# RELATIONS
# - users have relations with eachother.
# - relations are grouped in UserGroup (friends, family, colleges, etc.) OR relation are one-on-one in UserRelation
# - there are three type of groups:
#   1. (personal) personal groups: private groups of a user to list his/her friends (compare with favorites)
#   2. (tribe) application groups: groups defined by the application that uses them
#   3. (private/public/invitation) public groups: public groups of a user where other users can participate.
# - relation trust is defined in AccessGroups (name=work, name=private, level=1, level=2)
# - a group defines a generic trust level for a group of users.
# - a relation defines a specific trust level between two users
# - groups are organized in applications, to control which applications can access which user profiles.
#
CREATE TABLE AccessApp
(
 AccessAppId INT NOT NULL AUTO_INCREMENT,
 AccessAppName VARCHAR(256),
 AccessAppKey VARCHAR(256),
 PRIMARY KEY (AccessAppId)
) ENGINE = INNODB;

CREATE TABLE AccessGroup
(
 AccessGroupId INT NOT NULL AUTO_INCREMENT,
 AccessGroupName VARCHAR(128),
 AccessGroupLevel INT NOT NULL,
 PRIMARY KEY (AccessGroupId)
) ENGINE = INNODB;

CREATE TABLE UserGroup
(
 UserGroupId INT NOT NULL AUTO_INCREMENT,
 UserGroupName VARCHAR(256),
 UserGroupDescription VARCHAR(4096),
 UserGroupType VARCHAR(128),
 UserGroupTimestamp VARCHAR(128),
 UserId INT NOT NULL,
 AccessGroupId INT NOT NULL,
 PRIMARY KEY (UserGroupId),
 FOREIGN KEY (UserId) REFERENCES User (UserId) ON DELETE CASCADE,
 FOREIGN KEY (AccessGroupId) REFERENCES AccessGroup (AccessGroupId)
) ENGINE = INNODB;

CREATE TABLE UserRelation
(
 UserRelationId INT NOT NULL AUTO_INCREMENT,
 UserRelationUserId INT NOT NULL,
 UserId INT NOT NULL,
 AccessGroupId INT NOT NULL,
 PRIMARY KEY (UserRelationId),
 INDEX UserIndex (UserRelationUserId),
 FOREIGN KEY (UserId) REFERENCES User (UserId) ON DELETE CASCADE,
 FOREIGN KEY (AccessGroupId) REFERENCES AccessGroup (AccessGroupId)
) ENGINE = INNODB;

CREATE TABLE UserInGroup
(
 UserId INT NOT NULL,
 UserGroupId INT NOT NULL,
 PRIMARY KEY (UserId, UserGroupId),
 FOREIGN KEY (UserId) REFERENCES User (UserId) on DELETE CASCADE,
 FOREIGN KEY (UserGroupId) REFERENCES UserGroup (UserGroupId) on DELETE CASCADE
) ENGINE = INNODB;

CREATE TABLE UserGroupInApp
(
 UserGroupId INT NOT NULL,
 AccessAppId INT NOT NULL,
 PRIMARY KEY (UserGroupId, AccessAppId),
 FOREIGN KEY (UserGroupId) REFERENCES UserGroup (UserGroupId) on DELETE CASCADE,
 FOREIGN KEY (AccessAppId) REFERENCES AccessApp (AccessAppId) on DELETE CASCADE
) ENGINE = INNODB;

CREATE TABLE UserRelationManual
(
 UserRelationManualId INT NOT NULL AUTO_INCREMENT,
 UserRelationManualEmail VARCHAR(256),
 UserRelationManualFirstName VARCHAR(256),
 UserRelationManualLastName VARCHAR(256),
 UserRelationManualGender SMALLINT NOT NULL DEFAULT 0,
 UserRelationManualDateOfBirth VARCHAR(128),
 UserRelationManualAddress VARCHAR(256),
 UserRelationManualPostalCode VARCHAR(128),
 UserRelationManualCity VARCHAR(256),
 UserRelationManualCountry VARCHAR(256),
 UserRelationManualPhone VARCHAR(128),
 UserRelationManualCellPhone VARCHAR(128),
 UserRelationId INT NOT NULL,
 UserGroupId INT NOT NULL,
 AccessGroupId INT NOT NULL,
 PRIMARY KEY (UserRelationManualId),
 FOREIGN KEY (UserGroupId) REFERENCES UserGroup (UserGroupId),
 FOREIGN KEY (AccessGroupId) REFERENCES AccessGroup (AccessGroupId)
) ENGINE = INNODB;

CREATE TABLE UserInvitation
(
 UserInvitationId INT NOT NULL AUTO_INCREMENT,
 UserInvitationMail VARCHAR(256),
 UserInvitationKey VARCHAR(128),
 UserInvitationTimestamp VARCHAR(128),
 UserId INT NOT NULL,
 PRIMARY KEY (UserInvitationId),
 INDEX KeyIndex (UserInvitationKey),
 FOREIGN KEY (UserId) REFERENCES User (UserId) ON DELETE CASCADE
) ENGINE = INNODB;



##########
# SUPPLEMENT
# - some user characteristics have their own structure, these are
#   - Activity (user activity in this website)
# - other user characteristics have free format
#   - Address (home address, bill address, work address)
#   - Contact (telephone, email, im)
#   - Organization (jobs in past and present)
#   - Publication (publication in social networks, blogs)
#   - Experience (experience with products)

# Activity: activity information
# - examples:
#   - 10-9-2010, new relation, herman and edwin became friends, 1, /activity/1
#
CREATE TABLE UserActivity
(
 UserActivityId INT NOT NULL AUTO_INCREMENT,
 UserActivityTimestamp VARCHAR(128),
 UserActivityTitle VARCHAR(256),
 UserActivityDescription VARCHAR(4096),
 UserActivityPriority SMALLINT NOT NULL DEFAULT 0,
 UserActivityUrl VARCHAR(1024),
 UserId INT NOT NULL,
 AccessRuleId INT NOT NULL,
 PRIMARY KEY (UserActivityId),
 FOREIGN KEY (UserId) REFERENCES User (UserId) ON DELETE CASCADE,
 FOREIGN KEY (AccessRuleId) REFERENCES AccessRule (AccessRuleId)
) ENGINE = INNODB;

# ADDRESS: address information
# - type: home, 2nd home, billing, shipping, work
#
CREATE TABLE UserAddress
(
 SectionId INT NOT NULL AUTO_INCREMENT,
 SectionType VARCHAR(128),
 SectionName VARCHAR(256),
 UserId INT NOT NULL,
 AccessRuleId INT NOT NULL,
 PRIMARY KEY (SectionId),
 FOREIGN KEY (UserId) REFERENCES User (UserId) ON DELETE CASCADE,
 FOREIGN KEY (AccessRuleId) REFERENCES AccessRule (AccessRuleId)
) ENGINE = INNODB;

# Single attribute = value characteristics are stored in Annotation.
CREATE TABLE UserAddressAnnotation
(
 AnnotationId INT NOT NULL AUTO_INCREMENT,
 AnnotationAttribute VARCHAR(256),
 AnnotationValue VARCHAR(4096),
 AnnotationType VARCHAR(128),
 SectionId INT NOT NULL,
 AccessRuleId INT NOT NULL,
 PRIMARY KEY (AnnotationId),
 FOREIGN KEY (SectionId) REFERENCES UserAddress (SectionId) ON DELETE CASCADE,
 FOREIGN KEY (AccessRuleId) REFERENCES AccessRule (AccessRuleId)
) ENGINE = INNODB;

# Plural attribute = value characteristics are stored in Profile & ProfileAnnotation.
CREATE TABLE UserAddressProfile
(
 ProfileId INT NOT NULL AUTO_INCREMENT,
 ProfileGroup VARCHAR(256),
 ProfileName VARCHAR(256),
 SectionId INT NOT NULL,
 AccessRuleId INT NOT NULL,
 PRIMARY KEY (ProfileId),
 FOREIGN KEY (SectionId) REFERENCES UserAddress (SectionId) ON DELETE CASCADE,
 FOREIGN KEY (AccessRuleId) REFERENCES AccessRule (AccessRuleId)
) ENGINE = INNODB;

CREATE TABLE UserAddressProfileAnnotation
(
 AnnotationId INT NOT NULL AUTO_INCREMENT,
 AnnotationAttribute VARCHAR(256),
 AnnotationValue VARCHAR(4096),
 AnnotationType VARCHAR(128),
 ProfileId INT NOT NULL,
 PRIMARY KEY (AnnotationId),
 FOREIGN KEY (ProfileId) REFERENCES UserAddressProfile (ProfileId) ON DELETE CASCADE
) ENGINE = INNODB;

# CONTACT: contact information
# - type: phone, cellphone, fax, semafoon, email, im
# - examples:
#   - cellphone, private, +310612345678
#   - email, private, herman@dompseler.nl
#   - im, skype, herman.van.dompseler 
#
CREATE TABLE UserContact
(
 SectionId INT NOT NULL AUTO_INCREMENT,
 SectionType VARCHAR(128),
 SectionName VARCHAR(256),
 UserId INT NOT NULL,
 AccessRuleId INT NOT NULL,
 PRIMARY KEY (SectionId),
 FOREIGN KEY (UserId) REFERENCES User (UserId) ON DELETE CASCADE,
 FOREIGN KEY (AccessRuleId) REFERENCES AccessRule (AccessRuleId)
) ENGINE = INNODB;

# Single attribute = value characteristics are stored in Annotation.
CREATE TABLE UserContactAnnotation
(
 AnnotationId INT NOT NULL AUTO_INCREMENT,
 AnnotationAttribute VARCHAR(256),
 AnnotationValue VARCHAR(4096),
 AnnotationType VARCHAR(128),
 SectionId INT NOT NULL,
 AccessRuleId INT NOT NULL,
 PRIMARY KEY (AnnotationId),
 FOREIGN KEY (SectionId) REFERENCES UserContact (SectionId) ON DELETE CASCADE,
 FOREIGN KEY (AccessRuleId) REFERENCES AccessRule (AccessRuleId)
) ENGINE = INNODB;

# Plural attribute = value characteristics are stored in Profile & ProfileAnnotation.
CREATE TABLE UserContactProfile
(
 ProfileId INT NOT NULL AUTO_INCREMENT,
 ProfileGroup VARCHAR(256),
 ProfileName VARCHAR(256),
 SectionId INT NOT NULL,
 AccessRuleId INT NOT NULL,
 PRIMARY KEY (ProfileId),
 FOREIGN KEY (SectionId) REFERENCES UserContact (SectionId) ON DELETE CASCADE,
 FOREIGN KEY (AccessRuleId) REFERENCES AccessRule (AccessRuleId)
) ENGINE = INNODB;

CREATE TABLE UserContactProfileAnnotation
(
 AnnotationId INT NOT NULL AUTO_INCREMENT,
 AnnotationAttribute VARCHAR(256),
 AnnotationValue VARCHAR(4096),
 AnnotationType VARCHAR(128),
 ProfileId INT NOT NULL,
 PRIMARY KEY (AnnotationId),
 FOREIGN KEY (ProfileId) REFERENCES UserContactProfile (ProfileId) ON DELETE CASCADE
) ENGINE = INNODB;

# ORGANIZATION: work information
# - type: present, past
#
CREATE TABLE UserOrganization
(
 SectionId INT NOT NULL AUTO_INCREMENT,
 SectionType VARCHAR(128),
 SectionName VARCHAR(256),
 UserId INT NOT NULL,
 AccessRuleId INT NOT NULL,
 PRIMARY KEY (SectionId),
 FOREIGN KEY (UserId) REFERENCES User (UserId) ON DELETE CASCADE,
 FOREIGN KEY (AccessRuleId) REFERENCES AccessRule (AccessRuleId)
) ENGINE = INNODB;

# Single attribute = value characteristics are stored in Annotation.
CREATE TABLE UserOrganizationAnnotation
(
 AnnotationId INT NOT NULL AUTO_INCREMENT,
 AnnotationAttribute VARCHAR(256),
 AnnotationValue VARCHAR(4096),
 AnnotationType VARCHAR(128),
 SectionId INT NOT NULL,
 AccessRuleId INT NOT NULL,
 PRIMARY KEY (AnnotationId),
 FOREIGN KEY (SectionId) REFERENCES UserOrganization (SectionId) ON DELETE CASCADE,
 FOREIGN KEY (AccessRuleId) REFERENCES AccessRule (AccessRuleId)
) ENGINE = INNODB;

# Plural attribute = value characteristics are stored in Profile & ProfileAnnotation.
CREATE TABLE UserOrganizationProfile
(
 ProfileId INT NOT NULL AUTO_INCREMENT,
 ProfileGroup VARCHAR(256),
 ProfileName VARCHAR(256),
 SectionId INT NOT NULL,
 AccessRuleId INT NOT NULL,
 PRIMARY KEY (ProfileId),
 FOREIGN KEY (SectionId) REFERENCES UserOrganization (SectionId) ON DELETE CASCADE,
 FOREIGN KEY (AccessRuleId) REFERENCES AccessRule (AccessRuleId)
) ENGINE = INNODB;

CREATE TABLE UserOrganizationProfileAnnotation
(
 AnnotationId INT NOT NULL AUTO_INCREMENT,
 AnnotationAttribute VARCHAR(256),
 AnnotationValue VARCHAR(4096),
 AnnotationType VARCHAR(128),
 ProfileId INT NOT NULL,
 PRIMARY KEY (AnnotationId),
 FOREIGN KEY (ProfileId) REFERENCES UserOrganizationProfile (ProfileId) ON DELETE CASCADE
) ENGINE = INNODB;

# PUBLICATION: publicatie information
# - type: social (hyves, facebook), blog (blogger, twitter), share (flick, facebook)
#
CREATE TABLE UserPublication
(
 SectionId INT NOT NULL AUTO_INCREMENT,
 SectionType VARCHAR(128),
 SectionName VARCHAR(256),
 UserId INT NOT NULL,
 AccessRuleId INT NOT NULL,
 PRIMARY KEY (SectionId),
 FOREIGN KEY (UserId) REFERENCES User (UserId) ON DELETE CASCADE,
 FOREIGN KEY (AccessRuleId) REFERENCES AccessRule (AccessRuleId)
) ENGINE = INNODB;

# Single attribute = value characteristics are stored in Annotation.
CREATE TABLE UserPublicationAnnotation
(
 AnnotationId INT NOT NULL AUTO_INCREMENT,
 AnnotationAttribute VARCHAR(256),
 AnnotationValue VARCHAR(4096),
 AnnotationType VARCHAR(128),
 SectionId INT NOT NULL,
 AccessRuleId INT NOT NULL,
 PRIMARY KEY (AnnotationId),
 FOREIGN KEY (SectionId) REFERENCES UserPublication (SectionId) ON DELETE CASCADE,
 FOREIGN KEY (AccessRuleId) REFERENCES AccessRule (AccessRuleId)
) ENGINE = INNODB;

# Plural attribute = value characteristics are stored in Profile & ProfileAnnotation.
CREATE TABLE UserPublicationProfile
(
 ProfileId INT NOT NULL AUTO_INCREMENT,
 ProfileGroup VARCHAR(256),
 ProfileName VARCHAR(256),
 SectionId INT NOT NULL,
 AccessRuleId INT NOT NULL,
 PRIMARY KEY (ProfileId),
 FOREIGN KEY (SectionId) REFERENCES UserPublication (SectionId) ON DELETE CASCADE,
 FOREIGN KEY (AccessRuleId) REFERENCES AccessRule (AccessRuleId)
) ENGINE = INNODB;

CREATE TABLE UserPublicationProfileAnnotation
(
 AnnotationId INT NOT NULL AUTO_INCREMENT,
 AnnotationAttribute VARCHAR(256),
 AnnotationValue VARCHAR(4096),
 AnnotationType VARCHAR(128),
 ProfileId INT NOT NULL,
 PRIMARY KEY (AnnotationId),
 FOREIGN KEY (ProfileId) REFERENCES UserPublicationProfile (ProfileId) ON DELETE CASCADE
) ENGINE = INNODB;

# EXPERIENCE: experience information
# - type: products, organizations, courses
# - product examples: car, books
# - organization examples: lost boys
#
CREATE TABLE UserExperience
(
 SectionId INT NOT NULL AUTO_INCREMENT,
 SectionType VARCHAR(128),
 SectionName VARCHAR(256),
 UserId INT NOT NULL,
 AccessRuleId INT NOT NULL,
 PRIMARY KEY (SectionId),
 FOREIGN KEY (UserId) REFERENCES User (UserId) ON DELETE CASCADE,
 FOREIGN KEY (AccessRuleId) REFERENCES AccessRule (AccessRuleId)
) ENGINE = INNODB;

# Single attribute = value characteristics are stored in Annotation.
CREATE TABLE UserExperienceAnnotation
(
 AnnotationId INT NOT NULL AUTO_INCREMENT,
 AnnotationAttribute VARCHAR(256),
 AnnotationValue VARCHAR(4096),
 AnnotationType VARCHAR(128),
 SectionId INT NOT NULL,
 AccessRuleId INT NOT NULL,
 PRIMARY KEY (AnnotationId),
 FOREIGN KEY (SectionId) REFERENCES UserExperience (SectionId) ON DELETE CASCADE,
 FOREIGN KEY (AccessRuleId) REFERENCES AccessRule (AccessRuleId)
) ENGINE = INNODB;

# Plural attribute = value characteristics are stored in Profile & ProfileAnnotation.
CREATE TABLE UserExperienceProfile
(
 ProfileId INT NOT NULL AUTO_INCREMENT,
 ProfileGroup VARCHAR(256),
 ProfileName VARCHAR(256),
 SectionId INT NOT NULL,
 AccessRuleId INT NOT NULL,
 PRIMARY KEY (ProfileId),
 FOREIGN KEY (SectionId) REFERENCES UserExperience (SectionId) ON DELETE CASCADE,
 FOREIGN KEY (AccessRuleId) REFERENCES AccessRule (AccessRuleId)
) ENGINE = INNODB;

CREATE TABLE UserExperienceProfileAnnotation
(
 AnnotationId INT NOT NULL AUTO_INCREMENT,
 AnnotationAttribute VARCHAR(256),
 AnnotationValue VARCHAR(4096),
 AnnotationType VARCHAR(128),
 ProfileId INT NOT NULL,
 PRIMARY KEY (AnnotationId),
 FOREIGN KEY (ProfileId) REFERENCES UserExperienceProfile (ProfileId) ON DELETE CASCADE
) ENGINE = INNODB;

