DROP TABLE IF EXISTS comment; -- drop it like it's hot
DROP TABLE IF EXISTS friend; -- drop first bc created last
DROP TABLE IF EXISTS violation;
DROP TABLE IF EXISTS restaurant;
DROP TABLE IF EXISTS profile;
DROP TABLE IF EXISTS user; -- dropping this table last as it is created first

CREATE TABLE user (
	userId INT UNSIGNED AUTO_INCREMENT NOT NULL,
	hash CHAR(128),
	salt CHAR(64),
	name VARCHAR(64),
	email VARCHAR(64) NOT NULL,
	INDEX(userId),
	PRIMARY KEY(userId),
	UNIQUE (email)
);

CREATE TABLE profile (
	profileId INT UNSIGNED AUTO_INCREMENT NOT NULL,
	userId INT UNSIGNED NOT NULL,
	email VARCHAR(64) NOT NULL,
	PRIMARY KEY(profileId),
	FOREIGN KEY(userId) REFERENCES user(userId),
	INDEX(profileId)
);

CREATE TABLE restaurant (
	restaurantId INT UNSIGNED AUTO_INCREMENT NOT NULL,
	address VARCHAR(128) NOT NULL,
	phone VARCHAR(32) NOT NULL,
	forkRating FLOAT(32) NOT NULL, -- this is a decimal value
	facilityKey VARCHAR(64) NOT NULL,
	googleId VARCHAR(128), -- Google assigns an alphanumeric code
	name VARCHAR(128) NOT NULL,
	INDEX(restaurantId),
	PRIMARY KEY(restaurantId), -- didn't assign a foreign key bc this entity apparently has none
	UNIQUE (facilityKey)
);

CREATE TABLE violation (
	violationId INT UNSIGNED AUTO_INCREMENT NOT NULL,
	restaurantId INT UNSIGNED NOT NULL,
	violationCode VARCHAR(8) NOT NULL,
	violationDesc VARCHAR(1024),
	inspectionMemo VARCHAR(1024),
	serialNum VARCHAR(12) NOT NULL, -- this value is alphanumeric, hence not an integer
	INDEX(violationId),
	PRIMARY KEY(violationId),
	FOREIGN KEY(restaurantId) REFERENCES restaurant(restaurantId)
);

CREATE TABLE comment (
	commentId INT UNSIGNED AUTO_INCREMENT NOT NULL,
	restaurantId INT UNSIGNED NOT NULL,
	userId INT UNSIGNED NOT NULL,
	dateTime DATETIME NOT NULL,
	content VARCHAR(1064) NOT NULL,
	INDEX(commentId),
	PRIMARY KEY(commentId),
	FOREIGN KEY(restaurantId) REFERENCES restaurant(restaurantId),
	FOREIGN KEY(userId) REFERENCES user(userId)
);

CREATE TABLE friend (
	firstUserId INT UNSIGNED NOT NULL,
	secondUserId INT UNSIGNED NOT NULL,
	dateFriended DATETIME NOT NULL,
	relationshipCode INT(12) NOT NULL,
	FOREIGN KEY(firstUserId) REFERENCES user(userId),
	FOREIGN KEY(firstUserId) REFERENCES user(userId)
);

