CREATE TABLE Users (
  Uid          INT UNSIGNED      PRIMARY KEY AUTO_INCREMENT,
  UserName     VARCHAR(64)       UNIQUE,
  Email        VARCHAR(128)      NOT NULL UNIQUE,
  Salt         SMALLINT UNSIGNED NOT NULL,
  PasswordHash BINARY(40)        NOT NULL,
  FirstName    VARCHAR(64)       NOT NULL,
  LastName     VARCHAR(64)       NOT NULL,
  Phone        CHAR(10)                 ,
  Address      VARCHAR(128)             ,
  City         VARCHAR(64)              ,
  State        CHAR(2)                  ,
  ZipCode      VARCHAR(10)
);
CREATE TABLE Checkin (
   Uid         INT UNSIGNED     NOT NULL,
   DateTime    DATETIME         NOT NULL
);

CREATE TABLE SleepLog (
  LogID       INT UNSIGNED     PRIMARY KEY AUTO_INCREMENT,
  StartTime   DATETIME         NOT NULL UNIQUE,
  StopTime    DATETIME         NOT NULL UNIQUE
);


