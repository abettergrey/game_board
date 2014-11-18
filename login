<?php
session_start();

displayHTMLHead();

show_login_form();

if(isset($_POST['Submit']))
{
	if(isset($_POST['sign_up']))
	{
		$hostname="localhost";
		$username="root";
		$password="hpfreak01";
		$dbname="game_board";
		$mysqli = new mysqli($hostname, $username, $password, $dbname);
		$stmt = $mysqli->stmt_init();
		if($stmt = $mysqli->prepare("INSERT INTO users (user_name, user_email, user_team) 
				VALUES (?, ?, ?)"))
		{
			$stmt->bind_param('ssi', $_POST['name'], $_POST['email'], $_POST['team']);
			$stmt->execute();
			$stmt->close();
		}
	}
}

function displayHTMLHead()
{
echo '<!DOCTYPE html>
    <html> 
	<head>
	<title>table22.php</title>
	<link rel="stylesheet" 	href="https://maxcdn.bootstrapcdn.com/bootstrap/
	3.2.0/css/bootstrap.min.css">
	<link rel="stylesheet" 	href="https://maxcdn.bootstrapcdn.com/bootstrap/
	3.2.0/css/bootstrap-theme.min.css">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/
	3.2.0/js/bootstrap.min.js">
	</script></head><body>';
}

function show_login_form()
{
	echo '<div class="col-md-4">
	<form name="basic" method="POST" action="">
		<table class="table table-condensed" style="border: 1px solid #dddddd; 
		    border-radius: 5px; box-shadow: 2px 2px 10px;">
			<tr><td colspan="2" style="text-align: center; border-radius: 5px; 
			    color: white; background-color:#333333;">
			<h2>Login/Signup</h2></td></tr>
			<tr><td>Email: </td><td><input type="edit" name="email" value="" 
			size="20"></td></tr>
			<tr><td>User Name: </td><td><input type="edit" name="name" value="" 
			size="20"></td></tr>
			<tr><td>Team: </td><td><input type="edit" name="team" value="" 
			size="20"></td></tr>
			<tr><td>Password: </td><td><input type="password" name="pasword" value="" 
			size="20"></td></tr>
			<tr><td>Check if this is a signup</td><td><input type="checkbox" name="sign_up" value="sign"></td></tr>
			<tr><td><input type="submit" name="Submit" 
			class="btn btn-success" value="Submit"></td>';
}