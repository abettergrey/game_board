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


$hostname="localhost";
$username="root";
$password="hpfreak01";
$dbname="game_board";
$user_email = $_SESSION['user_email'];
$user_id = $_SESSION['id'];
$user_team = $_SESSION['user_team'];
$mysqli = new mysqli($hostname, $username, $password, $dbname);

if(!$user_id)
{
	header( 'Location: http://107.178.221.68/game_board/login.php' );
}
checkConnect($mysqli);

//This will only fire if the database is connected
if($mysqli)
{
	createTables($mysqli);
	$userSelection 		= 0;
	$firstCall 			= 1; // first time program is called
	$insertTeam			= 2;
	$insertTeam_done	= 3;
	$join_team			= 4;
	$join_team_complete = 5;
	$make_event			= 6;
	$make_event_complete= 7;
	$deleteSelected 	= 8;

	$userSelection = $firstCall; // assumes first call unless button was clicked
	
	if( isset( $_POST['insert_team'] ) ) $userSelection = $insertTeam;
	if( isset( $_POST['insertTeam_done'] ) ) $userSelection = $insertTeam_done;
	if( isset( $_POST['join_team'])) $userSelection = $join_team;
	if( isset( $_POST['join_team_complete'])) $userSelection = $join_team_complete;
	if( isset( $_POST['make_event'])) $userSelection = $make_event;
	if( isset( $_POST['add_event_complete'])) $userSelection = $make_event_complete;
	if( isset( $_POST['deleteSelected'])) $userSelection = $deleteSelected;
	
	$team_name = $_POST['team_name'];
	$enemy_team_id = $_POST['event_e_team'];
	$game = $_POST['event_game'];
	$game_type = $_POST['event_game_type'];
	switch($userSelection):
		case $firstCall:
			displayHTMLHead();
		    showList($mysqli, $msg);
		    break;
		case $insertTeam: 
			displayHTMLHead();
			display_team_insert();
			break;
		case $insertTeam_done:
			create_team($mysqli);
			displayHTMLHead();
			header( 'Location: http://107.178.221.68/game_board/index.php' );
			break;
		case $join_team:
			displayHTMLHead();
			display_join_team($mysqli);
			break;
		case $join_team_complete:
			joint_team($mysqli);
			displayHTMLHead();
			header( 'Location: http://107.178.221.68/game_board/index.php' );
			break;
		case $make_event:
			displayHTMLHead();
			display_event_create($mysqli);
			break;
		case $make_event_complete:
			create_event($mysqli);
			displayHTMLHead();
			header( 'Location: http://107.178.221.68/game_board/index.php' );
			break;
		case $deleteSelected:
			displayHTMLHead();
			delete_function($mysqli);
			break;
	endswitch;

}

function delete_function($mysqli)
{
	$index = $_POST['hid'];  // "hid" is id of db record to be deleted
    $stmt = $mysqli->stmt_init();
    if($stmt = $mysqli->prepare("DELETE FROM events WHERE id=?"))
    {
        // Bind parameters. Types: s=string, i=integer, d=double, etc.
		// protects against sql injections
        $stmt->bind_param('i', $index);
        $stmt->execute();
        $stmt->close();
    }
}

function create_event($mysqli)
{
	global $user_team, $enemy_team_id, $game, $game_type;
    $stmt = $mysqli->stmt_init();
    if($stmt = $mysqli->prepare("INSERT INTO events (team_one_id, team_two_id, game_name, game_type ) VALUES (?,?,?,?)"))
    {	
        // Bind parameters. Types: s=string, i=integer, d=double, etc.
		// protects against sql injections
        $stmt->bind_param('iiss', $user_team, $enemy_team_id, $game, $game_type);
        $stmt->execute();
        $stmt->close();
    }
}

function display_event_create($mysqli)
{
	global $user_team;
	echo '<div class="col-md-4">
	<form name="basic" method="POST" action="index.php" 
	    onSubmit="return validate();">
		<table class="table table-condensed" style="border: 1px solid #dddddd; 
		    border-radius: 5px; box-shadow: 2px 2px 10px;">
			<tr><td colspan="2" style="text-align: center; border-radius: 5px; 
			    color: white; background-color:#333333;">
			<h2>Event Maker</h2></td></tr>';
	if($user_team)
	{	
		echo 	'<tr><td>Enemy Team: </td><td><select name="event_e_team">';
		echo populate_teams();
		echo 	'</select></td></tr>
				<tr><td>Game Being Played: </td><td><input type="edit" name="event_game" value="" 
				size="20"></td></tr>
				<tr><td>Game Type: </td><td><input type="edit" name="event_game_type" value="" 
				size="20"></td></tr>
				<tr><td><input type="submit" name="add_event_complete" 
				class="btn btn-success" value="Add Event"></td>
				<td style="text-align: right;"><input type="reset" 
				class="btn btn-danger" value="Reset Form"></td></tr>
			</table></form></div>';
	}
	else
	{
		echo '<tr><td>You must join a team first<td></tr>
		<tr><td><input type="submit" name="" 
		class="btn btn-success" value="Back to Events"></td>
		</table></form></div>';
	}
}

function populate_teams()
{	
	global $mysqli;
	$result = $mysqli->query("select * from team");
	while($row = $result->fetch_row())
	{
		$output = '<option value ="' . $row[0] . '"> ' . $row[1] . '</option>';
			
		echo $output;
	}
	$result->close();
}

function joint_team($mysqli)
{	
	$index = $_POST['uid'];
	global $user_id;
    $stmt = $mysqli->stmt_init();
    if($stmt = $mysqli->prepare("UPDATE users SET user_team=? where id = $user_id"))
    {	
        // Bind parameters. Types: s=string, i=integer, d=double, etc.
		// protects against sql injections
        $stmt->bind_param('i', $index);
        $stmt->execute();
        $stmt->close();
    }
    $_SESSION['user_team'] = $index;
}

function display_join_team($mysqli)
{
	echo'<div class="col-md-12">
			<form action="index.php" method="POST">
			<table class="table table-condensed" 
			style="border: 1px solid #dddddd; border-radius: 5px; 
			box-shadow: 2px 2px 10px;">
			<tr><td colspan="10" style="text-align: center; border-radius: 5px; 
			color: white; background-color:#333333;">
			<h2 style="color: white;">Join a Team</h2>
			</td></tr><tr style="font-weight:800; font-size:20px;">
			<td>id</td><td>Team Name</td><td>Wins</td><td>Losses</td></tr>';

	// get count of records in mysql table
	$countresult = $mysqli->query("SELECT COUNT(*) FROM team");
	$countfetch  = $countresult->fetch_row();
	$countvalue  = $countfetch[0];
	$countresult->close();

	// if records > 0 in mysql table, then populate html table, 
	// else display "no records" message
	if( $countvalue > 0 )
	{
			if($result = $mysqli->query("SELECT * FROM team"))
			{	
				while($row = $result->fetch_row())
				{
					$output = '<tr><td>' . $row[0] . '</td><td>' . $row[1] . '</td><td>'
					. $row[2] . '</td><td>' . $row[3] . '</td><td><input name="join_team_complete" type="submit" 
					class="btn btn-danger" value="Join" onclick="setUid(' . 
					$row[0] .')" />';

				echo $output;
				}
			}
			$result->close();
	}
	else
	{
			echo '<br><p>No records in database table</p><br>';
	}
	echo '<input type="hidden" id="uid" name="uid" value="">';
	footer();
}

function create_team($mysqli)
{
	global $team_name;
    $stmt = $mysqli->stmt_init();
    if($stmt = $mysqli->prepare("INSERT INTO team (team_name) VALUES (?)"))
    {	
        // Bind parameters. Types: s=string, i=integer, d=double, etc.
		// protects against sql injections
        $stmt->bind_param('s', $team_name);
        $stmt->execute();
        $stmt->close();
    }
}

function display_team_insert()
{
	echo '<div class="col-md-4">
	<form name="basic" method="POST" action="index.php" 
	    onSubmit="return validate();">
		<table class="table table-condensed" style="border: 1px solid #dddddd; 
		    border-radius: 5px; box-shadow: 2px 2px 10px;">
			<tr><td colspan="2" style="text-align: center; border-radius: 5px; 
			    color: white; background-color:#333333;">
			<h2>Team Maker</h2></td></tr>
			<tr><td>Team Name: </td><td><input type="edit" name="team_name" value="" 
			size="20"></td></tr>
			<tr><td><input type="submit" name="insertTeam_done" 
			class="btn btn-success" value="Add Team"></td>
			<td style="text-align: right;"><input type="reset" 
			class="btn btn-danger" value="Reset Form"></td></tr>
		</table></form></div>';
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
				team_two_score INT, 
				game_name VARCHAR(30),
				game_type VARCHAR(30),
				winning_team INT,
				closed BOOLEAN,
				flag BOOLEAN,
				FOREIGN KEY (team_one_id) REFERENCES team (id),
				FOREIGN KEY (team_two_id) REFERENCES team (id),
				FOREIGN KEY (winning_team) REFERENCES team (id))";
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
				FOREIGN KEY (user_team) REFERENCES team (id))";
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
			<form action="index.php" method="POST">
			<table class="table table-condensed" 
			style="border: 1px solid #dddddd; border-radius: 5px; 
			box-shadow: 2px 2px 10px;">
			<tr><td colspan="11" style="text-align: center; border-radius: 5px; 
			color: white; background-color:#333333;">
			<h2 style="color: white;">Game Board Events</h2>
			</td></tr><tr style="font-weight:800; font-size:20px;">
			<td>id</td><td>Team 1 Name</td><td>Team 1 Score</td>
			<td>Team 2 Name</td><td>Team 2 Score</td><td>Game Name</td>
			<td>Game Type</td><td>Winner</td><td></td><td></td></tr>';

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
			<input type="submit" name="insert_team" value="Make a team" 
			class="btn btn-primary"">
			<input type="submit" name="join_team" value="Join a Team" 
			class="btn btn-primary"">
			<input type="submit" name="make_event" value="Make a Event" 
			class="btn btn-danger"">
			</form></div>';
	footer();
}

function populateTable($mysqli)
{	
	global $user_team;
	if($result = $mysqli->query("SELECT * FROM events"))
	{
		while($row = $result->fetch_row())
		{	
			$is_my_team1 = $row[1];
			$is_my_team2 = $row[2];
			$output = '<tr><td>' . $row[0] . '</td><td>' . convert_int_to_name($row[1]) . '</td><td>'
				. $row[3] . '</td><td>' . convert_int_to_name($row[2]) . '</td><td>' . $row[4]
				. '</td><td>' . $row[5] . '</td><td>' . $row[6]  
				. '</td><td>' . $row[6] . '</td>';

			if($user_team === $is_my_team1 || $user_team === $is_my_team1)
			{
				$output .= '<td><input name="deleteSelected" type="submit" 
					class="btn btn-danger" value="Delete" onclick="setHid(' . 
					$row[0] .')" /> <input style="margin-left: 10px;" type="submit" 
					name="updateSelected" class="btn btn-primary" value="Update" 
					onclick="setUid(' . $row[0] . ');" />';
			}
			else
			{
				$output .= '<td></td>';
			}
			echo $output;
		}
	}
	$result->close();
}

function convert_int_to_name($num)
{	
	global $mysqli;
	if($result = $mysqli->query("SELECT * FROM team WHERE id = $num"))
	{
		$row = $result->fetch_row();
		return $row[1];
	}
	else{echo"nope";die;}
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
