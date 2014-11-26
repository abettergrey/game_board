<?php

session_start();

$user_email = $_POST["user_email"];
$user_password = $_POST["user_password"];
$user_id = $_POST["user_id"];

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
									class="btn btn-success" value="Sign up"></td>
									</table>
            				</div>
            			</div>
            		</div>
            		</div>';
	
}