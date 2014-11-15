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
	$userSelection 		= 0;
	$firstCall 			= 1; // first time program is called

	$userSelection = $firstCall; // assumes first call unless button was clicked
	switch($userSelection):
		case $firstCall:
			displayHTMLHead();
		    showList($mysqli, $msg);
		    break;
	endswitch;

}

function checkConnect($mysqli)
{
	if($mysqli->connect_errno) 
	{
		die('You done messed up up, and here is why ' . $mysqli->connect_error);
		exit();
	}
}

function createTables($mysqli)
{
	//Creating the event table
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
		if($stmt = $mysqli->prepare($sql))
        {
            $stmt->execute();
        }
	}
	//*****************************************************************

	//creating user table
	if($result = $mysqli->query("SELECT id FROM users LIMIT 1"))
	{
		$row = $result->fetch_object();
		$id_u = $row->id;
		$result->close();
	}
	if(!$id_u)
	{
		$sql = "CREATE TABLE users
				(id INT NOT NULL AUTO_INCREMENT, PRIMARY KEY( id ),
				user_name VARCHAR(30),
				user_email VARCHAR(30),
				user_team INT,
				FOREIGN KEY (user_team) REFERENCES teams (id))";
		if($stmt = $mysqli->prepare($sql))
        {
            $stmt->execute();
        }
	}
	//*******************************************************************

	//creating team table
	if($result = $mysqli->query("SELECT id FROM team LIMIT 1"))
	{
		$row = $result->fetch_object();
		$id_t = $row->id;
		$result->close();
	}
	if(!$id_t)
	{
		$sql = "CREATE TABLE team
				(id INT NOT NULL AUTO_INCREMENT, PRIMARY KEY( id ),
				team_name VARCHAR(30),
				team_wins INT,
				team_loses INT)";
		if($stmt = $mysqli->prepare($sql))
        {
            $stmt->execute();
        }
	}
}

function displayHTMLHead()
{
echo '<!DOCTYPE html>
    <html> 
	<head>
	<title>Game Board</title>
	<link rel="stylesheet" 	href="https://maxcdn.bootstrapcdn.com/bootstrap/
	3.2.0/css/bootstrap.min.css">
	<link rel="stylesheet" 	href="https://maxcdn.bootstrapcdn.com/bootstrap/
	3.2.0/css/bootstrap-theme.min.css">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/
	3.2.0/js/bootstrap.min.js">
	</script></head><body>';
}

function showList($mysqli, $msg) 
{
    // display html table column headings
	echo 	'<div class="col-md-12">
			<form action="dbHandler.php" method="POST">
			<table class="table table-condensed" 
			style="border: 1px solid #dddddd; border-radius: 5px; 
			box-shadow: 2px 2px 10px;">
			<tr><td colspan="10" style="text-align: center; border-radius: 5px; 
			color: white; background-color:#333333;">
			<h2 style="color: white;">Game Board Events</h2>
			</td></tr><tr style="font-weight:800; font-size:20px;">
			<td>id</td><td>Team 1 Name</td><td>Team 1 Score</td>
			<td>Team 2 Name</td><td>Team 2 Score</td><td>Game Name</td>
			<td>Game Type</td><td>Winner</td><td>Closed</td><td></td></tr>';

	// get count of records in mysql table
	$countresult = $mysqli->query("SELECT COUNT(*) FROM events");
	$countfetch  = $countresult->fetch_row();
	$countvalue  = $countfetch[0];
	$countresult->close();

	// if records > 0 in mysql table, then populate html table, 
	// else display "no records" message
	if( $countvalue > 0 )
	{
			populateTable($mysqli); // populate html table, from mysql table
	}
	else
	{
			echo '<br><p>No records in database table</p><br>';
	}

	echo    '</table>
			<input type="hidden" id="hid" name="hid" value="">
			<input type="hidden" id="uid" name="uid" value="">
			</form></div>';
	footer();
}

function populateTable($mysqli)
{
	if($result = $mysqli->query("SELECT * FROM events"))
	{
		while($row = $result->fetch_row())
		{
			$output = '<tr><td>' . $row[0] > '</td><td>' . $row[1] . '</td><td>'
				. $row[3] . '</td><td>' . $row[2] . '</td><td>' . $row[4]
				. '</td><td>' . $row[5] . '</td><td>' . $row[6]  
				. '</td><td>' . $row[6] . '</td>'
				. '<td><input style="margin-left: 10px;" type="submit"
				name="update" value="Update" onClick="setUid(' . $row[0] . ');" />';

			echo $output;
		}
	}
}

function footer()
{
	echo "<script>
			function setHid(num)
			{
				document.getElementById('hid').value = num;
		    }
		    function setUid(num)
			{
				document.getElementById('uid').value = num;
		    }
		 </script>";
}
