DROP TABLE IF EXISTS votedComment; -- dropping this table first as it is created last
DROP TABLE IF EXISTS friend;
DROP TABLE IF EXISTS likedRestaurant;
DROP TABLE IF EXISTS comment;
DROP TABLE IF EXISTS violation;
DROP TABLE IF EXISTS restaurant;
DROP TABLE IF EXISTS profile;
DROP TABLE IF EXISTS user; -- dropping this table last as it is created first

CREATE TABLE user (
	userId INT UNSIGNED AUTO_INCREMENT NOT NULL,
	INDEX(userId),
	PRIMARY KEY(userId)
);

CREATE TABLE profile (
	profileId INT UNSIGNED AUTO_INCREMENT NOT NULL,
	email VARCHAR(64) NOT NULL,
	PRIMARY KEY(profileId),
	FOREIGN KEY(userId) REFERENCES user(userId),
	INDEX(profileId)
);

CREATE TABLE restaurant (
	restaurantId INT UNSIGNED AUTO_INCREMENT NOT NULL,
	address VARCHAR(128) NOT NULL,
	phone INT(32) NOT NULL,
	forkRating INT(32) NOT NULL,
	facilityKey INT(12) NOT NULL,
	googleId VARCHAR(128), -- Google assigns an alphanumeric code
	INDEX(restaurantId),
	PRIMARY KEY(restaurantId) -- didn't assign a foreign key bc this entity apparently has none
);

CREATE TABLE violation (
	violationId INT UNSIGNED AUTO_INCREMENT NOT NULL,
	violationCode INT(12) NOT NULL,
	violationDesc VARCHAR(64) NOT NULL,
	inspectionMemo VARCHAR(256) NOT NULL,
	serialNum VARCHAR(12) NOT NULL, -- this value is alphanumeric, hence not an integer
	INDEX(violationId),
	PRIMARY KEY(violationId),
	FOREIGN KEY(restaurantId) REFERENCES restaurant(restaurantId)
);

CREATE TABLE comment (
	commentId INT UNSIGNED AUTO_INCREMENT NOT NULL,
	dateTime DATETIME NOT NULL,
	content VARCHAR(1064) NOT NULL,
	INDEX(commentId),
	PRIMARY KEY(commentId),
	FOREIGN KEY(restaurantId) REFERENCES restaurant(restaurantId),
	FOREIGN KEY(profileId) REFERENCES profileId
);

CREATE TABLE likedRestaurant (
	likedRestaurantId INT UNSIGNED AUTO_INCREMENT NOT NULL,
	INDEX(likedRestaurantId),
	FOREIGN KEY(restaurantId) REFERENCES restaurant(restaurantId),
	FOREIGN KEY(profileId) REFERENCES profile(profileId)
);

CREATE TABLE friend (
	friendId INT UNSIGNED AUTO_INCREMENT NOT NULL,
	firstProfileId, -- errmmm, I don't get this...
	secondProfileId, -- do we actually need friendId ?
	dateFriended DATETIME NOT NULL,
	relationshipCode INT(12) NOT NULL,
	FOREIGN KEY(profileId) REFERENCES profile(profileId)
);

CREATE TABLE votedComment (
	votedCommentId INT UNSIGNED AUTO_INCREMENT NOT NULL,
	voteType INT(8) NOT NULL, -- assuming it's some number
	INDEX(votedCommentId),
	FOREIGN KEY(commentId) REFERENCES comment(commentId),
	FOREIGN KEY(profileId) REFERENCES profile(profileId)
);