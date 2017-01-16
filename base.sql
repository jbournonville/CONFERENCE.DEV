/*DROP DATABASE event_booking;*/

CREATE DATABASE event_booking;

USE event_booking;

/*--------------------------------------------------------------------*\
TABLE MODULES
\*--------------------------------------------------------------------*/
CREATE TABLE modules
(
  id          INT PRIMARY KEY AUTO_INCREMENT,
  module      VARCHAR(100),
  description TEXT
);
INSERT INTO modules (module, description) VALUES ('Keynote', 'Presentation des nouveautées d\'Apple');
INSERT INTO modules (module, description) VALUES ('Xcode', 'Presentation du logiciel Xcode');
INSERT INTO modules (module, description) VALUES ('iOS', 'Presentation du nouvel os pour les iDevices');
INSERT INTO modules (module, description)
VALUES ('macOS', 'Presentation du nouvel os pour les Macintosh');
INSERT INTO modules (module, description)
VALUES ('watchOS', 'Presentation du nouvel os pour les Apple Watch');
INSERT INTO modules (module, description) VALUES ('tvOS', 'Presentation du nouvel os pour les Apple TV');

/*--------------------------------------------------------------------*\
TABLE EVENTS
\*--------------------------------------------------------------------*/
CREATE TABLE events
(
  id                   INT PRIMARY KEY AUTO_INCREMENT,
  event                VARCHAR(100),
  address              VARCHAR(255),
  description          TEXT,
  eventDate            DATE,
  duration             INT,
  nbSlotsPerDay        INT,
  nbSpeakerMaxByModule INT,
  bookingOpen          BOOLEAN         DEFAULT FALSE
);
INSERT INTO events (event, address, eventDate, duration, nbSlotsPerDay, nbSpeakerMaxByModule) VALUE (
  'WWDC16',
  'Cupertino',
  '2016/09/09',
  3,
  3,
  2
);
INSERT INTO events (event, address, description, eventDate, duration, nbSlotsPerDay, nbSpeakerMaxByModule, bookingOpen)
  VALUE (
  'WWDC17',
  'Cupertino',
  'Conférence Mondiale 2017 pour les développeurs iOS et MacOs ',
  '2017/09/09',
  2,
  3,
  2,
  TRUE
);
INSERT INTO events (event, address, description, eventDate, duration, nbSlotsPerDay, nbSpeakerMaxByModule)
  VALUE (
  'WWDC18',
  'Cupertino',
  'Conférence Mondiale 2018 pour les développeurs iOS et MacOs ',
  '2018/09/09',
  2,
  3,
  2
);


/*--------------------------------------------------------------------*\
TABLE HALLS
\*--------------------------------------------------------------------*/
CREATE TABLE halls
(
  id       INT PRIMARY KEY AUTO_INCREMENT,
  hall     VARCHAR(30),
  building VARCHAR(60),
  picture  TEXT,
  capacity INT
);
INSERT INTO halls (hall, capacity, building, picture)
VALUES ('Main', 500, 'A1-001', 'img/halls/main.jpg');
INSERT INTO halls (hall, capacity, building, picture)
VALUES ('Hall1', 100, 'A2-001', 'img/halls/hall1.jpg');
INSERT INTO halls (hall, capacity, building, picture)
VALUES ('Hall2', 50, 'A2-001', 'img/halls/hall2.jpg');
INSERT INTO halls (hall, capacity, building, picture)
VALUES ('Hall3', 50, 'A2-002', 'img/halls/hall3.jpg');
INSERT INTO halls (hall, capacity, building, picture)
VALUES ('Hall4', 50, 'A3-001', 'img/halls/hall4.jpg');
INSERT INTO halls (hall, capacity, building, picture)
VALUES ('Hall5', 2, 'A3-002', 'img/halls/hall5.jpg');

/*--------------------------------------------------------------------*\
TABLE SLOTS
\*--------------------------------------------------------------------*/
CREATE TABLE slots
(
  id      INT PRIMARY KEY AUTO_INCREMENT,
  idEvent INT,
  slot    DATETIME,

  CONSTRAINT session_event_id_fk FOREIGN KEY (idEvent) REFERENCES events (id)
);

INSERT INTO slots (idEvent, slot)
VALUES (2, '2017-09-09 10:00:00');
INSERT INTO slots (idEvent, slot)
VALUES (2, '2017-09-09 14:00:00');
INSERT INTO slots (idEvent, slot)
VALUES (2, '2017-09-09 16:00:00');
INSERT INTO slots (idEvent, slot)
VALUES (2, '2017-09-10 10:00:00');

/*--------------------------------------------------------------------*\
TABLE ROLES
\*--------------------------------------------------------------------*/
CREATE TABLE roles
(
  id   INT PRIMARY KEY AUTO_INCREMENT,
  role VARCHAR(30)
);
CREATE UNIQUE INDEX roles_role_uindex
  ON roles (role);

INSERT INTO roles (role) VALUES ('admin');
INSERT INTO roles (role) VALUES ('contributeur');
INSERT INTO roles (role) VALUES ('membre');


/*--------------------------------------------------------------------*\
TABLE USERS
\*--------------------------------------------------------------------*/
CREATE TABLE users (
  id       INT PRIMARY KEY AUTO_INCREMENT,
  username VARCHAR(30),
  email    VARCHAR(255),
  password VARCHAR(255),
  name     VARCHAR(30),
  surname  VARCHAR(30),
  idRole   INT,
  themeChoice VARCHAR(30) DEFAULT 'main1.css',
  lastActiveConnection TIMESTAMP NULL,

  CONSTRAINT membres_role_id_fk FOREIGN KEY (idRole) REFERENCES roles (id)
);

INSERT INTO users (name, surname, email, username, idRole, password) VALUES
  (' ', 'Administrateur', 'adm@conference.fr', 'adm', 1,
   '$2y$10$AHhvaGCuVR0evH6kQb7Fku.kznlFP2PKo2s4xuEKpY4T.Owh/9sHK');
INSERT INTO users (name, surname, email, username, idRole, password)
VALUES (' ', 'Speaker', 'spk@conference.fr', 'spk', 2, '$2y$10$P.LM.eSrrZLJoEJXwbDhROQf3wmWa26PEDgOb9yxE75TIFywnJ4TO');
INSERT INTO users (name, surname, email, username, idRole, password)
VALUES (' ', 'User', 'usr@conference.fr', 'usr', 3, '$2y$10$NnxHSgWMSLdLyhGIvsI30uqcxnqcBgRNTs8UrJmaV5uvQRqpaB7mu');
INSERT INTO users (name, surname, email, username, idRole, password) VALUES
  ('BOURNONVILLE', 'Julien', 'jbo@conference.fr', 'jbo', 3,
   '$2y$10$U3swoaSgKPAgFR5xmW/3xOqGXFdQC3RolhwkFffx1c/lm9CVlGCZO');
INSERT INTO users (username, email, name, surname, idRole)
VALUES ('jharvey0', 'jharvey0@sun.com', 'Harvey', 'Judith', 2);
INSERT INTO users (username, email, name, surname, idRole)
VALUES ('tmiller1', 'tmiller1@ucoz.ru', 'Miller', 'Teresa', 2);
INSERT INTO users (username, email, name, surname, idRole)
VALUES ('jmccoy2', 'jmccoy2@google.co.uk', 'Mccoy', 'Joyce', 2);
INSERT INTO users (username, email, name, surname, idRole)
VALUES ('jrichards3', 'jrichards3@geocities.com', 'Richards', 'Joyce', 2);
INSERT INTO users (username, email, name, surname, idRole)
VALUES ('mperry4', 'mperry4@technorati.com', 'Perry', 'Mark', 3);
INSERT INTO users (username, email, name, surname, idRole)
VALUES ('jholmes5', 'jholmes5@surveymonkey.com', 'Holmes', 'Jason', 3);
INSERT INTO users (username, email, name, surname, idRole)
VALUES ('jcole6', 'jcole6@bigcartel.com', 'Cole', 'Jimmy', 2);
INSERT INTO users (username, email, name, surname, idRole)
VALUES ('kcarr7', 'kcarr7@alexa.com', 'Carr', 'Kathleen', 3);
INSERT INTO users (username, email, name, surname, idRole)
VALUES ('ahughes8', 'ahughes8@army.mil', 'Hughes', 'Aaron', 3);
INSERT INTO users (username, email, name, surname, idRole)
VALUES ('tpierce9', 'tpierce9@google.de', 'Pierce', 'Tina', 2);
INSERT INTO users (username, email, name, surname, idRole)
VALUES ('jfranklina', 'jfranklina@gmpg.org', 'Franklin', 'Juan', 2);
INSERT INTO users (username, email, name, surname, idRole)
VALUES ('rsimsb', 'rsimsb@istockphoto.com', 'Sims', 'Roger', 2);
INSERT INTO users (username, email, name, surname, idRole)
VALUES ('kramosc', 'kramosc@dion.ne.jp', 'Ramos', 'Keith', 3);
INSERT INTO users (username, email, name, surname, idRole)
VALUES ('rramosd', 'rramosd@wikispaces.com', 'Ramos', 'Roy', 2);
INSERT INTO users (username, email, name, surname, idRole)
VALUES ('jellise', 'jellise@plala.or.jp', 'Ellis', 'Joan', 3);
INSERT INTO users (username, email, name, surname, idRole)
VALUES ('lreynoldsf', 'lreynoldsf@ft.com', 'Reynolds', 'Lawrence', 3);
INSERT INTO users (username, email, name, surname, idRole)
VALUES ('rwashingtong', 'rwashingtong@ucla.edu', 'Washington', 'Richard', 3);
INSERT INTO users (username, email, name, surname, idRole)
VALUES ('brussellh', 'brussellh@flavors.me', 'Russell', 'Bonnie', 2);
INSERT INTO users (username, email, name, surname, idRole)
VALUES ('hwheeleri', 'hwheeleri@wired.com', 'Wheeler', 'Howard', 3);
INSERT INTO users (username, email, name, surname, idRole)
VALUES ('pblackj', 'pblackj@nasa.gov', 'Black', 'Phyllis', 3);
INSERT INTO users (username, email, name, surname, idRole)
VALUES ('lcolek', 'lcolek@sakura.ne.jp', 'Cole', 'Lillian', 3);
INSERT INTO users (username, email, name, surname, idRole)
VALUES ('blongl', 'blongl@comcast.net', 'Long', 'Benjamin', 2);
INSERT INTO users (username, email, name, surname, idRole)
VALUES ('jporterm', 'jporterm@psu.edu', 'Porter', 'Jeffrey', 3);
INSERT INTO users (username, email, name, surname, idRole)
VALUES ('lgrayn', 'lgrayn@google.com.au', 'Gray', 'Linda', 2);
INSERT INTO users (username, email, name, surname, idRole)
VALUES ('pbakero', 'pbakero@wikispaces.com', 'Baker', 'Philip', 2);

/*--------------------------------------------------------------------*\
TABLE SESSIONS
\*--------------------------------------------------------------------*/
CREATE TABLE sessions (
  id          INT PRIMARY KEY AUTO_INCREMENT,
  idModule    INT,
  idHall      INT,
  idSlot      INT,
  mainSession BOOLEAN         DEFAULT FALSE,

  CONSTRAINT session_slot_id_fk FOREIGN KEY (idSlot) REFERENCES slots (id),
  CONSTRAINT session_module_id_fk FOREIGN KEY (idModule) REFERENCES modules (id),
  CONSTRAINT session_hall_id_fk FOREIGN KEY (idHall) REFERENCES halls (id)
);

INSERT INTO sessions (idSlot, idModule, idHall, mainSession) VALUES (1, 1, 1, TRUE);
INSERT INTO sessions (idSlot, idModule, idHall) VALUES (2, 2, 1);
INSERT INTO sessions (idSlot, idModule, idHall) VALUES (2, 4, 2);
INSERT INTO sessions (idSlot, idModule, idHall) VALUES (2, 3, 3);
INSERT INTO sessions (idSlot, idModule, idHall) VALUES (3, 4, 4);
INSERT INTO sessions (idSlot, idModule, idHall) VALUES (4, 2, 1);

/*--------------------------------------------------------------------*\
TABLE SESSIONS_SPEAKERS
\*--------------------------------------------------------------------*/
CREATE TABLE sessions_speakers
(
  idSession INT,
  idUser    INT,
  CONSTRAINT sessions_speaker_idSession_idUser_pk PRIMARY KEY (idSession, idUser),
  CONSTRAINT sessions_speaker_sessions_id_fk FOREIGN KEY (idSession) REFERENCES sessions (id)
    ON DELETE CASCADE,
  CONSTRAINT sessions_speaker_users_id_fk FOREIGN KEY (idUser) REFERENCES users (id)
    ON DELETE CASCADE
);

INSERT INTO sessions_speakers (idSession, idUser) VALUES (1, 2);
INSERT INTO sessions_speakers (idSession, idUser) VALUES (1, 6);
INSERT INTO sessions_speakers (idSession, idUser) VALUES (2, 2);
INSERT INTO sessions_speakers (idSession, idUser) VALUES (2, 14);
INSERT INTO sessions_speakers (idSession, idUser) VALUES (3, 11);

/*--------------------------------------------------------------------*\
TABLE SESSIONS_AUDITORS
\*--------------------------------------------------------------------*/
CREATE TABLE sessions_auditors
(
  idSession INT,
  idUser    INT,
  CONSTRAINT sessions_auditors_idSession_idUser_pk PRIMARY KEY (idSession, idUser),
  CONSTRAINT sessions_auditors_sessions_id_fk FOREIGN KEY (idSession) REFERENCES sessions (id),
  CONSTRAINT sessions_auditors_users_id_fk FOREIGN KEY (idUser) REFERENCES users (id)
);

INSERT INTO sessions_auditors (idSession, idUser) VALUES (1,1);
INSERT INTO sessions_auditors (idSession, idUser) VALUES (4,1);
INSERT INTO sessions_auditors (idSession, idUser) VALUES (5,1);
INSERT INTO sessions_auditors (idSession, idUser) VALUES (6,1);
INSERT INTO sessions_auditors (idSession, idUser) VALUES (3,2);
INSERT INTO sessions_auditors (idSession, idUser) VALUES (3,3);
