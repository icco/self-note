<?php

include "backend.php";

if(isset($_POST['user']))
{
	$user = $_POST['user'];
}

if(isset($_POST['password']))
{
	$pw = $_POST['password'];
}

if(isset($pw) && isset($user))
{
	$db = connect();
	$ans = login($db, $user, $pw);

	if($ans)
	{
		// Aww this doesn't work...
		header('Location: index.php');
	}
}

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Welcome to SelfNote</title>
<link href="style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="container">
<h1>SelfNote.</h1>
<?php 

if(isset($ans) AND !($ans))
{
	print '<div class="error">Wrong Username or Password</div>';
}

?>
<form method="post" action="auth.php">
<table align="center">
<tr>
<td><label for="user">Email:</label></td>
<td><input type="text" id="user" name="user" size="20" /></td>
</tr>
<tr>
<td><label for="password">Password:</label></td>

<td><input type="password" id="password" name="password" size="20" />
<a href="reset.php" style="font-size: 10px; color: grey;">Forgot?</a></td>
</tr>
<tr><td>&nbsp;</td><td><input type="submit" value="Login" /></td></tr>
</table>
</form>

</div>
</body>
</html>
