<?php

session_start();

$user_email = $_POST["user_email"];
$user_password = $_POST["user_password"];
$user_name = $_POST["user_name"];

$mysqli = new mysqli($hostname, $username, $password, $dbname);

$hostname="localhost";
$username="root";
$password="hpfreak01";
$dbname="game_board";
if(!$user_email || !$user_password)
{
	show_login();
}
else
{
	if( isset( $_POST['login_try'] ) )
	{
		try_login();
	}
	else if( isset( $_POST['sign_up_try'] ))
	{
		sign_up();
	}
}

function try_login()
{
	global $hostname, $username, $password, $dbname, $user_name, $user_password, $mysqli;
	$found = false;
	if($result = $mysqli->query("SELECT * FROM $usertable"))
	{
		while($row = $result->fetch_row())
		{
			if($user_email === $row[2] && $user_password === $row[4])
			{
				$_SESSION['user_email'] = $row[1];
				$_SESSION['id'] = $row[0];
				header( 'Location: http://107.178.221.68/game_board/index.php' );
				$found = true;
			}
		}
	}
	if(!$found)
	{
		echo 'Login failed!';
	}
}

function sign_up()
{
	global $hostname, $username, $password, $dbname, $user_name, $user_password, $user_name, $mysqli;
	
	$stmt = $mysqli->stmt_init();
    if($stmt = $mysqli->prepare("INSERT INTO users (user_name, user_email, user_password) VALUES (?, ?, ?)"))
    {
        // Bind parameters. Types: s=string, i=integer, d=double, etc.
		// protects against sql injections
        $stmt->bind_param('sss', $user_name, $user_email, $user_password);
        $stmt->execute();
        $stmt->close();
    }
	header( 'Location: http://107.178.221.68/game_board/index.php' );
}

function show_login()
{
	echo '<!DOCTYPE html>
    <html> 
	<head>
	<title>Login</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"</script>
	</script></head><body>';

	
	echo '	<br>
			<div id="accordion" class="panel-group">
    			<div class="panel panel-default">
        			<div class="panel-heading">
            			<h4 class="panel-title">
            			 <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">Login</a>
            			</h4>
        			</div>
        			<div id="collapseOne" class="panel-collapse in">
            			<div class="panel-body">
							<div class="col-md-4>
								<form name="basic" method="POST" action="login.php">
								<table class="table table-condensed" 
									style="border: 1px solid #dddddd; 
									border-radius: 5px; box-shadow: 2px 2px 10px; margin: 0 auto; width:250px">
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
									</table>
								</form>
							</div>
						</div>
					</div>
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">Sign Up</a>
            				</h4>
            			</div>
            			<div id="collapseTwo" class="panel-collapse collapse">
            				<div class="panel-body">
            					<div class="col-md-4>
								<form name="basic" method="POST" action="login.php">
								<table class="table table-condensed" 
									style="border: 1px solid #dddddd; 
									border-radius: 5px; box-shadow: 2px 2px 10px; margin: 0 auto; width:250px">
									<tr><td colspan="2" style="text-align: center; 
									border-radius: 5px; color: white; 
									background-color:#333333;">
									<h2>Sign up Form</h2></td></tr>
									<tr><td>Email: </td><td><input type="edit"
									name="user_email" value="" size="20"</td></tr>
									<tr><td>Name: </td><td><input type="edit"
									name="user_name" value="" size="20"</td></tr>
									<tr><td>Password: </td><td><input type="password"
									name="user_password" value="" size="20"</td></tr>
									<tr><td><input type="submit" name="sign_up_try"
									class="btn btn-primary" value="Sign up"></td>
									</table>
									</form>
            				</div>
            			</div>
            		</div>
            		</div>';
	
}