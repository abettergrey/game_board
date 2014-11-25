<?php

session_start();

$user_email = $_POST["user_email"];
$user_password = $_POST["user_password"];

if(!$user_id || !$user_email || !$user_password)
{
	show_login();
}
else
{
	//go_for_loginOP
}

function show_login()
{
	echo '<!DOCTYPE html>
    <html> 
	<head>
	<title>Login</title>
	<link rel="stylesheet" 	href="https://maxcdn.bootstrapcdn.com/bootstrap/
	3.2.0/css/bootstrap.min.css">
	<link rel="stylesheet" 	href="https://maxcdn.bootstrapcdn.com/bootstrap/
	3.2.0/css/bootstrap-theme.min.css">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/
	3.2.0/js/bootstrap.min.js">
	</script></head><body>';
	
	echo '	<br>
			<div class="col-md-4 span6 offset3">
			<form name="basic" method="POST" action="login.php">
				<table class="table table-condensed" 
					style="border: 1px solid #dddddd; 
					border-radius: 5px; box-shadow: 2px 2px 10px;">
					<tr><td colspan="2" style="text-align: center; 
					border-radius: 5px; color: white; 
					background-color:#333333;">
					<h2>Login Form</h2></td></tr>
					<tr><td>Email: </td><td><input type="edit"
					name="user_email" value="" size="20"</td></tr>
					<tr><td>Password: </td><td><input type="password"
					name="user_password" value="" size="20"</td></tr>
					<tr><td><input type="submit" name="login_try"
					class="btn btn-success" value="Login"></td>
					</table>';
	
}