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
    Username     varchar(80) UNIQUE,
    Email        varchar(80),
    Player_ID    int,
    PRIMARY KEY(Player_ID),
	CONSTRAINT CHK_PID CHECK (Player_ID > 0)
);

grant select on Player to public;

CREATE TABLE Location ( 
    Loc_ID        int,
    L_Name         varchar(80),
    City        varchar(80),
    Island        varchar(80),
    PRIMARY KEY(Loc_ID),
	CONSTRAINT CHK_LID CHECK (Loc_ID > 0)
);

grant select on Location to public;

/* Cant add the ON UPDATE CASCADE */
CREATE TABLE Quest ( 
    Q_ID        int,
    q_name        varchar(80), 
    Loc_id        int not NULL,
    Difficulty    int,
    PRIMARY KEY(Q_ID),
	CONSTRAINT CHK_QST CHECK (Q_ID > 0 AND Loc_id > 0),
    FOREIGN KEY(Loc_id) REFERENCES Location ON DELETE CASCADE
);

grant select on Quest to public;

CREATE TABLE Characters (
    HP                int,
    MP                int,
    Char_Name         varchar(80),
    Char_Level        int,
    Char_ID            int,
    PRIMARY KEY(Char_ID),
	CONSTRAINT CHK_CHT CHECK (Char_ID > 0 AND Char_Level > 0)
);

grant select on Characters to public;

CREATE TABLE Enemy (
    Enemy_Exp        int,
    Char_ID            int,
    PRIMARY KEY(Char_ID),
	CONSTRAINT CHK_EMY CHECK (Char_ID > 0 AND Enemy_Exp >= 0),
    FOREIGN KEY(Char_ID) REFERENCES Characters
);

grant select on Enemy to public;

CREATE TABLE Hero (
    Hero_Class                    varchar(80),
    Job                            varchar(80),
    Quests_Completed            int,
    Player_ID                    int,
    Char_ID                        int,
    PRIMARY KEY(Char_ID),
	CONSTRAINT CHK_HRO CHECK (Char_ID > 0 AND Player_ID > 0 AND Quests_Completed > 0),
    FOREIGN KEY(Char_ID) REFERENCES Characters ON DELETE CASCADE,
    FOREIGN KEY(Player_ID) REFERENCES Player ON DELETE CASCADE
);

grant select on Hero to public;

CREATE TABLE Item(
    Item_ID            int,
    I_Level            int,
    I_Type            varchar(80),
    I_Name            varchar(80),
    I_Value            int,
    PRIMARY KEY(Item_ID),
	CONSTRAINT CHK_ITM CHECK (Item_ID > 0 AND I_Level > 0 AND I_Value >= 0)
);

grant select on Item to public;

CREATE TABLE Carries(
    Char_id        int,
    Item_id        int,
    PRIMARY KEY(Char_id, Item_id),
	CONSTRAINT CHK_CAR CHECK (Char_id > 0 AND Item_id > 0),
    FOREIGN KEY(Char_id) REFERENCES Hero ON DELETE CASCADE,
    FOREIGN KEY(Item_id) REFERENCES Item
);

grant select on Carries to public;

CREATE TABLE Completes(
    Char_id        int,
    Q_id        int,
    PRIMARY KEY(Char_id, Q_id),
	CONSTRAINT CHK_COM CHECK (Char_id > 0 AND Q_id > 0),
    FOREIGN KEY(Char_id) REFERENCES Hero ON DELETE CASCADE,
    FOREIGN KEY(Q_id) REFERENCES Quest
);

grant select on Completes to public;

CREATE TABLE Has(
    Enemy_id    int,
    Q_id         int,
    PRIMARY KEY(Enemy_id, Q_id),
	CONSTRAINT CHK_HAS CHECK (Enemy_id > 0 AND Q_id > 0),
    FOREIGN KEY(Enemy_id) REFERENCES Enemy,
    FOREIGN KEY(Q_id) REFERENCES Quest
);

grant select on Has to public;

insert into Player
values('Antipater', 'jun@email.com', 1);

insert into Player
values('Philippina', 'karen@email.com', 2);

insert into Player
values('Xoel', 'silas@email.com', 3);

insert into Player
values('Eadburga', 'marijke@email.com', 4);

insert into Player
values('Jockie', 'matthias@email.com', 5);

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
values(195, 75, 'Jake', 4, 1);

insert into Characters
values(270, 20, 'David', 6, 2);

insert into Characters
values(295, 10, 'Sally', 12, 3);

insert into Characters
values(295, 10, 'Daisy', 12, 4);

insert into Characters
values(295, 10, 'Lily', 12, 5);

insert into Characters
values(300, 12, 'Peach', 55, 11);

insert into Characters
values(295, 11, 'Paul', 100, 12);

insert into Characters
values(200, 80,'Wolf', 1, 6);

insert into Characters
values(120, 250, 'Tino', 8, 7);

insert into Characters
values(15, 0, 'Snail', 1, 8);

insert into Characters
values(35, 0, 'Stump', 4, 9);

insert into Characters
values(80, 10, 'Slime', 7, 10);

insert into Enemy
values(1, 6);

insert into Enemy
values(6, 7);

insert into Enemy
values(12, 8);

insert into Enemy
values(13, 9);

insert into Enemy
values(17, 10);

insert into Hero
values('Magician', 'Luminous', 4117, 1, 5);

insert into Hero
values('Magician', 'Kinesis', 1, 2, 4);

insert into Hero
values('Warrior', 'Zero', 5490, 3, 3);

insert into Hero
values('Bowman', 'Mercedes', 588, 4, 2);

insert into Hero
values('Pirate', 'Mechanic', 711, 5, 1);

insert into Hero
values('Bowman', 'Mercedes', 1001, 5, 11);

insert into Hero
values('Warrio', 'Zero', 1002, 3, 12);

insert into Item
values(1, 22, 'hat', 'Mithril Sharp Helm', 100);

insert into Item
values(2, 30, 'shoes', 'Squishy Shoes', 200);

insert into Item
values(3, 20, 'claws', 'Adamantium Igor', 150);

insert into Item
values(4, 120, 'gun', 'Reverse Blindness', 1000);

insert into Item
values(5, 20, 'spear', 'Bamboo Spears', 90);

insert into Carries
values(3, 2);

insert into Carries
values(2, 3);

insert into Carries
values(4, 4);

insert into Carries
values(1, 1);

insert into Carries
values(5, 5);

insert into Carries
values(11, 5);

insert into Carries
values(12, 2);

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

insert into Completes
values(2, 2);

insert into Completes
values(2, 3);

insert into Completes
values(2, 5);

insert into Has
values(6, 1);

insert into Has
values(7, 2);

insert into Has
values(8, 3);

insert into Has
values(9, 4);

insert into Has
values(10, 5);

