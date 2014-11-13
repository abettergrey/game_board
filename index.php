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
function checkConnect($mysqli)
{
	if($mysqli->connect_errno) 
	{
		die('You done fucked up, and here is why ' . $mysqli->connect_error);
		exit();
	}
}