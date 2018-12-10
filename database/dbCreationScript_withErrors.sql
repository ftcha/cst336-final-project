-- *************** Used VHS Warehouse ****************;
-- *************** Tom Cruise Emporium ***************;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS user_roles, TRANSACTION, transactionDetails, users, states, roles, product;
SET FOREIGN_KEY_CHECKS = 1;
SET @@auto_increment_increment = 1;


-- ************************************** `states`

CREATE TABLE states
(
 stateCode VARCHAR(2) NOT NULL ,
 stateTax  DECIMAL(10,6) NOT NULL ,
 shipping DECIMAL(10,2) NOT NULL ,
PRIMARY KEY (stateCode)
);


-- ************************************** `users`

CREATE TABLE users
(
 userId   TINYINT NOT NULL AUTO_INCREMENT ,
 userName VARCHAR(40) NOT NULL ,
 PASSWORD CHAR(40) NOT NULL ,
 stateCode VARCHAR(2) NOT NULL ,
PRIMARY KEY (userId),
UNIQUE(userName),
CONSTRAINT FK_users_state FOREIGN KEY (stateCode) REFERENCES states (stateCode)
);


-- ************************************** `roles`

CREATE TABLE roles
(
 roleId          TINYINT NOT NULL AUTO_INCREMENT ,
 roleName        VARCHAR(45) NOT NULL ,
 roleDescription VARCHAR(45) NOT NULL ,
PRIMARY KEY (roleId)
);


-- ************************************** `product`

CREATE TABLE product
(
 productId   INT NOT NULL AUTO_INCREMENT ,
 NAME        VARCHAR(45) NOT NULL ,
 description VARCHAR(350) NOT NULL ,
 imageURL    VARCHAR(100) NOT NULL ,
 price       DECIMAL(10,2) NOT NULL ,
PRIMARY KEY (productId)
);


-- ************************************** `user_roles`

CREATE TABLE user_roles
(
 userId TINYINT NOT NULL ,
 roleId TINYINT NOT NULL ,
PRIMARY KEY (userId, roleId),
CONSTRAINT FK_user_roles_user FOREIGN KEY (userId) REFERENCES users (userId),
CONSTRAINT FK_user_roles_roles FOREIGN KEY (roleId) REFERENCES roles (roleId)
);


-- ************************************** `transaction`

CREATE TABLE TRANSACTION
(
 tranId INT NOT NULL AUTO_INCREMENT ,
 subtotal FLOAT,
 tax FLOAT,
 shipping FLOAT,
 userId TINYINT NOT NULL,
 PRIMARY KEY(tranId),
 CONSTRAINT FK_transaction_user FOREIGN KEY (userId) REFERENCES users (userId)
);


-- ************************************** `transactionDetails`

CREATE TABLE transactionDetails
(
 tranId     INT NOT NULL ,
 lineNumber INT NOT NULL,
 productId  INT NOT NULL ,
 PRIMARY KEY (tranId, lineNumber),
 CONSTRAINT FK_transactionDet_transaction FOREIGN KEY (tranId) REFERENCES TRANSACTION (tranId),
 CONSTRAINT FK_transaction_product FOREIGN KEY (productId) REFERENCES product (productId)
);


-- ******************** INSERT STATEMENTS **********************


INSERT INTO states (stateCode, stateTax, shipping) VALUES
('AL', 0.04, 7.95),
('AK', 0, 7.95),
('AZ', 0.056, 7.95),
('AR', 0.065, 7.95),
('CA', 0.0725, 7.95),
('CO', 0.029, 7.95),
('CT', 0.0635, 7.95),
('DE', 0, 7.95),
('FL', 0.06, 7.95),
('GA', 0.04, 7.95),
('HI', 0.04, 7.95),
('ID', 0.06, 7.95),
('IL', 0.0625, 7.95),
('IN', 0.07, 7.95),
('IA', 0.06, 7.95),
('KS', 0.065, 7.95),
('KY', 0.06, 7.95),
('LA', 0.05, 7.95),
('ME', 0.055, 7.95),
('MD', 0.06, 7.95),
('MA', 0.0625, 7.95),
('MI', 0.06, 7.95),
('MN', 0.06875, 7.95),
('MS', 0.07, 7.95),
('MO', 0.04225, 7.95),
('MT', 0, 7.95),
('NE', 0.055, 7.95),
('NV', 0.0685, 7.95),
('NH', 0, 7.95),
('NJ', 0.06625, 7.95),
('NM', 0.05125, 7.95),
('NY', 0.04, 7.95),
('NC', 0.0475, 7.95),
('ND', 0.05, 7.95),
('OH', 0.0575, 7.95),
('OK', 0.045, 7.95),
('OR', 0, 7.95),
('PA', 0.06, 7.95),
('RI', 0.07, 7.95),
('SC', 0.06, 7.95),
('SD', 0.045, 7.95),
('TN', 0.07, 7.95),
('TX', 0.0625, 7.95),
('UT', 0.0595, 7.95),
('VT', 0.06, 7.95),
('VA', 0.053, 7.95),
('WA', 0.065, 7.95),
('WV', 0.06, 7.95),
('WI', 0.05, 7.95),
('WY', 0.04, 7.95),
('DC', 0.0575, 7.95),
('PR', 0.105, 7.95);



INSERT INTO users (userId, userName, PASSWORD, stateCode) VALUES
(NULL, 'admin', SHA1('secret'), 'CA'),
(NULL, 'tom.riddle', SHA1('tom.riddle'), 'AL'),
(NULL, 'ludo.bagman', SHA1('ludo.bagman'), 'AZ'),
(NULL, 'fleur.delacour', SHA1('fleur.delacour'), 'CO'),
(NULL, 'lee.jordan', SHA1('lee.jordan'), 'PR'),
(NULL, 'gilderoy.lockhart', SHA1('gilderoy.lockhart'), 'DC'),
(NULL, 'padma.patil', SHA1('padma.patil'), 'NC'),
(NULL, 'kingsley.shacklebolt', SHA1('kingsley.shacklebolt'), 'NY'),
(NULL, 'dolores.umbridge', SHA1('dolores.umbridge'), 'KS'),
(NULL, 'oliver.wood', SHA1('oliver.wood'), 'ME'),
(NULL, 'buckbeak', SHA1('buckbeak'), 'IA'),
(NULL, 'draco.malfoy', SHA1('draco.malfoy'), 'IL'),
(NULL, 'neville.longbottom', SHA1('neville.longbottom'), 'CT'),
(NULL, 'rubeus.hagrid', SHA1('rubeus.hagrid'), 'SC'),
(NULL, 'norbert', SHA1('norbert'), 'SD'),
(NULL, 'aurora.sinistra', SHA1('aurora.sinistra'), 'ND'),
(NULL, 'hermione.granger', SHA1('hermione.granger'), 'HI'),
(NULL, 'cedric.diggory', SHA1('cedric.diggory'), 'GA'),
(NULL, 'sirius.black', SHA1('sirius.black'), 'LA'),
(NULL, 'dennis.creevey', SHA1('dennis.creevey'), 'AR'),
(NULL, 'viktor.krum', SHA1('viktor.krum'), 'WY');



INSERT INTO roles (roleId, roleName, roleDescription) VALUES
(NULL, 'Admin', 'Site Administrator'),
(NULL, 'User', 'Normal User');



INSERT INTO product (productId, NAME, description, imageURL, price) VALUES
(NULL, 'Cocktail', "Tom Cruise is electrifying as Brian Flanagan, a young, confident, and ambitious bartender who moves to Jamaica and meets an independent artist.", 'https://images-na.ssl-images-amazon.com/images/I/41SCM1BQDWL._SY445_.jpg', 6.98),
(NULL, 'The Firm', "The Firm is a roller coaster of twists and turns that leave the viewer breathless.", 'https://images-na.ssl-images-amazon.com/images/I/81H%2BUXcj%2B7L._SL1500_.jpg', 7.10),
(NULL, 'Top Gun', "Tom Cruise stars as Maverick, a talented training pilot in an elite U.S. school for fighter pilots. When he stumbles upon some MiG's over the Persian Gulf, and his wingman panics, Maverick cleverly talks him through the situation to safety.", 'https://images-na.ssl-images-amazon.com/images/I/5116B0CZ7ML.jpg', 15.98),
(NULL, 'All The Right Moves', "Stefan 'Stef' Djordjevic (Tom Cruise) is a Serbian-American high school defensive back who is both gifted in sports and academics seeking a college football scholarship to escape the economically depressed small western Pennsylvania town of Ampipe and a dead-end job and life working at the mill like his father and brother Greg.", 'https://images-na.ssl-images-amazon.com/images/I/518VWlUCULL._SY445_.jpg', 9.99),
(NULL, 'The Outsiders', "S.E. Hinton's beloved novel of teens from the wrong side of the tracks, directed by Francis Ford Coppola, featuring Matt Dillon, Tom Cruise, Rob Lowe, Patrick Swayze and other young stars.", 'https://images-na.ssl-images-amazon.com/images/I/516T%2BGI9-xL._SY445_.jpg', 1.97),
(NULL, 'Days of Thunder', "In the fast-paced world of NASCAR, a rivalry brews between rookie hotshot Cole Trickle (Tom Cruise) and veteran racer Rowdy Burns (Michael Rooker).", 'https://images-na.ssl-images-amazon.com/images/I/41R8RH8A47L._SY445_.jpg', 6.99),
(NULL, 'Eyes Wide Shut', "A New York City doctor, who is married to an art curator, pushes himself on a harrowing and dangerous night-long odyssey of sexual and moral discovery after his wife admits that she once almost cheated on him.", 'https://images-na.ssl-images-amazon.com/images/I/418H4P3FXYL._SY445_.jpg', 17.77),
(NULL, 'Jerry Maguire', "When slick sports agent Jerry Maguire (Tom Cruise) has a crisis of conscience, he pens a heartfelt company-wide memo that promptly gets him fired.", 'https://images-na.ssl-images-amazon.com/images/I/51PAZQXDZ1L._SY445_.jpg', 9.50),
(NULL, 'Minority Report', "Based on a story by famed science fiction writer Philip K. Dick, 'Minority Report' is an action-detective thriller set in Washington D.C. in 2054, where police utilize a psychic technology to arrest and convict murderers before they commit their crime.", 'https://images-na.ssl-images-amazon.com/images/I/5178A7V08VL._SY445_.jpg', 20.63),
(NULL, 'Interview with the Vampire', "Born as an 18th-century lord, Louis (Brad Pitt) is now a bicentennial vampire, telling his story to an eager biographer (Christian Slater).", 'https://images-na.ssl-images-amazon.com/images/I/41YMVAE223L._SY445_.jpg', 18.49),
(NULL, 'The Color of Money', 'Former pool hustler "Fast Eddie" Felson (Paul Newman) decides he wants to return to the game by taking a pupil. He meets talented but green Vincent Lauria (Tom Cruise) and proposes a partnership.', 'https://images-na.ssl-images-amazon.com/images/I/51obDFQtSYL._SY445_.jpg', 9.96),
(NULL, 'Collateral', 'A cab driver finds himself the hostage of an engaging contract killer as he makes his rounds from hit to hit during one night in Los Angeles. He must find a way to save both himself and one last victim.', 'https://images-na.ssl-images-amazon.com/images/I/41DGN4HKGXL._SY445_.jpg', 6.69),
(NULL, 'Magnolia', 'A complex, intricately crafted character drama of monumental proportions. Featuring truly an ensemble cast, Magnolia never focuses too little or too much on any single character. Instead, it focuses on similarities, differences, and a great deal of parallels between characters and their lives.', 'https://images-na.ssl-images-amazon.com/images/I/512FN9qt2tL._SY445_.jpg', 12.99),
(NULL, 'A Few Good Men', 'Lt. Daniel Kaffee (Tom Cruise) is a military lawyer defending two U.S. Marines charged with killing a fellow Marine at the Guantanamo Bay Naval Base in Cuba.', 'https://images-na.ssl-images-amazon.com/images/I/412HBJK664L._SY445_.jpg', 5.69),
(NULL, 'Vanilla Sky', 'The story of a young New York City publishing magnate who finds himself on an unexpected roller-coaster ride of romance, comedy, suspicion, love, sex and dreams in a mind-bending search for his soul.', 'https://images-na.ssl-images-amazon.com/images/I/51QWYVYWM0L._SY445_.jpg', 3.41),
(NULL, 'Born on the Fourth of July', 'In the mid 1960s, suburban New York teenager Ron Kovic (Tom Cruise) enlists in the Marines, fulfilling what he sees as his patriotic duty. During his second tour in Vietnam, he accidentally kills a fellow soldier during a retreat and later becomes permanently paralyzed in battle.', 'https://images-na.ssl-images-amazon.com/images/I/51nfOrOLJ7L._SY445_.jpg', 1.40),
(NULL, 'Far and Away', "Joseph (Tom Cruise) and his landlord's daughter, Shannon (Nicole Kidman), travel from Ireland to America in hopes of claiming free land in Oklahoma. The pair get sidetracked in Boston, where Joseph takes up boxing to support himself.", 'https://images-na.ssl-images-amazon.com/images/I/512G5BC78GL._SY445_.jpg', 10.00),
(NULL, 'Legend', 'Darkness (Tim Curry) seeks to create eternal night by destroying the last of the unicorns. Jack (Tom Cruise) and his friends do everything possible to save the world and Princess Lili (Mia Sara) from the hands of Darkness.', 'https://images-na.ssl-images-amazon.com/images/I/41ZtuBmfNcL._SY445_.jpg', 6.38),
(NULL, 'The Last Samurai', "Capt. Nathan Algren (Tom Cruise) is an American military officer hired by the Emperor of Japan to train the country's first army in the art of modern warfare.", 'https://images-na.ssl-images-amazon.com/images/I/5106CWcm9rL._SY445_.jpg', 13.89),
(NULL, 'Mission Impossible', 'When U.S. government operative Ethan Hunt (Tom Cruise) and his mentor, Jim Phelps (Jon Voight), go on a covert assignment that takes a disastrous turn, Jim is killed, and Ethan becomes the prime murder suspect.', 'https://images-na.ssl-images-amazon.com/images/I/419FJXQWAJL._SY445_.jpg', 7.95);



INSERT INTO user_roles (userId, roleId) VALUES
( (SELECT userId FROM users WHERE userName='admin'), (SELECT roleId FROM roles WHERE roleName='Admin') ),
( (SELECT userId FROM users WHERE userName='tom.riddle'), (SELECT roleId FROM roles WHERE roleName='User') ),
( (SELECT userId FROM users WHERE userName='ludo.bagman'), (SELECT roleId FROM roles WHERE roleName='User') ),
( (SELECT userId FROM users WHERE userName='fleur.delacour'), (SELECT roleId FROM roles WHERE roleName='User') ),
( (SELECT userId FROM users WHERE userName='lee.jordan'), (SELECT roleId FROM roles WHERE roleName='User') ),
( (SELECT userId FROM users WHERE userName='gilderoy.lockhart'), (SELECT roleId FROM roles WHERE roleName='User') ),
( (SELECT userId FROM users WHERE userName='padma.patil'), (SELECT roleId FROM roles WHERE roleName='User') ),
( (SELECT userId FROM users WHERE userName='kingsley.shacklebolt'), (SELECT roleId FROM roles WHERE roleName='User') ),
( (SELECT userId FROM users WHERE userName='dolores.umbridge'), (SELECT roleId FROM roles WHERE roleName='User') ),
( (SELECT userId FROM users WHERE userName='oliver.wood'), (SELECT roleId FROM roles WHERE roleName='User') ),
( (SELECT userId FROM users WHERE userName='buckbeak'), (SELECT roleId FROM roles WHERE roleName='User') ),
( (SELECT userId FROM users WHERE userName='draco.malfoy'), (SELECT roleId FROM roles WHERE roleName='User') ),
( (SELECT userId FROM users WHERE userName='neville.longbottom'), (SELECT roleId FROM roles WHERE roleName='User') ),
( (SELECT userId FROM users WHERE userName='rubeus.hagrid'), (SELECT roleId FROM roles WHERE roleName='User') ),
( (SELECT userId FROM users WHERE userName='norbert'), (SELECT roleId FROM roles WHERE roleName='User') ),
( (SELECT userId FROM users WHERE userName='aurora.sinistra'), (SELECT roleId FROM roles WHERE roleName='User') ),
( (SELECT userId FROM users WHERE userName='hermione.granger'), (SELECT roleId FROM roles WHERE roleName='User') ),
( (SELECT userId FROM users WHERE userName='cedric.diggory'), (SELECT roleId FROM roles WHERE roleName='User') ),
( (SELECT userId FROM users WHERE userName='sirius.black'), (SELECT roleId FROM roles WHERE roleName='User') ),
( (SELECT userId FROM users WHERE userName='dennis.creevey'), (SELECT roleId FROM roles WHERE roleName='User') ),
( (SELECT userId FROM users WHERE userName='viktor.krum'), (SELECT roleId FROM roles WHERE roleName='User') );



INSERT INTO TRANSACTION (tranId, subtotal, tax, shipping, userId) VALUES
(NULL, 19.66, 0.79, 7.95, (SELECT userId FROM users WHERE userName='tom.riddle') ),
(NULL, 193.76, 10.85, 7.95, (SELECT userId FROM users WHERE userName='ludo.bagman') ),
(NULL, 34.74, 1.01, 7.95, (SELECT userId FROM users WHERE userName='fleur.delacour') ),
(NULL, 6.38, 0.67, 7.95, (SELECT userId FROM users WHERE userName='lee.jordan') ),
(NULL, 1.4, 0.08, 7.95, (SELECT userId FROM users WHERE userName='gilderoy.lockhart') ),
(NULL, 19.37, 0.92, 7.95, (SELECT userId FROM users WHERE userName='padma.patil') ),
(NULL, 44.4, 1.78, 7.95, (SELECT userId FROM users WHERE userName='kingsley.shacklebolt') ),
(NULL, 5.69, 0.37, 7.95, (SELECT userId FROM users WHERE userName='dolores.umbridge') ),
(NULL, 34.76, 1.91, 7.95, (SELECT userId FROM users WHERE userName='oliver.wood') ),
(NULL, 20.63, 1.24, 7.95, (SELECT userId FROM users WHERE userName='buckbeak') ),
(NULL, 9.07, 0.57, 7.95, (SELECT userId FROM users WHERE userName='draco.malfoy') ),
(NULL, 21.36, 1.36, 7.95, (SELECT userId FROM users WHERE userName='neville.longbottom') ),
(NULL, 28.58, 1.71, 7.95, (SELECT userId FROM users WHERE userName='rubeus.hagrid') ),
(NULL, 6.38, 0.29, 7.95, (SELECT userId FROM users WHERE userName='norbert') ),
(NULL, 37.99, 1.9, 7.95, (SELECT userId FROM users WHERE userName='aurora.sinistra') ),
(NULL, 24.75, 0.99, 7.95, (SELECT userId FROM users WHERE userName='hermione.granger') ),
(NULL, 31.84, 1.27, 7.95, (SELECT userId FROM users WHERE userName='cedric.diggory') ),
(NULL, 16.69, 0.83, 7.95, (SELECT userId FROM users WHERE userName='sirius.black') ),
(NULL, 7.95, 0.52, 7.95, (SELECT userId FROM users WHERE userName='dennis.creevey') ),
(NULL, 13.89, 0.56, 7.95, (SELECT userId FROM users WHERE userName='viktor.krum') );


INSERT INTO transactionDetails (tranId, lineNumber, productId) VALUES
( (SELECT tranId FROM TRANSACTION WHERE userId='tom.riddle'), 1, 3),
( (SELECT tranId FROM TRANSACTION WHERE userId='tom.riddle'), 2, 18),
( (SELECT tranId FROM TRANSACTION WHERE userId='tom.riddle'), 3, 7),
( (SELECT tranId FROM TRANSACTION WHERE userId='ludo.bagman'), 1, 1),
( (SELECT tranId FROM TRANSACTION WHERE userId='ludo.bagman'), 2, 2),
( (SELECT tranId FROM TRANSACTION WHERE userId='ludo.bagman'), 3, 3),
( (SELECT tranId FROM TRANSACTION WHERE userId='ludo.bagman'), 4, 4),
( (SELECT tranId FROM TRANSACTION WHERE userId='ludo.bagman'), 5, 5),
( (SELECT tranId FROM TRANSACTION WHERE userId='ludo.bagman'), 6, 6),
( (SELECT tranId FROM TRANSACTION WHERE userId='ludo.bagman'), 7, 7),
( (SELECT tranId FROM TRANSACTION WHERE userId='ludo.bagman'), 8, 8),
( (SELECT tranId FROM TRANSACTION WHERE userId='ludo.bagman'), 9, 9),
( (SELECT tranId FROM TRANSACTION WHERE userId='ludo.bagman'), 10, 10),
( (SELECT tranId FROM TRANSACTION WHERE userId='ludo.bagman'), 11, 11),
( (SELECT tranId FROM TRANSACTION WHERE userId='ludo.bagman'), 12, 12),
( (SELECT tranId FROM TRANSACTION WHERE userId='ludo.bagman'), 13, 13),
( (SELECT tranId FROM TRANSACTION WHERE userId='ludo.bagman'), 14, 14),
( (SELECT tranId FROM TRANSACTION WHERE userId='ludo.bagman'), 15, 15),
( (SELECT tranId FROM TRANSACTION WHERE userId='ludo.bagman'), 16, 16),
( (SELECT tranId FROM TRANSACTION WHERE userId='ludo.bagman'), 17, 17),
( (SELECT tranId FROM TRANSACTION WHERE userId='ludo.bagman'), 18, 18),
( (SELECT tranId FROM TRANSACTION WHERE userId='ludo.bagman'), 19, 19),
( (SELECT tranId FROM TRANSACTION WHERE userId='ludo.bagman'), 20, 20),
( (SELECT tranId FROM TRANSACTION WHERE userId='fleur.delacour'), 1, 4),
( (SELECT tranId FROM TRANSACTION WHERE userId='fleur.delacour'), 2, 6),
( (SELECT tranId FROM TRANSACTION WHERE userId='fleur.delacour'), 3, 10),
( (SELECT tranId FROM TRANSACTION WHERE userId='fleur.delacour'), 4, 9),
( (SELECT tranId FROM TRANSACTION WHERE userId='lee.jordan'), 1, 3),
( (SELECT tranId FROM TRANSACTION WHERE userId='gilderoy.lockhart'), 1, 16),
( (SELECT tranId FROM TRANSACTION WHERE userId='padma.patil'), 1, 11),
( (SELECT tranId FROM TRANSACTION WHERE userId='padma.patil'), 2, 8),
( (SELECT tranId FROM TRANSACTION WHERE userId='padma.patil'), 3, 1);
