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
