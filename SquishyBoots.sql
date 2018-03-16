drop table Completes;
drop table Has;
drop table Carries;
drop table Enemy;
drop table Hero;
drop table Quest;
drop table Characters;
drop table Player;
drop table Location;
drop table Item;



CREATE TABLE Player (
	Username 	varchar(20) UNIQUE,
	Email		varchar(20),
	Player_ID	int,
	PRIMARY KEY(Player_ID)
);

grant select on Player to public;

CREATE TABLE Location ( 
	Loc_ID		int,
	L_Name 		varchar(20),
	City		varchar(20),
	Island		varchar(20),
	PRIMARY KEY(Loc_ID)
);

grant select on Location to public;

/* Cant add the ON UPDATE CASCADE */
CREATE TABLE Quest ( 
	Q_ID		int,
	q_name		varchar(20), 
	Loc_id		int not NULL,
	Difficulty	int,
	PRIMARY KEY(Q_ID),
	FOREIGN KEY(Loc_id) REFERENCES Location ON DELETE CASCADE
);

grant select on Quest to public;

CREATE TABLE Characters (
	HP				int,
	MP				int,
	Char_Name 		varchar(20),
	Char_Level		int,
	Char_ID			int,
	PRIMARY KEY(Char_id)
);

grant select on Characters to public;

CREATE TABLE Enemy (
	Enemy_Level		int,
	Enemy_Exp		int,
	Char_ID			int,
	PRIMARY KEY(Char_ID),
	FOREIGN KEY(Char_ID) REFERENCES Characters
);

grant select on Enemy to public;

CREATE TABLE Hero (
	Hero_Class					varchar(20),
	Job							varchar(20),
	Quests_Completed			int,
	Player_ID					int,
	Char_ID						int,
	PRIMARY KEY(Char_ID),
	FOREIGN KEY(Char_ID) REFERENCES Characters,
	FOREIGN KEY(Player_ID) REFERENCES Player
);

grant select on Hero to public;

CREATE TABLE Item(
	Item_ID			int,
	I_Level			int,
	I_Type			varchar(20),
	I_Name			varchar(20),
	I_Value			int,
	PRIMARY KEY(Item_ID)
);

grant select on Item to public;

CREATE TABLE Carries(
	Char_id		int,
	Item_id		int,
	PRIMARY KEY(Char_id, Item_id),
	FOREIGN KEY(Char_id) REFERENCES Hero,
	FOREIGN KEY(Item_id) REFERENCES Item
);

grant select on Carries to public;

CREATE TABLE Completes(
	Char_id		int,
	Q_id		int,
	PRIMARY KEY(Char_id, Q_id),
	FOREIGN KEY(Char_id) REFERENCES Hero,
	FOREIGN KEY(Q_id) REFERENCES Quest
);

grant select on Completes to public;

CREATE TABLE Has(
	Enemy_id	int,
	Q_id	 	int,
	PRIMARY KEY(Enemy_id, Q_id),
	FOREIGN KEY(Enemy_id) REFERENCES Enemy,
	FOREIGN KEY(Q_id) REFERENCES Quest
);

grant select on Has to public;

insert into Player
values('Antipater', 'jun@email.com', '1')

insert into Player
values('Philippina', 'karen@email.com', '2')

insert into Player
values('Xoel', 'silas@email.com', '3')

insert into Player
values('Eadburga', 'marijke@email.com', '4')

insert into Player
values('Jockie', 'matthias@email.com', '5')

insert into Location
values(1, 'Road of Regrets 1', 'Temple of Time', 'Ossyria');

insert into Location
values(2, 'Time Lane: Three Doors', 'Temple of Time', 'Ossyria');

insert into Location
values(3, 'Singing Mushroom Forest: Windflower Forest', 'Henesys', 'Victoria Island');

insert into Location
values(4, 'Construction Site', 'Kerning City', 'Victoria Island');

insert into Location
values(5, 'Snail Park', 'Maple Tree Hill', 'Maple Island');

insert into Quest 
values(1, '(Wanted) Green Mushrooms', 3, 0);

insert into Quest 
values(2, 'Path to the Past', 2, 10);

insert into Quest 
values(3, 'Keeny’s Research on Neo Huroid!', 4, 5);

insert into Quest 
values(4, 'Keeny’s Research on D.Roid!!', 5, 6);

insert into Quest 
values(5, 'Runaway Brother', 3, 3);

insert into Characters
values(2, 'Jake', 195, 75, 4);

insert into Characters
values(4, 'David', 270, 20, 6);

insert into Characters
values(3, 'Sally', 295, 10, 12);

insert into Characters
values(1, 'Wolf', 200, 80, 10);

insert into Characters
values(5, 'Tino', 120, 250, 8);

insert into Enemy
values(1, 0, 1);

insert into Enemy
values(6, 6, 4);

insert into Enemy
values(7, 12, 7);

insert into Enemy
values(8, 17, 10);

insert into Enemy
values(5, 3, 1);

insert into Hero
values(9, 2, 'Luminous', 4117);

insert into Hero
values(2, 1, 'Kinesis', 1);

insert into Hero
values(3, 5, 'Zero', 5490);

insert into Hero
values(4, 3, 'Mercedes', 588);

insert into Hero
values(10, 4, 'Mechanic', 711);

insert into Item
values(1, 'Mithril Sharp Helm', 'hat', 22, 100);

insert into Item
values(2, 'Squishy Shoes', 'shoes', 30, 200);

insert into Item
values(3, 'Adamantium Igor', 'claws', 20, 150);

insert into Item
values(4, 'Reverse Blindness', 'gun', 120, 1000);

insert into Item
values(5, 'Bamboo Spears', 'spear', 20, 90);

insert into Carries
values(3, 2);

insert into Carries
values(2, 2);

insert into Carries
values(4, 4);

insert into Carries
values(2, 1);

insert into Carries
values(3, 5);

insert into Completes
values(2, 1);

insert into Completes
values(3, 2);

insert into Completes
values(4, 3);

insert into Completes
values(2, 4);

insert into Completes
values(3, 5);

insert into Has
values(1, '(Wanted) Green Mushrroms');

insert into Has
values(5, 'Path to the Past');

insert into Has
values(1, 'Keeny’s Research on Neo Huroid!');

insert into Has
values(5, 'Keeny’s Research on D.Roid!!');

insert into Has
values(5, 'Runaway Brother');

