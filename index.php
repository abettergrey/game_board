<?php
session_start();
//GAME BOARD
//Author: Brett Yeager
//Desc.: This application will allow a user to log in/
//	create an acount. This account can then join/leave/create a team.
//	The teams will then create events: multiplayer matches.
//	Once the match is complete, a team will enter stats about the
//	match and the other team will have to accept. If the other team
//	does not accept than the event will be flaged for review by
// an admin.

echo 'In the beginning the Universe was created.
This has made a lot of people very angry and been widely regarded as a bad move.';

$hostname="localhost";
$username="root";
$password="hpfreak01";
$dbname="game_board";

$mysqli = new mysqli($hostname, $username, $password, $dbname);
checkConnect($mysqli);
echo 'The database is connected';

//This will only fire if the database is connected
if($mysqli)
{
	createTables($mysqli);
}
function checkConnect($mysqli)
{
	if($mysqli->connect_errno) 
	{
		die('You done fucked up, and here is why ' . $mysqli->connect_error);
		exit();
	}
}

function createTables($mysqli)
{
	if($result = $mysqli->query("SELECT id FROM events LIMIT 1"))
	{
		$row = $result->fetch_object();
		$id = $row->id;
		$result->close();
	}
	if(!$id)
	{
		$sql = "CREATE TABLE events 
				(id INT NOT NULL AUTO_INCREMENT, PRIMARY KEY( id ),
				team_one_id INT,
				team_two_id INT,
				team_one_score INT,
				team_two_socre INT, 
				game_name VARCHAR(30),
				game_type VARCHAR(30),
				winning_team INT,
				closed BOOLEAN,
				flag BOOLEAN,
				FOREIGN KEY (team_one_id) REFERENCES teams (id),
				FOREIGN KEY (team_two_id) REFERENCES teams (id),
				FOREIGN KEY (winning_team) REFERENCES teams (id))";
				
	}
}