drop table if exists Vote;
drop table if exists Statement;
drop table if exists Player;
drop table if exists Game;

create table Game
(
    gameId BINARY(16) not null,
    gameCode VARCHAR(6) not null unique,
    gameCreated DATETIME(6) not null,
    gameActivity DATETIME(6) not null,
    gameCurrentPlayerId BINARY(16) null,
    gameCurrentStatementId BINARY(16) null,
    gameCurrentState TINYINT not null,
    gameTeamOneScore TINYINT not null,
    gameTeamTwoScore TINYINT not null,
    primary key (gameId)
);

create table Player
(
    playerId BINARY(16) not null,
    playerGameId BINARY(16) null,
    playerName VARCHAR(32) not null,
    playerTeamNumber TINYINT not null,
    playerPlayed BOOLEAN not null default false,
    playerLastModified DATETIME(6) not null,
    primary key (playerId),
    foreign key (playerGameId) references Game(gameId)
);

create table Statement(
    statementId BINARY(16) not null,
    statementText VARCHAR(280) not null,
    statementTrue BOOLEAN not null ,
    statementUsed BOOLEAN not null default false,
    statementPlayerId BINARY(16) not null,
    primary key (statementId),
    foreign key (statementPlayerId) references Player(playerId)
);

create table Vote (
    voteId BINARY(16) not null,
    voteStatementId BINARY(16) not null,
    votePlayerId BINARY(16) not null,
    voteTrue BOOLEAN not null,
    primary key (voteId),
    foreign key (voteStatementId) references Statement(statementId),
    foreign key (votePlayerId) references Player(playerId)
);